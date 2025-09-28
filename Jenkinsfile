/*******************************************************
 * Janak Travels – CI/CD Pipeline (Windows Agent)
 *
 * Description:
 * End-to-end CI/CD for a PHP app containerized with Docker.
 * Stages: checkout → PHP lint → SonarCloud → image build →
 * (optional) Trivy scan → staging deploy (docker compose) →
 * smoke tests → (optional) push → prod placeholders.
 *
 * Agent: Windows (Jenkins node) with Docker Desktop.
 *
 * Jenkins Credentials:
 *   - github-credentials : SCM (in multibranch)
 *   - sonar-token        : SonarCloud token (Secret text)
 *   - ghcr               : (optional) push to GHCR
 *
 * External Prereqs:
 *   - Docker Desktop installed & logged-in once
 *   - SonarCloud project available to sonar-token
 *   - docker-compose.staging.yml in repo root
 *******************************************************/

pipeline {
  agent any

  options {
    ansiColor('xterm')
    timestamps()
    buildDiscarder(logRotator(numToKeepStr: '15'))
    disableConcurrentBuilds()
  }

  environment {
    // App / branch
    APP_NAME           = 'janak-travels'
    BRANCH_NAME_SAFE   = "${env.BRANCH_NAME ?: 'main'}"

    // Image tags
    IMAGE_LOCAL_TAG    = "${APP_NAME}:latest"
    IMAGE_STAGING_TAG  = "${APP_NAME}:staging"
    IMAGE_PROD_TAG     = "${APP_NAME}:prod"

    // Staging (compose)
    STAGING_PROJECT    = 'janak-staging'
    STAGING_COMPOSE    = 'docker-compose.staging.yml'
    STAGING_HTTP_PORT  = '8081'
    STAGING_URL        = "http://localhost:${STAGING_HTTP_PORT}"

    // Optional registry push
    REG_PUSH           = 'false'     // 'true' to enable stage 9
    REG_HOST           = 'ghcr.io'
    REG_NAMESPACE      = 'brijesh-palta'
    REG_CREDENTIALS_ID = 'ghcr'
    REG_IMAGE          = "${REG_HOST}/${REG_NAMESPACE}/${APP_NAME}"

    // SonarCloud
    SONAR_HOST_URL     = 'https://sonarcloud.io'
    SONAR_ORG          = 'brijesh-palta'
    SONAR_PROJECT_KEY  = 'Janak-Travels'

    // Trivy optional
    TRIVY_ENABLED      = 'false'
    TRIVY_IMAGE        = 'aquasec/trivy:latest'
  }

  stages {

    /*********************************************************
     * 0) Ensure Docker Daemon Running (Windows)
     *   - Use native Jenkins 'powershell' (no caret mishaps)
     *********************************************************/
    stage('0) Ensure Docker Running') {
      steps {
        powershell '''
          $ErrorActionPreference = "Stop"

          # Start Docker service if present and not running
          $svc = Get-Service -Name 'com.docker.service' -ErrorAction SilentlyContinue
          if ($svc -and $svc.Status -ne 'Running') {
            Start-Service -Name 'com.docker.service'
          }

          # Wait up to 60s for daemon to respond
          $deadline = (Get-Date).AddSeconds(60)
          $ok = $false
          while(-not $ok -and (Get-Date) -lt $deadline) {
            try {
              docker info *> $null
              $ok = $true
            } catch {
              Start-Sleep -Seconds 2
            }
          }
          if (-not $ok) { throw "Docker daemon did not become ready in time." }

          docker version
        '''
      }
    }

    /**********************************************
     * 1) Source Checkout and Version Traceability
     **********************************************/
    stage('1) Checkout & Version') {
      steps {
        checkout scm
        bat '''
          for /F "usebackq tokens=1" %%i in (`git rev-parse --short HEAD`) do @echo %%i > sha.txt
        '''
        script {
          def sha = readFile('sha.txt').trim()
          echo "Checkout completed. Current commit SHA: ${sha}"
        }
      }
    }

    /***************************************
     * 2) PHP Syntax Lint via php:8.2-cli
     ***************************************/
    stage('2) PHP Lint (via Docker)') {
      steps {
        bat 'docker version'
        bat """
          docker run --rm -v "%WORKSPACE%":/app -w /app php:8.2-cli ^
            bash -lc "set -e; find . -type f -name '*.php' -print0 | xargs -0 -n1 php -l"
        """
      }
    }

    /***************************************************
     * 3) Static Code Analysis – SonarCloud (Dockerized)
     ***************************************************/
    stage('3) Code Quality (SonarCloud)') {
      steps {
        withCredentials([string(credentialsId: 'sonar-token', variable: 'SC_TOKEN')]) {
          bat """
            docker run --rm -e SONAR_TOKEN=%SC_TOKEN% ^
              -v "%WORKSPACE%":/usr/src sonarsource/sonar-scanner-cli:5 ^
              sonar-scanner ^
                -Dsonar.host.url=${SONAR_HOST_URL} ^
                -Dsonar.organization=${SONAR_ORG} ^
                -Dsonar.projectKey=${SONAR_PROJECT_KEY}
          """
        }
      }
    }

    /***********************************************
     * 4) Build Docker Image
     ***********************************************/
    stage('4) Build Docker Image') {
      steps {
        bat "docker build -t ${IMAGE_LOCAL_TAG} ."
      }
    }

    /************************************************************
     * 5) Image Security Scan with Trivy (optional)
     ************************************************************/
    stage('5) Security Scan (Trivy)') {
      when { expression { env.TRIVY_ENABLED == 'true' } }
      steps {
        bat """
          docker run --rm ^
            -v /var/run/docker.sock:/var/run/docker.sock ^
            -v "%USERPROFILE%/.cache/trivy":/root/.cache/ ^
            ${TRIVY_IMAGE} image ${IMAGE_LOCAL_TAG}
        """
      }
    }

    /*********************************************************
     * 6) Free Staging Port if occupied
     *********************************************************/
    stage('6) Free Port (if used)') {
      steps {
        bat """
          for /F "tokens=*" %%i in ('docker ps -q --filter "publish=${STAGING_HTTP_PORT}"') do @docker rm -f %%i
        """
      }
    }

    /********************************************
     * 7) Deploy to Staging (docker compose)
     ********************************************/
    stage('7) Deploy Staging') {
      steps {
        bat """
          docker compose -p ${STAGING_PROJECT} -f ${STAGING_COMPOSE} down || exit /b 0
          docker compose -p ${STAGING_PROJECT} -f ${STAGING_COMPOSE} up -d --build
        """
      }
    }

    /********************************************
     * 8) Smoke Test (Staging)
     ********************************************/
    stage('8) Smoke Test (Staging)') {
      steps {
        // Health endpoint should return 200 (or output)
        bat "curl -fsS ${STAGING_URL}/health.php"

        // Login page must return HTTP 200
        bat """
          curl -s -o NUL -w "HTTP_CODE=%%{http_code}" "${STAGING_URL}/loginpage.php" > status.txt
          find "HTTP_CODE=200" status.txt >nul || (echo Smoke test failed & exit /b 1)
        """
      }
    }

    /**************************************************************
     * 9) Push to Registry (optional; main + REG_PUSH=true)
     **************************************************************/
    stage('9) Push to Registry') {
      when {
        allOf {
          branch 'main'
          expression { env.REG_PUSH == 'true' }
        }
      }
      steps {
        withCredentials([string(credentialsId: env.REG_CREDENTIALS_ID, variable: 'REG_TOKEN')]) {
          bat """
            echo %REG_TOKEN% | docker login ${REG_HOST} -u ${REG_NAMESPACE} --password-stdin
            docker tag ${IMAGE_LOCAL_TAG} ${REG_IMAGE}:latest
            docker push ${REG_IMAGE}:latest
          """
        }
      }
    }

    /*********************************************************
     * 10) Production Deployment (placeholder)
     *********************************************************/
    stage('10) Deploy Production') {
      when { branch 'main' }
      steps {
        echo "Production deployment placeholder — replace with real logic (SSH/WinRM/K8s/CDN, etc.)."
      }
    }

    /*********************************************************
     * 11) Production Monitoring (placeholder)
     *********************************************************/
    stage('11) Monitoring: Production') {
      when { branch 'main' }
      steps {
        echo "Production monitoring placeholder — add your health checks / Synthetics / APM here."
      }
    }
  }

  post {
    success {
      echo "Pipeline completed successfully"
    }
    failure {
      echo "Pipeline failed — check logs of failed stage"
    }
    always {
      bat "docker ps --format \"table {{.ID}}\\t{{.Names}}\\t{{.Status}}\\t{{.Ports}}\""
    }
  }
}

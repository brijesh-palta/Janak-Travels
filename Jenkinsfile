/*******************************************************
 * Janak Travels – CI/CD Pipeline
 *
 * Description:
 * End-to-end CI/CD for a PHP application containerized
 * with Docker. Stages include source checkout, PHP lint,
 * static analysis with SonarCloud, container build,
 * optional image security scanning, staging deployment,
 * smoke tests, optional registry push, and production
 * placeholders for deploy and monitoring.
 *
 * Agent/OS:
 * Windows Jenkins agent with Docker Desktop.
 *
 * Required Jenkins Credentials:
 *   - github-credentials  : for SCM checkout (already in multibranch)
 *   - sonar-token         : SonarCloud token (Secret text)
 *   - ghcr                : Token/password to push to GitHub Container Registry (optional)
 *
 * External Prerequisites:
 *   - Docker Desktop installed on the agent and logged in at least once
 *   - SonarCloud project exists and is accessible by the sonar-token
 *   - docker-compose.staging.yml present in the repo
 *******************************************************/

pipeline {
  agent any

  options {
    ansiColor('xterm')                               // Colored output
    timestamps()                                     // Timestamp every line
    buildDiscarder(logRotator(numToKeepStr: '15'))   // Keep last 15 builds
    disableConcurrentBuilds()                        // Avoid overlapping runs
  }

  environment {
    // Application naming
    APP_NAME          = 'janak-travels'
    BRANCH_NAME_SAFE  = "${env.BRANCH_NAME ?: 'main'}"

    // Image tags
    IMAGE_LOCAL_TAG   = "${APP_NAME}:latest"
    IMAGE_STAGING_TAG = "${APP_NAME}:staging"
    IMAGE_PROD_TAG    = "${APP_NAME}:prod"

    // Staging deploy (Docker Compose)
    STAGING_PROJECT   = 'janak-staging'
    STAGING_COMPOSE   = 'docker-compose.staging.yml'
    STAGING_HTTP_PORT = '8081'
    STAGING_URL       = "http://localhost:${STAGING_HTTP_PORT}"

    // Registry push (optional)
    REG_PUSH          = 'false'                      // set to 'true' to enable stage 9
    REG_HOST          = 'ghcr.io'
    REG_NAMESPACE     = 'brijesh-palta'
    REG_CREDENTIALS_ID= 'ghcr'
    REG_IMAGE         = "${REG_HOST}/${REG_NAMESPACE}/${APP_NAME}"

    // SonarCloud
    SONAR_HOST_URL    = 'https://sonarcloud.io'
    SONAR_ORG         = 'brijesh-palta'              // organization key (slug with hyphen)
    SONAR_PROJECT_KEY = 'Janak-Travels'              // must match key in SonarCloud UI

    // Security scan (optional)
    TRIVY_ENABLED     = 'false'                      // set to 'true' to enable stage 5
    TRIVY_IMAGE       = 'aquasec/trivy:latest'
  }

  stages {

    /*********************************************************
     * 0) Ensure Docker Daemon Running (Windows only)
     * Purpose:
     *   - Start the Docker Windows service if present and not
     *     running, then wait until the daemon responds.
     * Notes:
     *   - Uses PowerShell through a batch step.
     *********************************************************/
    stage('0) Ensure Docker Running') {
      steps {
        bat '''
        @echo on
        rem Attempt to start the Windows service for Docker Desktop if available
        powershell -NoProfile -Command ^
          "$svc = Get-Service -Name 'com.docker.service' -ErrorAction SilentlyContinue; ^
           if ($null -ne $svc -and $svc.Status -ne 'Running') { Start-Service -Name 'com.docker.service' }"

        rem Wait up to ~60 seconds for Docker daemon to become responsive
        setlocal enabledelayedexpansion
        set /a _wait=0
        :wait_loop
          docker info >nul 2>&1
          if !errorlevel! == 0 goto docker_ok
          if !_wait! GEQ 30 goto docker_fail
          timeout /t 2 >nul
          set /a _wait+=1
          goto wait_loop

        :docker_ok
        echo Docker is running.
        docker version
        goto :eof

        :docker_fail
        echo ERROR: Docker daemon did not become ready in time.
        exit /b 1
        '''
      }
    }

    /**********************************************
     * 1) Source Checkout and Version Traceability
     **********************************************/
    stage('1) Checkout & Version') {
      steps {
        checkout scm
        script {
          bat '''
          for /F "usebackq tokens=1" %%i in (`git rev-parse --short HEAD`) do @echo %%i > sha.txt
          '''
          def sha = readFile('sha.txt').trim()
          echo "Checkout completed. Current commit SHA: ${sha}"
        }
      }
    }

    /***************************************
     * 2) PHP Syntax Lint using php:8.2-cli
     ***************************************/
    stage('2) PHP Lint (via Docker)') {
      steps {
        // Quick failure if Docker disappears after stage 0
        bat 'docker version'
        // Run php -l across all PHP files inside a disposable container
        bat """
        docker run --rm -v "%WORKSPACE%":/app -w /app php:8.2-cli ^
          bash -lc "set -e; find . -type f -name '*.php' -print0 | xargs -0 -n1 php -l"
        """
      }
    }

    /***************************************************
     * 3) Static Code Analysis – SonarCloud
     *    Uses official sonar-scanner-cli Docker image.
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
     * 4) Build Docker Image from repository Dockerfile
     ***********************************************/
    stage('4) Build Docker Image') {
      steps {
        bat "docker build -t ${IMAGE_LOCAL_TAG} ."
      }
    }

    /************************************************************
     * 5) Image Security Scan with Trivy (optional)
     *    Set TRIVY_ENABLED=true in environment to enable.
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
     * 6) Free Staging Port
     *    Remove any container already bound to the staging port.
     *********************************************************/
    stage('6) Free Port (if used)') {
      steps {
        bat """
        for /F "tokens=*" %%i in ('docker ps -q --filter "publish=${STAGING_HTTP_PORT}"') do @docker rm -f %%i
        """
      }
    }

    /********************************************
     * 7) Deploy to Staging via Docker Compose
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
     * 8) Smoke Test against Staging endpoints
     ********************************************/
    stage('8) Smoke Test (Staging)') {
      steps {
        // Application health endpoint
        bat "curl -fsS ${STAGING_URL}/health.php"
        // Simple page check must return HTTP 200
        bat """
        curl -s -o NUL -w "HTTP_CODE=%%{http_code}" "${STAGING_URL}/loginpage.php" > status.txt
        find "HTTP_CODE=200" status.txt >nul || (exit /b 1)
        """
      }
    }

    /**************************************************************
     * 9) Push to Registry (optional, main branch and REG_PUSH=true)
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
     * 10) Production Deployment (placeholder – customize)
     *********************************************************/
    stage('10) Deploy Production') {
      when { branch 'main' }
      steps {
        echo "Production deployment placeholder. Replace with real production deployment logic."
      }
    }

    /*********************************************************
     * 11) Production Monitoring (placeholder – customize)
     *********************************************************/
    stage('11) Monitoring: Production') {
      when { branch 'main' }
      steps {
        echo "Production monitoring placeholder. Add health checks here."
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
      // Quick view of containers after the run
      bat "docker ps --format \"table {{.ID}}\\t{{.Names}}\\t{{.Status}}\\t{{.Ports}}\""
    }
  }
}

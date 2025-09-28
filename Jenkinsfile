/*******************************************************
 * Janak Travels – CI/CD Pipeline (Windows Agent, Final)
 *
 * Stages: checkout → PHP lint → unit tests (PHPUnit) →
 * SonarCloud → build image → security scan (Trivy FS) →
 * free port → staging deploy (compose w/ fallback) →
 * smoke tests → (optional) push → prod/monitor placeholders.
 *
 * Agent: Windows Jenkins node with Docker Desktop.
 * Creds: sonar-token (Secret text), ghcr (optional).
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
    APP_NAME            = 'janak-travels'
    BRANCH_NAME_SAFE    = "${env.BRANCH_NAME ?: 'main'}"

    IMAGE_LOCAL_TAG     = "${APP_NAME}:latest"
    IMAGE_STAGING_TAG   = "${APP_NAME}:staging"
    IMAGE_PROD_TAG      = "${APP_NAME}:prod"

    STAGING_PROJECT     = 'janak-staging'
    STAGING_COMPOSE     = 'docker-compose.staging.yml'
    STAGING_HTTP_PORT   = '8081'
    STAGING_URL         = "http://localhost:${STAGING_HTTP_PORT}"

    REG_PUSH            = 'false'      // 'true' to enable stage 9
    REG_HOST            = 'ghcr.io'
    REG_NAMESPACE       = 'brijesh-palta'
    REG_CREDENTIALS_ID  = 'ghcr'
    REG_IMAGE           = "${REG_HOST}/${REG_NAMESPACE}/${APP_NAME}"

    SONAR_HOST_URL      = 'https://sonarcloud.io'
    SONAR_ORG           = 'brijesh-palta'
    SONAR_PROJECT_KEY   = 'Janak-Travels'

    TRIVY_ENABLED       = 'true'       // ENABLED: filesystem scan (Windows-safe)
    TRIVY_IMAGE         = 'aquasec/trivy:latest'
  }

  stages {

    /**********************
     * 0) Docker Up (Win)
     **********************/
    stage('0) Ensure Docker Running') {
      steps {
        powershell '''
          $ErrorActionPreference = "Stop"

          # Start Docker service if present and not running
          $svc = Get-Service -Name 'com.docker.service' -ErrorAction SilentlyContinue
          if ($svc -and $svc.Status -ne 'Running') {
            Start-Service -Name 'com.docker.service'
          }

          # Wait up to 60s for daemon
          $deadline = (Get-Date).AddSeconds(60)
          $ok = $false
          while(-not $ok -and (Get-Date) -lt $deadline) {
            try { docker info *> $null; $ok = $true } catch { Start-Sleep -Seconds 2 }
          }
          if (-not $ok) { throw "Docker daemon did not become ready in time." }

          docker version
        '''
      }
    }

    /**********************************************
     * 1) Source Checkout & Version (Windows-safe)
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
     * 2) PHP Lint (php -l inside container)
     ***************************************/
    stage('2) PHP Lint (via Docker)') {
      steps {
        bat 'docker version'
        bat """
          docker run --rm -v "%WORKSPACE%":/app -w /app php:8.2-cli ^
            bash -lc "set -euo pipefail; if command -v find >/dev/null 2>&1; then f=find; else f=/usr/bin/find; fi; $f . -type f -name '*.php' -print0 | xargs -0 -n1 php -l"
        """
      }
    }

    /***************************************
     * 2.5) Unit Tests (PHPUnit via Composer)
     ***************************************/
    stage('2.5) Unit Tests (PHPUnit)') {
      steps {
        // Installs phpunit in workspace vendor/ using composer image, then runs tests
        bat """
          docker run --rm -v "%WORKSPACE%":/app -w /app composer:2 ^
            sh -lc "set -e; [ -f composer.json ] || (echo '{\"require-dev\":{\"phpunit/phpunit\":\"^10\"}}' > composer.json); composer install --no-interaction --prefer-dist; ./vendor/bin/phpunit --log-junit junit.xml || (echo 'PHPUnit failed' && exit 1)"
        """
        // Archive JUnit XML if present
        junit allowEmptyResults: true, testResults: 'junit.xml'
      }
    }

    /***************************************************
     * 3) Code Quality – SonarCloud (TEMP WORKDIR FIX)
     ***************************************************/
    stage('3) Code Quality (SonarCloud)') {
      steps {
        withCredentials([string(credentialsId: 'sonar-token', variable: 'SC_TOKEN')]) {
          bat """
            docker run --rm -e SONAR_TOKEN=%SC_TOKEN% ^
              -v "%WORKSPACE%":/usr/src sonarsource/sonar-scanner-cli:5 ^
              sh -lc "set -e; mkdir -p /sonar-tmp; sonar-scanner \
                -Dsonar.host.url=${SONAR_HOST_URL} \
                -Dsonar.organization=${SONAR_ORG} \
                -Dsonar.projectKey=${SONAR_PROJECT_KEY} \
                -Dsonar.working.directory=/sonar-tmp"
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
     * 5) Security Scan – Trivy (Filesystem; Windows-friendly)
     *    Scans repo FS instead of Docker daemon (no socket).
     ************************************************************/
    stage('5) Security Scan (Trivy FS)') {
      when { expression { env.TRIVY_ENABLED == 'true' } }
      steps {
        bat """
          docker run --rm ^
            -v "%WORKSPACE%":/src ^
            ${TRIVY_IMAGE} fs --severity HIGH,CRITICAL --exit-code 0 --no-progress /src > trivy-fs.txt
        """
        archiveArtifacts artifacts: 'trivy-fs.txt', onlyIfSuccessful: true
      }
    }

    /*********************************************************
     * 6) Free Staging Port (kill any container on 8081)
     *********************************************************/
    stage('6) Free Port (if used)') {
      steps {
        bat """
          for /F "tokens=*" %%i in ('docker ps -q --filter "publish=${STAGING_HTTP_PORT}"') do @docker rm -f %%i
        """
      }
    }

    /********************************************
     * 7) Deploy Staging (compose with fallback)
     ********************************************/
    stage('7) Deploy Staging') {
      steps {
        // Try modern 'docker compose', fallback to legacy 'docker-compose'
        bat """
          docker compose version >NUL 2>&1
          if %ERRORLEVEL%==0 (
            docker compose -p ${STAGING_PROJECT} -f ${STAGING_COMPOSE} down || exit /b 0
            docker compose -p ${STAGING_PROJECT} -f ${STAGING_COMPOSE} up -d --build
          ) else (
            docker-compose -p ${STAGING_PROJECT} -f ${STAGING_COMPOSE} down || exit /b 0
            docker-compose -p ${STAGING_PROJECT} -f ${STAGING_COMPOSE} up -d --build
          )
        """
      }
    }

    /********************************************
     * 8) Smoke Test (curl OR PowerShell fallback)
     ********************************************/
    stage('8) Smoke Test (Staging)') {
      steps {
        // Health endpoint must respond
        bat """
          where curl >NUL 2>&1
          if %ERRORLEVEL%==0 (
            curl -fsS ${STAGING_URL}/health.php
          ) else (
            powershell -NoProfile -Command "Invoke-WebRequest -UseBasicParsing '${STAGING_URL}/health.php' | Out-Null"
          )
        """

        // loginpage.php must return HTTP 200
        bat """
          where curl >NUL 2>&1
          if %ERRORLEVEL%==0 (
            curl -s -o NUL -w "HTTP_CODE=%%{http_code}" "${STAGING_URL}/loginpage.php" > status.txt
            find "HTTP_CODE=200" status.txt >nul || (echo Smoke test failed & exit /b 1)
          ) else (
            powershell -NoProfile -Command "$r=Invoke-WebRequest -UseBasicParsing '${STAGING_URL}/loginpage.php'; if($r.StatusCode -ne 200){ exit 1 }"
          )
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
     * 10) Deploy Production (placeholder – customize)
     *********************************************************/
    stage('10) Deploy Production') {
      when { branch 'main' }
      steps {
        echo "Prod deploy placeholder — add your prod compose/WinRM/SSH/K8s steps."
      }
    }

    /*********************************************************
     * 11) Monitoring (placeholder – customize)
     *********************************************************/
    stage('11) Monitoring: Production') {
      when { branch 'main' }
      steps {
        echo "Monitoring placeholder — add uptime/APM/Synthetics + alerts."
      }
    }
  }

  post {
    success {
      echo "Pipeline completed successfully"
    }
    failure {
      echo "Pipeline failed — check the stage logs above"
    }
    always {
      bat "docker ps --format \"table {{.ID}}\\t{{.Names}}\\t{{.Status}}\\t{{.Ports}}\""
      archiveArtifacts allowEmptyArchive: true, artifacts: 'status.txt,junit.xml,trivy-fs.txt'
    }
  }
}

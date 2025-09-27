// Jenkins Declarative Pipeline for PHP app (Windows agent + Docker)
// Stages: Build, Test, Code Quality, Security, Deploy, Release, Monitoring
// NOTE: Ensure this Jenkins node has: Docker, curl, and access to the internet.

pipeline {
  agent any

  options {
    // Helpful output options
    timestamps()
    ansiColor('xterm')
    skipDefaultCheckout(true)
    // Keep only the last 10 builds to save disk
    buildDiscarder(logRotator(numToKeepStr: '10'))
  }

  // -------- Global environment (edit to your needs) --------
  environment {
    // Registry settings (override by Jenkins "Parameters" or Folder-level envs if you want)
    REGISTRY_HOST = 'ghcr.io'                 // e.g. ghcr.io or index.docker.io
    REGISTRY_USER = 'brijesh-palta'           // your registry username / org
    REGISTRY_REPO = 'janak-travels'           // image name in the registry

    // Credentials in Jenkins (must exist in Jenkins -> Credentials)
    // For GitHub Container Registry: create a "Username with password" where:
    //   - Username = your GitHub username
    //   - Password = a GitHub PAT with package:write, package:read, repo scopes
    //   - ID = 'ghcr-creds' (or change the string below)
    REGISTRY_CRED_ID = 'ghcr-creds'           // <-- CHANGE if you used a different ID

    // SonarCloud token credentials (Jenkins -> Credentials -> "Secret text")
    //   ID should be 'sonar-token' (or update below)
    SONAR_TOKEN_ID = 'sonar-token'

    // Optional toggles
    RUN_TRIVY = 'true'                        // Set 'false' to skip Security scan
    // Application networking
    STAGING_HTTP_PORT = '8081'                // Staging port exposed by docker-compose
    PROD_HTTP_PORT    = '8080'                // Prod port exposed by docker-compose
  }

  stages {

    // 0) Make sure Docker daemon is running on the Windows agent
    stage('0) Ensure Docker Daemon') {
      steps {
        bat '''
        @echo on
        REM Try Docker Desktop service first
        sc query com.docker.service >NUL 2>&1
        if %ERRORLEVEL%==0 (
          for /f "tokens=3" %%s in ('sc query com.docker.service ^| find "STATE"') do set STATE=%%s
          if /I NOT "%STATE%"=="RUNNING" (
            echo [INFO] Starting com.docker.service ...
            net start com.docker.service 1>NUL 2>NUL || powershell -NoProfile -Command "Start-Service -Name com.docker.service"
          )
        ) else (
          REM Fallback to classic 'docker' service (Windows Server scenario)
          sc query docker >NUL 2>&1
          if %ERRORLEVEL%==0 (
            for /f "tokens=3" %%s in ('sc query docker ^| find "STATE"') do set STATE=%%s
            if /I NOT "%STATE%"=="RUNNING" (
              echo [INFO] Starting docker service ...
              net start docker 1>NUL 2>NUL || powershell -NoProfile -Command "Start-Service -Name docker"
            )
          ) else (
            echo [WARN] No Docker service found by name. Assuming Docker Desktop GUI may auto-start.
          )
        )

        REM Wait until docker is responsive (max ~60s)
        setlocal enabledelayedexpansion
        set RETRIES=30
        for /L %%i in (1,1,%RETRIES%) do (
          docker info 1>NUL 2>NUL && (echo [OK] Docker is up. & exit /b 0)
          echo [WAIT] Docker not ready yet... (%%i/%RETRIES%)
          timeout /T 2 /NOBREAK >NUL
        )
        echo [ERROR] Docker daemon not up after waiting. Failing the build.
        exit /b 1
        '''
      }
    }

    // 1) Checkout code and compute IMAGE_TAG
    stage('1) Checkout & Version') {
      steps {
        checkout scm
        script {
          // Write short commit SHA to sha.txt (CAREFUL: Use %%i in Windows batch)
          bat '''for /F "usebackq tokens=1" %%i in (`git rev-parse --short HEAD`) do @echo %%i > sha.txt'''
          def sha = fileExists('sha.txt') ? readFile('sha.txt').trim() : "local"
          // Ensure BRANCH_NAME exists (for multibranch it is auto-set)
          if (!env.BRANCH_NAME) { env.BRANCH_NAME = "main" }
          // IMAGE_TAG: branch-buildNumber-sha, e.g., main-42-a1b2c3d
          env.IMAGE_TAG = "${env.BRANCH_NAME}-${env.BUILD_NUMBER}-${sha}"

          echo "✔ Checkout complete"
          echo "IMAGE_TAG = ${env.IMAGE_TAG}"
          echo "REGISTRY = ${env.REGISTRY_HOST}/${env.REGISTRY_USER}/${env.REGISTRY_REPO}"
        }
      }
    }

    // 2) "Test": PHP syntax linting (fast check using official PHP CLI image)
    stage('2) Test: PHP Lint (Docker)') {
      steps {
        bat """
        docker run --rm ^
          -v "%CD%":/app ^
          -w /app php:8.2-cli ^
          bash -lc "set -e; find . -type f -name '*.php' -print0 | xargs -0 -n1 php -l"
        """
      }
    }

    // 3) Code Quality: SonarCloud scan (using sonar-scanner Docker image)
    stage('3) Code Quality (SonarCloud)') {
      environment {
        // These are read by the docker container
        SONAR_HOST_URL = 'https://sonarcloud.io'
        SONAR_ORG      = 'brijesh-palta'       // <-- CHANGE if needed
        SONAR_PROJECT  = 'janak-travels'       // <-- CHANGE if needed
      }
      steps {
        withCredentials([string(credentialsId: env.SONAR_TOKEN_ID, variable: 'SC_TOKEN')]) {
          bat """
          docker run --rm ^
            -e SONAR_TOKEN=%SC_TOKEN% ^
            -v "%CD%":/usr/src sonarsource/sonar-scanner-cli:5 ^
            sonar-scanner ^
              -Dsonar.host.url=%SONAR_HOST_URL% ^
              -Dsonar.organization=%SONAR_ORG% ^
              -Dsonar.projectKey=%SONAR_PROJECT% ^
              -Dsonar.sources=. ^
              -Dsonar.exclusions=**/node_modules/**,**/*.jpg,**/*.jpeg,**/*.png,**/*.gif,**/*.css,**/*.js,**/build-wrapper-dump.json
          """
        }
      }
    }

    // 4) Build: Docker image for the app
    stage('4) Build Docker Image') {
      steps {
        bat """
        docker build -t ${env.REGISTRY_REPO}:latest .
        docker tag ${env.REGISTRY_REPO}:latest ${env.REGISTRY_REPO}:${env.IMAGE_TAG}
        """
      }
    }

    // 5) Security: Container image scan with Trivy (optional)
    stage('5) Security Scan (Trivy)') {
      when { expression { return env.RUN_TRIVY?.toLowerCase() == 'true' } }
      steps {
        // Use Trivy Docker image to scan the *built* image
        bat """
        docker run --rm ^
          -v "%CD%":/work ^
          aquasec/trivy:latest image --exit-code 0 --no-progress ${env.REGISTRY_REPO}:${env.IMAGE_TAG}
        """
        // If you want to fail on high/critical vulns, change --exit-code 1 and add --severity HIGH,CRITICAL
      }
    }

    // 6) Deploy (Staging): first free the port if something is bound
    stage('6) Free Port ' + "${STAGING_HTTP_PORT}" + ' (if used)') {
      steps {
        // Remove any container that has the port published (best-effort)
        bat """
        for /F "tokens=*" %%i in ('docker ps -q --filter "publish=${env.STAGING_HTTP_PORT}"') do @docker rm -f %%i
        """
      }
    }

    // 7) Deploy (Staging): docker compose up
    stage('7) Deploy Staging (docker compose)') {
      steps {
        bat """
        docker compose -p janak-staging -f docker-compose.staging.yml down || exit /b 0
        docker compose -p janak-staging -f docker-compose.staging.yml up -d --build
        """
      }
    }

    // 8) Monitoring: simple smoke on Staging
    stage('8) Monitoring: Smoke on Staging') {
      steps {
        // 1) Health endpoint must return JSON fast
        bat """
        curl -fsS http://localhost:${env.STAGING_HTTP_PORT}/health.php || exit /b 1
        """

        // 2) Login page (or any public page) should return HTTP 200
        bat """
        curl -s -o NUL -w "HTTP_CODE=%%{http_code}" "http://localhost:${env.STAGING_HTTP_PORT}/loginpage.php" 1>status.txt
        find "HTTP_CODE=200" status.txt  1>nul 2>&1
        if errorlevel 1 (
          echo "Login page not available or not returning 200"
          type status.txt
          exit /b 1
        ) else (
          echo "[OK] Login page returned 200 OK"
        )
        """
      }
    }

    // 9) Release: Push image to registry (main branch only)
    stage('9) Release: Push to Registry (main only)') {
      when { branch 'main' }
      steps {
        withCredentials([usernamePassword(credentialsId: env.REGISTRY_CRED_ID,
                                          usernameVariable: 'R_USER',
                                          passwordVariable: 'R_PASS')]) {
          script {
            // Full image refs for registry
            def LATEST = "${env.REGISTRY_HOST}/${env.REGISTRY_USER}/${env.REGISTRY_REPO}:latest"
            def TAGGED = "${env.REGISTRY_HOST}/${env.REGISTRY_USER}/${env.REGISTRY_REPO}:${env.IMAGE_TAG}"

            bat """
            echo [INFO] Logging into ${env.REGISTRY_HOST} as %R_USER%
            echo %R_PASS% | docker login ${env.REGISTRY_HOST} -u %R_USER% --password-stdin

            docker tag ${env.REGISTRY_REPO}:latest ${LATEST}
            docker tag ${env.REGISTRY_REPO}:${env.IMAGE_TAG} ${TAGGED}

            docker push ${LATEST}
            docker push ${TAGGED}
            """
          }
        }
      }
    }

    // 10) Deploy (Production): pull and start prod compose (main only)
    stage('10) Deploy Production (main only)') {
      when { branch 'main' }
      steps {
        // If your prod server is a separate host, do deployment via SSH from here.
        // This example assumes you're deploying on the same Jenkins node for demo.
        bat """
        docker compose -p janak-prod -f docker-compose.prod.yml down || exit /b 0
        docker compose -p janak-prod -f docker-compose.prod.yml up -d --pull always --build
        """
      }
    }

    // 11) Monitoring: simple smoke on Prod (main only)
    stage('11) Monitoring: Smoke on Prod (main only)') {
      when { branch 'main' }
      steps {
        bat """
        curl -fsS http://localhost:${env.PROD_HTTP_PORT}/health.php || exit /b 1
        curl -s -o NUL -w "HTTP_CODE=%%{http_code}" "http://localhost:${env.PROD_HTTP_PORT}/loginpage.php" 1>status.txt
        find "HTTP_CODE=200" status.txt  1>nul 2>&1
        if errorlevel 1 (
          echo "Prod login page not available or not returning 200"
          type status.txt
          exit /b 1
        ) else (
          echo "[OK] Prod login page returned 200 OK"
        )
        """
      }
    }
  }

  // Post actions for visibility & cleanup
  post {
    success {
      echo 'Pipeline SUCCEEDED ✅'
      archiveArtifacts artifacts: 'status.txt', onlyIfSuccessful: true
    }
    failure {
      echo 'Pipeline FAILED ❌ — please check the first red stage logs.'
      archiveArtifacts artifacts: 'status.txt', allowEmptyArchive: true
    }
    always {
      // Optional: clean up dangling images/containers (be careful on shared agents)
      echo 'Pipeline finished. ✅'
    }
  }
}

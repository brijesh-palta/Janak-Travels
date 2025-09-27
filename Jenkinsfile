pipeline {
  agent any

  environment {
    IMAGE_NAME = "janak-travels"
    REGISTRY   = "ghcr.io/brijesh-palta"
    IMAGE_TAG  = "latest"   // fallback
    REG_HOST   = ""
  }

  options {
    timestamps()
    skipDefaultCheckout(true)
    buildDiscarder(logRotator(numToKeepStr: '30'))
  }

  triggers { pollSCM('H/5 * * * *') }

  stages {

    stage('1) Checkout & Version') {
      steps {
        checkout scm
        script {
          // Get short SHA
          bat '''
          for /f "usebackq tokens=1" %%i in (`git rev-parse --short HEAD`) do @echo %%i > sha.txt
          '''
          def sha = fileExists('sha.txt') ? readFile('sha.txt').trim() : "local"
          if (!env.BRANCH_NAME) { env.BRANCH_NAME = "main" }
          env.IMAGE_TAG = "${env.BRANCH_NAME}-${env.BUILD_NUMBER}-${sha}"
          env.REG_HOST  = (env.REGISTRY.split('/')[0])
          echo "✔ Checkout done"
          echo "IMAGE_TAG=${env.IMAGE_TAG}"
          echo "REG_HOST=${env.REG_HOST}"
        }
      }
    }

    stage('2) PHP Lint (Docker)') {
      steps {
        bat '''
        docker run --rm -v "%CD%":/app -w /app php:8.2-cli ^
          bash -lc "set -e; find . -type f -name '*.php' -print0 | xargs -0 -n1 php -l"
        '''
      }
    }

    stage('3) Code Quality (SonarCloud)') {
      steps {
        withCredentials([string(credentialsId: 'sonar-token', variable: 'SC_TOKEN')]) {
          bat '''
          docker run --rm ^
            -e SONAR_TOKEN=%SC_TOKEN% ^
            -v "%CD%":/usr/src sonarsource/sonar-scanner-cli:5 ^
            sonar-scanner ^
              -Dsonar.host.url=https://sonarcloud.io ^
              -Dsonar.organization=brijesh-palta ^
              -Dsonar.projectKey=janak-travels ^
              -Dsonar.sources=. ^
              -Dsonar.exclusions=**/node_modules/**,**/*.jpg,**/*.jpeg,**/*.png,**/*.gif,**/*.css,**/*.js
          '''
        }
      }
    }

    stage('4) Build Docker Image') {
      steps {
        script {
          bat "docker build -t %IMAGE_NAME%:%IMAGE_TAG% . || exit /b 1"
        }
      }
    }

    stage('5) Security Scan (Trivy)') {
      when { expression { return false } } // enable if trivy is installed
      steps {
        bat 'echo "Add Trivy here later"'
      }
    }

    stage('6) Free Port 8081 (if used)') {
      steps {
        bat 'for /F "tokens=*" %%i in (\'docker ps -q --filter "publish=8081"\') do @docker rm -f %%i'
      }
    }

    stage('7) Deploy Staging (docker compose)') {
      steps {
        bat '''
        docker compose -p janak-staging -f docker-compose.staging.yml down || exit /b 0
        docker compose -p janak-staging -f docker-compose.staging.yml up -d --build || exit /b 1
        '''
      }
    }

    stage('8) Monitoring: Smoke on Staging') {
      steps {
        bat '''
        REM --- Health endpoint check ---
        curl -fsS http://localhost:8081/health.php || exit /b 1

        REM --- Login page availability check ---
        curl -s -o NUL -w "HTTP_CODE=%%{http_code}" "http://localhost:8081/loginpage.php" > status.txt
        find "HTTP_CODE=200" status.txt 1>nul 2>&1
        if errorlevel 1 (
          echo "Login page not available or not returning 200"
          type status.txt
          exit /b 1
        ) else (
          echo "✔ Login page returned 200 OK"
        )
        '''
      }
    }

    stage('9) Push to Registry (main only)') {
      when { branch 'main' }
      steps {
        withCredentials([usernamePassword(credentialsId: 'ghcr', usernameVariable: 'U', passwordVariable: 'P')]) {
          bat '''
          echo %P% | docker login %REG_HOST% -u %U% --password-stdin
          docker tag %IMAGE_NAME%:%IMAGE_TAG% %REGISTRY%/%IMAGE_NAME%:%IMAGE_TAG%
          docker tag %IMAGE_NAME%:%IMAGE_TAG% %REGISTRY%/%IMAGE_NAME%:latest
          docker push %REGISTRY%/%IMAGE_NAME%:%IMAGE_TAG%
          docker push %REGISTRY%/%IMAGE_NAME%:latest
          '''
        }
      }
    }

    stage('10) Deploy Production (main only)') {
      when { branch 'main' }
      steps {
        bat 'docker compose -p janak-prod -f docker-compose.prod.yml up -d || exit /b 1'
      }
    }

    stage('11) Monitoring: Smoke on Prod (main only)') {
      when { branch 'main' }
      steps {
        bat 'curl -fsS http://localhost/health.php || exit /b 1'
      }
    }
  }

  post {
    success { echo "Pipeline SUCCESS — IMAGE_TAG=${env.IMAGE_TAG}" }
    unstable { echo "Pipeline UNSTABLE — check quality/security results" }
    failure { echo "Pipeline FAILED — see first red stage logs" }
  }
}

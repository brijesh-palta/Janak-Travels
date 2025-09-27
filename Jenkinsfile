pipeline {
  agent any

  environment {
    IMAGE_NAME = "janak-travels"
    REGISTRY   = "ghcr.io/brijesh-palta"
    IMAGE_TAG  = "latest"
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
          // Generate short git SHA
          bat '''
          for /f "usebackq tokens=1" %%i in (`git rev-parse --short HEAD`) do @echo %%i > sha.txt
          '''
          def sha = fileExists('sha.txt') ? readFile('sha.txt').trim() : "local"
          if (!env.BRANCH_NAME) { env.BRANCH_NAME = "main" }
          env.IMAGE_TAG = "${env.BRANCH_NAME}-${env.BUILD_NUMBER}-${sha}"
          env.REG_HOST  = (env.REGISTRY.split('/')[0])
          echo "✔ Checkout complete"
          echo "IMAGE_TAG=${env.IMAGE_TAG}"
          echo "REG_HOST=${env.REG_HOST}"
        }
      }
    }

    stage('2) PHP Lint (Build)') {
      steps {
        bat '''
        docker run --rm -v "%CD%":/app -w /app php:8.2-cli ^
          bash -lc "set -e; find . -type f -name '*.php' -print0 | xargs -0 -n1 php -l"
        '''
      }
    }

    stage('3) Unit Tests (PHPUnit Stub)') {
      steps {
        bat '''
        echo "Running unit tests..."
        REM Replace this with phpunit when tests are available
        echo "✔ All placeholder tests passed"
        '''
      }
    }

    stage('4) Code Quality (SonarCloud)') {
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

    stage('5) Security Scan (Trivy)') {
      steps {
        bat '''
        docker run --rm -v /var/run/docker.sock:/var/run/docker.sock aquasec/trivy:latest image %IMAGE_NAME%:latest || exit /b 1
        '''
      }
    }

    stage('6) Build Docker Image') {
      steps {
        bat "docker build -t %IMAGE_NAME%:%IMAGE_TAG% ."
      }
    }

    stage('7) Deploy Staging (docker compose)') {
      steps {
        bat '''
        docker compose -f docker-compose.staging.yml down || exit /b 0
        docker compose -f docker-compose.staging.yml up -d --build
        '''
      }
    }

    stage('8) Smoke on Staging') {
      steps {
        bat '''
        curl -fsS http://localhost:8081/health.php
        curl -I http://localhost:8081/ | find "200" >nul
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
        bat 'docker compose -f docker-compose.prod.yml up -d'
      }
    }

    stage('11) Post-Release Smoke (Prod)') {
      when { branch 'main' }
      steps {
        bat 'curl -fsS http://localhost/health.php'
      }
    }
  }

  post {
    success { echo "Pipeline SUCCESS — IMAGE_TAG=${env.IMAGE_TAG}" }
    unstable { echo "Pipeline UNSTABLE — check SonarCloud or scan results" }
    failure { echo "Pipeline FAILED — review first failing stage logs" }
  }
}

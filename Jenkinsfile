pipeline {
  agent any

  environment {
    IMAGE_NAME         = "janak-travels"
    REGISTRY           = "ghcr.io/brijesh-palta"           
    IMAGE_TAG          = ""
    REG_HOST           = ""                               
    SONAR_HOST_URL     = "https://sonarcloud.io"
    SONAR_ORG          = "brijesh-palta"                   
    SONAR_PROJECT_KEY  = "janak-travels"                   
    SONAR_DASHBOARD    = "https://sonarcloud.io/project/overview?id=janak-travels"
    GITHUB_REPO_URL    = "https://github.com/brijesh-palta/Janak-Travels"
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
          // Windows-safe short SHA
          bat 'for /f "usebackq tokens=1" %i in (`git rev-parse --short HEAD`) do @echo %i>sha.txt'
          def sha = readFile('sha.txt').trim()
          env.IMAGE_TAG = "${env.BRANCH_NAME}-${env.BUILD_NUMBER}-${sha}"
          env.REG_HOST  = (env.REGISTRY.split('/')[0])   // e.g. ghcr.io
          echo "IMAGE_TAG=${env.IMAGE_TAG} | REG_HOST=${env.REG_HOST}"
          echo "Repo: ${env.GITHUB_REPO_URL}"
        }
      }
    }

    stage('2) PHP Lint (via Docker)') {
      steps {
        // Lint all PHP files using official PHP image (no local PHP needed)
        bat '''
        docker run --rm -v "%CD%":/app -w /app php:8.2-cli ^
          bash -lc "set -e; find . -type f -name '*.php' -print0 | xargs -0 -n1 php -l"
        '''
      }
    }

    stage('3) Code Quality (SonarCloud)') {
      steps {
        withCredentials([string(credentialsId: 'sonar-token', variable: 'SC_TOKEN')]) {
          // Dockerized sonar-scanner; no Jenkins plugin required
          bat '''
          docker run --rm ^
            -e SONAR_TOKEN=%SC_TOKEN% ^
            -v "%CD%":/usr/src sonarsource/sonar-scanner-cli:5 ^
            sonar-scanner ^
              -Dsonar.host.url=%SONAR_HOST_URL% ^
              -Dsonar.organization=%SONAR_ORG% ^
              -Dsonar.projectKey=%SONAR_PROJECT_KEY% ^
              -Dsonar.sources=.
          '''
        }
      }
    }

    stage('4) Build Docker Image') {
      steps {
        bat 'docker build -t %IMAGE_NAME%:%IMAGE_TAG% .'
      }
    }

    // Enable later if you install Trivy on Windows host
    stage('5) Container Scan (Trivy)') {
      when { expression { return false } }
      steps { bat 'echo Skipping Trivy on Windows (optional stage)' }
    }

    stage('6) Deploy Staging (docker compose)') {
      steps {
        bat '''
        docker compose -f docker-compose.staging.yml down || exit /b 0
        docker compose -f docker-compose.staging.yml up -d --build
        '''
      }
    }

    stage('7) Smoke on Staging') {
      steps {
        bat '''
        curl -fsS http://localhost:8081/health.php
        curl -I http://localhost:8081/ | find "200" >nul
        '''
      }
    }

    stage('8) Push to Registry (main only)') {
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

    stage('9) Deploy Production (main only)') {
      when { branch 'main' }
      steps {
        // docker-compose.prod.yml should use the image %REGISTRY%/%IMAGE_NAME%:%IMAGE_TAG%
        bat '''
        set IMAGE_TAG=%IMAGE_TAG%
        docker compose -f docker-compose.prod.yml up -d
        '''
      }
    }

    stage('10) Post-Release Smoke (Prod)') {
      when { branch 'main' }
      steps {
        bat 'curl -fsS http://localhost/health.php'
      }
    }
  }

  post {
    success {
      echo "All 10 stages OK | Image: %REGISTRY%/%IMAGE_NAME%:%IMAGE_TAG%"
      echo "GitHub: ${env.GITHUB_REPO_URL}"
      echo "SonarCloud: ${env.SONAR_DASHBOARD}"
    }
    failure { echo "Pipeline failed — check first red stage logs." }
  }
}

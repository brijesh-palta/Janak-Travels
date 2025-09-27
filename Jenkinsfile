  pipeline {
    agent any
  
    environment {
      IMAGE_NAME = "janak-travels"
      REGISTRY   = "ghcr.io/brijesh-palta"   
      IMAGE_TAG  = "latest"                  // default fallback
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
            // Try to get short SHA
            bat '''
            for /f "usebackq tokens=1" %%i in (`git rev-parse --short HEAD`) do @echo %%i > sha.txt
            '''
            def sha = ""
            if (fileExists('sha.txt')) {
              sha = readFile('sha.txt').trim()
            }
            if (!sha) { sha = "local" }
  
            if (!env.BRANCH_NAME) {
              env.BRANCH_NAME = "main"
            }
  
            env.IMAGE_TAG = "${env.BRANCH_NAME}-${env.BUILD_NUMBER}-${sha}"
            env.REG_HOST  = (env.REGISTRY.split('/')[0])
  
            echo "✔ Checkout done"
            echo "IMAGE_TAG=${env.IMAGE_TAG}"
            echo "REG_HOST=${env.REG_HOST}"
          }
        }
      }
  
      stage('2) PHP Lint (via Docker)') {
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
                -Dsonar.sources=.
            '''
          }
        }
      }
  
      stage('4) Build Docker Image') {
        steps {
          script {
            def tag = env.IMAGE_TAG ?: "latest"
            bat "docker build -t %IMAGE_NAME%:${tag} ."
          }
        }
      }
  
      stage('5) Container Scan (Trivy)') {
        when { expression { return false } } // Enable if you add Trivy
        steps {
          bat 'echo "Add Trivy here later"'
        }
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
          bat '''
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
      success { echo "Pipeline SUCCESS — IMAGE_TAG=${env.IMAGE_TAG}" }
      unstable { echo "Pipeline UNSTABLE — check SonarCloud/optional scan" }
      failure { echo "Pipeline FAILED — check logs from red stage" }
    }
  }

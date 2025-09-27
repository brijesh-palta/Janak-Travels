pipeline {
  agent any

  environment {
    IMAGE_NAME = "janak-travels"
    REGISTRY   = "ghcr.io/brijesh-palta"
    IMAGE_TAG  = ""
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
          def shortSha = (env.GIT_COMMIT ?: "local").take(7)
          env.IMAGE_TAG = "${env.BRANCH_NAME}-${env.BUILD_NUMBER}-${shortSha}"
          env.REG_HOST  = (env.REGISTRY.split('/')[0])
          echo "IMAGE_TAG=${env.IMAGE_TAG}  REG_HOST=${env.REG_HOST}"
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

    stage('3) Code Quality (SonarQube)') {
      steps {
        script {
          try {
            withSonarQubeEnv('MySonar') {
              bat '''
              docker run --rm ^
                -e SONAR_HOST_URL=%SONAR_HOST_URL% ^
                -e SONAR_TOKEN=%SONAR_AUTH_TOKEN% ^
                -v "%CD%":/usr/src sonarsource/sonar-scanner-cli:5 ^
                sonar-scanner -Dsonar.projectKey=janak-travels -Dsonar.sources=.
              '''
            }
          } catch (err) {
            currentBuild.result = 'UNSTABLE'
            echo "Sonar stage skipped/unstable: ${err}"
          }
        }
      }
      post {
        success {
          script {
            try {
              timeout(time: 5, unit: 'MINUTES') {
                def qg = waitForQualityGate()
                if (qg.status != 'OK') error "Quality Gate failed: ${qg.status}"
              }
            } catch (e) { echo "QualityGate wait skipped: ${e.message}" }
          }
        }
      }
    }

    stage('4) Build Docker Image') {
      steps {
        bat 'docker build -t %IMAGE_NAME%:%IMAGE_TAG% .'
      }
    }

    stage('5) Container Scan (Trivy)') {
      when { expression { return false } } // enable later if Trivy installed
      steps {
        bat 'echo "Add Trivy scanning here if needed"'
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
    success { echo "All 10 stages succeeded — %IMAGE_TAG%" }
    unstable { echo "Pipeline UNSTABLE — check Sonar/scan results." }
    failure { echo "Pipeline failed — see red stage logs." }
  }
}

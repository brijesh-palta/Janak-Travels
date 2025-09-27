pipeline {
  agent any

  environment {
    IMAGE_NAME = "janak-travels"
    REGISTRY   = "ghcr.io/brijesh-palta"
    IMAGE_TAG  = ""                      // set in Stage 1
  }

  options {
    ansiColor('xterm')
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
          def sha = sh(returnStdout: true, script: 'git rev-parse --short HEAD').trim()
          env.IMAGE_TAG = "${env.BRANCH_NAME}-${env.BUILD_NUMBER}-${sha}"
          echo "Computed IMAGE_TAG: ${env.IMAGE_TAG}"
        }
      }
    }

    stage('2) PHP Lint') {
      steps {
        sh '''
          set -e
          find . -type f -name "*.php" -print0 | xargs -0 -n1 php -l
        '''
      }
    }

    stage('3) Code Quality (SonarCloud)') {
      steps {
        withSonarQubeEnv('MySonar') {
          sh '''
            sonar-scanner \
              -Dsonar.projectKey=janak-travels \
              -Dsonar.sources=. \
              -Dsonar.exclusions=**/node_modules/**,**/*.jpg,**/*.jpeg,**/*.png,**/*.gif,**/*.css,**/*.js
          '''
        }
      }
      post {
        success {
          script {
            timeout(time: 5, unit: 'MINUTES') {
              def qg = waitForQualityGate()
              if (qg.status != 'OK') error "Quality Gate failed: ${qg.status}"
            }
          }
        }
      }
    }

    stage('4) Build Docker Image') {
      steps {
        sh 'docker build -t ${IMAGE_NAME}:${IMAGE_TAG} .'
      }
    }

    stage('5) Container Scan (Trivy)') {
      steps {
        sh '''
          which trivy || (apk add --no-cache curl && \
            curl -sSfL https://raw.githubusercontent.com/aquasecurity/trivy/main/contrib/install.sh \
              | sh -s -- -b /usr/local/bin)
          trivy image --severity HIGH,CRITICAL --exit-code 1 ${IMAGE_NAME}:${IMAGE_TAG} | tee trivy.txt
        '''
      }
      post { always { archiveArtifacts artifacts: 'trivy.txt', fingerprint: true } }
    }

    stage('6) Deploy Staging (Compose)') {
      steps {
        sh '''
          docker compose -f docker-compose.staging.yml down || true
          docker compose -f docker-compose.staging.yml up -d --build
          sleep 5
        '''
      }
    }

    stage('7) Smoke on Staging') {
      steps {
        sh '''
          set -e
          curl -fsS http://localhost:8081/health.php | grep '"ok":true'
          # optional: homepage HTTP status
          curl -I http://localhost:8081/ | head -n 1
        '''
      }
    }

    stage('8) Push to Registry') {
      when { branch 'main' }
      steps {
        withCredentials([usernamePassword(credentialsId: 'ghcr', usernameVariable: 'U', passwordVariable: 'P')]) {
          sh '''
            echo $P | docker login ${REGISTRY.split('/')[0]} -u $U --password-stdin || true
            docker tag ${IMAGE_NAME}:${IMAGE_TAG} ${REGISTRY}/${IMAGE_NAME}:${IMAGE_TAG}
            docker tag ${IMAGE_NAME}:${IMAGE_TAG} ${REGISTRY}/${IMAGE_NAME}:latest
            docker push ${REGISTRY}/${IMAGE_NAME}:${IMAGE_TAG}
            docker push ${REGISTRY}/${IMAGE_NAME}:latest
          '''
        }
      }
    }

    stage('9) Deploy Production (Compose)') {
      when { branch 'main' }
      steps {
        sh '''
          IMAGE_TAG=${IMAGE_TAG} docker compose -f docker-compose.prod.yml up -d
          sleep 5
        '''
      }
    }

    stage('10) Post-Release Smoke (Prod)') {
      when { branch 'main' }
      steps {
        sh '''
          set -e
          curl -fsS http://localhost/health.php | grep '"ok":true'
        '''
      }
    }
  }

  post {
    success { echo "All 10 stages ${env.IMAGE_TAG}" }
    failure { echo "Pipeline failed — check the gated stage output." }
  }
}

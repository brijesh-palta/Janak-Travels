pipeline {
  agent any

  options {
    timestamps()
    ansiColor('xterm')
  }

  environment {
    // image + registry
    REG_HOST   = 'ghcr.io'
    GH_USER    = 'brijesh-palta'                       // <-- apna GitHub username
    IMAGE_NAME = 'janak-travels'
    IMAGE_REPO = "${REG_HOST}/${GH_USER}/${IMAGE_NAME}"
    // sonar
    SONAR_ORG  = 'brijesh-palta'
    SONAR_HOST = 'https://sonarcloud.io'
    SONAR_KEY  = 'janak-travels'
  }

  parameters {
    booleanParam(name: 'RUN_TRIVY',        defaultValue: false, description: 'Run Trivy image scan (needs trivy installed on agent)')
    booleanParam(name: 'PUSH_TO_REGISTRY', defaultValue: true,  description: 'Push image to GHCR on main')
    booleanParam(name: 'DEPLOY_PROD',      defaultValue: true,  description: 'Deploy to Production on main')
  }

  stages {
    stage('1) Checkout & Version') {
      steps {
        checkout([$class: 'GitSCM',
          branches: [[name: '*/main']],
          userRemoteConfigs: [[
            url: 'https://github.com/brijesh-palta/Janak-Travels.git',
            credentialsId: 'github-credentials'
          ]]
        ])
        script {
          bat '''for /F "usebackq tokens=1" %i in (`git rev-parse --short HEAD`) do @echo %i  1>sha.txt'''
          def shortSha = readFile('sha.txt').trim()
          env.GIT_SHA   = shortSha
          env.IMAGE_TAG = (env.BRANCH_NAME == 'main') ? "main-${shortSha}" : "latest"
          echo "✔ Checkout done"
          echo "IMAGE_TAG=${env.IMAGE_TAG}"
          echo "REG_HOST=${env.REG_HOST}"
        }
      }
    }

    stage('2) PHP Lint (Docker)') {
      steps {
        bat '''
          docker run --rm -v "%cd%":/app -w /app php:8.2-cli ^
            bash -lc "set -e; find . -type f -name '*.php' -print0 | xargs -0 -n1 php -l"
        '''
      }
    }

    stage('3) Code Quality (SonarCloud)') {
      environment {
        SC_TOKEN = credentials('sonar-token') // <-- Jenkins me Sonar Cloud token id = sonar-token
      }
      steps {
        bat """
          docker run --rm -e SONAR_TOKEN=%SC_TOKEN% ^
            -v "%cd%":/usr/src sonarsource/sonar-scanner-cli:5 ^
            sonar-scanner ^
              -Dsonar.host.url=${SONAR_HOST} ^
              -Dsonar.organization=${SONAR_ORG} ^
              -Dsonar.projectKey=${SONAR_KEY} ^
              -Dsonar.sources=. ^
              -Dsonar.exclusions=**/node_modules/**,**/*.jpg,**/*.jpeg,**/*.png,**/*.gif,**/*.css,**/*.js
        """
      }
    }

    stage('4) Build Docker Image') {
      steps {
        bat "docker build -t ${IMAGE_NAME}:${env.IMAGE_TAG} .   || exit /b 1"
      }
    }

    stage('5) Security Scan (Trivy)') {
      when { expression { return params.RUN_TRIVY } }
      steps {
        // Trivy must be installed on agent OR use aquasec/trivy image with docker.sock mount
        bat "trivy image --quiet --severity HIGH,CRITICAL --exit-code 0 ${IMAGE_NAME}:${env.IMAGE_TAG} || exit /b 0"
      }
    }

    stage('6) Free Port 8081 (if used)') {
      steps {
        // kill any container publishing 8081 (Windows-friendly)
        bat 'for /F "tokens=*" %i in (\'docker ps -q --filter "publish=8081"\') do @docker rm -f %i'
      }
    }

    stage('7) Deploy Staging (docker compose)') {
      steps {
        bat 'docker compose -p janak-staging -f docker-compose.staging.yml down   || exit /b 0'
        bat 'docker compose -p janak-staging -f docker-compose.staging.yml up -d --build   || exit /b 1'
      }
    }

    stage('8) Monitoring: Smoke on Staging') {
      steps {
        bat '''
          REM --- Health endpoint check ---
          curl -fsS http://localhost:8081/health.php   || exit /b 1

          REM --- Login page availability check ---
          curl -s -o NUL -w "HTTP_CODE=%%{http_code}" "http://localhost:8081/loginpage.php"  1>status.txt
          find "HTTP_CODE=200" status.txt  1>nul 2>&1
          if errorlevel 1 (
            echo "Login page not available or not returning 200"
            type status.txt
            exit /b 1
          ) else (
            echo "✓ Login page returned 200 OK"
          )
        '''
      }
    }

    stage('9) Push to Registry (main only)') {
      when {
        allOf {
          branch 'main'
          expression { return params.PUSH_TO_REGISTRY }
        }
      }
      steps {
        withCredentials([usernamePassword(credentialsId: 'github-credentials', usernameVariable: 'GH_USER_V', passwordVariable: 'GH_PAT_V')]) {
          script {
            // tag & push both "sha" and "latest" for convenience
            bat """
              docker login ${REG_HOST} -u %GH_USER_V% -p %GH_PAT_V%
              docker tag ${IMAGE_NAME}:${env.IMAGE_TAG} ${IMAGE_REPO}:${env.IMAGE_TAG}
              docker tag ${IMAGE_NAME}:${env.IMAGE_TAG} ${IMAGE_REPO}:latest
              docker push ${IMAGE_REPO}:${env.IMAGE_TAG}
              docker push ${IMAGE_REPO}:latest
            """
          }
        }
      }
    }

    stage('10) Deploy Production (main only)') {
      when {
        allOf {
          branch 'main'
          expression { return params.DEPLOY_PROD }
        }
      }
      steps {
        bat 'docker compose -p janak-prod -f docker-compose.prod.yml pull  || exit /b 0'
        bat 'docker compose -p janak-prod -f docker-compose.prod.yml up -d --build  || exit /b 1'
      }
    }

    stage('11) Monitoring: Smoke on Prod (main only)') {
      when {
        allOf { branch 'main'; expression { return params.DEPLOY_PROD } }
      }
      steps {
        // Change URL/host if prod is remote or different port
        bat '''
          curl -fsS http://localhost:8080/health.php   || exit /b 1
          curl -s -o NUL -w "HTTP_CODE=%%{http_code}" "http://localhost:8080/loginpage.php"  1>status_prod.txt
          find "HTTP_CODE=200" status_prod.txt  1>nul 2>&1
          if errorlevel 1 (
            echo "Prod login page not 200"
            type status_prod.txt
            exit /b 1
          ) else (
            echo "✓ Prod login OK"
          )
        '''
      }
    }
  }

  post {
    success {
      echo 'Pipeline SUCCESS ✅'
    }
    failure {
      echo 'Pipeline FAILED — see first red stage logs'
    }
  }
}

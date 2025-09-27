/*******************************************************
 * Janak Travels – CI/CD Pipeline
 * 
 * Description:
 * This Jenkinsfile defines an end-to-end CI/CD workflow
 * for a PHP application containerized with Docker. It
 * includes source checkout, code validation, static
 * analysis with SonarCloud, security scanning, staging
 * deployment, smoke testing, container registry push,
 * production deployment, and monitoring.
 *
 * Pre-requisites:
 * - Jenkins running with Docker installed and accessible.
 * - Docker Desktop must be running on the agent machine.
 * - SonarCloud account with project key configured.
 * - Jenkins credentials created:
 *   • github-credentials (for GitHub SCM)
 *   • sonarcloud-token (SonarCloud access token)
 *   • ghcr (GitHub Container Registry token, if pushing)
 *******************************************************/

pipeline {
  agent any

  options {
    ansiColor('xterm')                         // Adds colored output in Jenkins logs
    timestamps()                               // Prepends each log line with a timestamp
    buildDiscarder(logRotator(numToKeepStr: '15')) // Retain only last 15 builds
    disableConcurrentBuilds()                  // Prevent parallel runs of this pipeline
  }

  environment {
    // Application settings
    APP_NAME          = 'janak-travels'
    BRANCH_NAME_SAFE  = "${env.BRANCH_NAME ?: 'main'}"

    // Docker images and tags
    IMAGE_LOCAL_TAG   = "${APP_NAME}:latest"
    IMAGE_STAGING_TAG = "${APP_NAME}:staging"
    IMAGE_PROD_TAG    = "${APP_NAME}:prod"

    // Staging deployment configuration
    STAGING_COMPOSE   = 'docker-compose.staging.yml'
    STAGING_PROJECT   = 'janak-staging'
    STAGING_HTTP_PORT = '8081'
    STAGING_URL       = "http://localhost:${STAGING_HTTP_PORT}"

    // Registry configuration (set REG_PUSH=true to enable pushing)
    REG_PUSH          = 'false'
    REG_HOST          = 'ghcr.io'
    REG_NAMESPACE     = 'brijesh-palta'
    REG_CREDENTIALS_ID= 'ghcr'
    REG_IMAGE         = "${REG_HOST}/${REG_NAMESPACE}/${APP_NAME}"

    // SonarCloud configuration
    SONAR_HOST_URL    = 'https://sonarcloud.io'
    SONAR_ORG         = 'brijesh-palta'
    SONAR_PROJECTKEY  = 'janak-travels'

    // Security scanning (set TRIVY_ENABLED=true to enable)
    TRIVY_ENABLED     = 'false'
    TRIVY_IMAGE       = 'aquasec/trivy:latest'
  }

  stages {

    /********************
     * 1) Source Checkout
     * Purpose: Retrieve source code from GitHub SCM.
     * Also records short commit hash for traceability.
     ********************/
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

    /********************
     * 2) Code Validation
     * Purpose: Validate PHP syntax using php:8.2-cli.
     * This acts as a lightweight unit test to catch
     * syntax errors before progressing.
     ********************/
    stage('2) PHP Lint (via Docker)') {
      steps {
        bat 'docker version' // Fail fast if Docker is not running
        bat """
        docker run --rm -v "%WORKSPACE%":/app -w /app php:8.2-cli ^
          bash -lc "set -e; find . -type f -name '*.php' -print0 | xargs -0 -n1 php -l"
        """
      }
    }

    /********************
     * 3) Static Code Analysis
     * Purpose: Run SonarCloud scanner to identify code
     * quality issues, vulnerabilities, and maintainability
     * metrics.
     ********************/
    stage('3) Code Quality (SonarCloud)') {
      steps {
        withCredentials([string(credentialsId: 'sonar-token', variable: 'SC_TOKEN')]) {
          bat """
          docker run --rm -e SONAR_TOKEN=%SONAR_TOKEN% ^
            -v "%WORKSPACE%":/usr/src sonarsource/sonar-scanner-cli:5 ^
            sonar-scanner ^
              -Dsonar.host.url=${SONAR_HOST_URL} ^
              -Dsonar.organization=${SONAR_ORG} ^
              -Dsonar.projectKey=${SONAR_PROJECTKEY} ^
              -Dsonar.sources=. ^
              -Dsonar.exclusions=**/node_modules/**,**/*.jpg,**/*.png,**/*.css,**/*.js
          """
        }
      }
    }

    /********************
     * 4) Docker Image Build
     * Purpose: Build container image using Dockerfile.
     * This creates the runtime artifact for deployment.
     ********************/
    stage('4) Build Docker Image') {
      steps {
        bat "docker build -t ${IMAGE_LOCAL_TAG} ."
      }
    }

    /********************
     * 5) Security Scan (Optional)
     * Purpose: Run Trivy against the built image to
     * identify known vulnerabilities.
     ********************/
    stage('5) Security Scan (Trivy)') {
      when {
        expression { env.TRIVY_ENABLED == 'true' }
      }
      steps {
        bat """
        docker run --rm ^
          -v /var/run/docker.sock:/var/run/docker.sock ^
          -v "%USERPROFILE%/.cache/trivy":/root/.cache/ ^
          ${TRIVY_IMAGE} image ${IMAGE_LOCAL_TAG}
        """
      }
    }

    /********************
     * 6) Port Cleanup
     * Purpose: Ensure staging port is free by removing
     * any container already bound to that port.
     ********************/
    stage('6) Free Port (if used)') {
      steps {
        bat """
        for /F "tokens=*" %%i in ('docker ps -q --filter "publish=${STAGING_HTTP_PORT}"') do @docker rm -f %%i
        """
      }
    }

    /********************
     * 7) Staging Deployment
     * Purpose: Deploy the application in staging
     * environment using docker-compose.
     ********************/
    stage('7) Deploy Staging') {
      steps {
        bat """
        docker compose -p ${STAGING_PROJECT} -f ${STAGING_COMPOSE} down || exit /b 0
        docker compose -p ${STAGING_PROJECT} -f ${STAGING_COMPOSE} up -d --build
        """
      }
    }

    /********************
     * 8) Smoke Testing (Staging)
     * Purpose: Validate application responsiveness on
     * staging by checking health.php and login page.
     ********************/
    stage('8) Smoke Test (Staging)') {
      steps {
        bat "curl -fsS ${STAGING_URL}/health.php"
        bat """
        curl -s -o NUL -w "HTTP_CODE=%%{http_code}" "${STAGING_URL}/loginpage.php" > status.txt
        find "HTTP_CODE=200" status.txt >nul || (exit /b 1)
        """
      }
    }

    /********************
     * 9) Registry Push (Optional, main branch only)
     * Purpose: Push built Docker image to GitHub Container
     * Registry for long-term storage and distribution.
     ********************/
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

    /********************
     * 10) Production Deployment (Optional)
     * Purpose: Placeholder stage for production deployment
     * using docker-compose, Kubernetes, or other platforms.
     ********************/
    stage('10) Deploy Production') {
      when {
        branch 'main'
      }
      steps {
        echo "Production deployment placeholder. Replace with real production deployment logic."
      }
    }

    /********************
     * 11) Production Monitoring (Optional)
     * Purpose: Placeholder for health checks against
     * production endpoints after deployment.
     ********************/
    stage('11) Monitoring: Production') {
      when {
        branch 'main'
      }
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
      bat 'docker ps --format "table {{.ID}}\t{{.Names}}\t{{.Status}}\t{{.Ports}}"'
    }
  }
}

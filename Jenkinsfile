pipeline {
    agent any

    environment {
        // Docker image name and registry configuration
        IMAGE_NAME = "janak-travels"
        REGISTRY   = "ghcr.io/brijesh-palta"   // GitHub Container Registry namespace
        IMAGE_TAG  = "latest"                  // default fallback tag
        REG_HOST   = ""                        // extracted from REGISTRY
    }

    options {
        // Pipeline behavior settings
        timestamps()                           // show timestamps in logs
        skipDefaultCheckout(true)              // manual checkout stage instead of default
        buildDiscarder(logRotator(numToKeepStr: '30')) // keep only last 30 builds
        ansiColor('xterm')                     // colored logs
    }

    triggers {
        // Poll GitHub repo every 5 minutes
        pollSCM('H/5 * * * *')
    }

    stages {

        stage('1) Checkout & Version') {
            steps {
                checkout scm
                script {
                    // Extract short git commit SHA and store in sha.txt
                    bat '''
                        for /F "usebackq tokens=1" %%i in (`git rev-parse --short HEAD`) do @echo %%i > sha.txt
                    '''
                    def sha = fileExists('sha.txt') ? readFile('sha.txt').trim() : "local"

                    // Ensure branch name fallback
                    if (!env.BRANCH_NAME) { env.BRANCH_NAME = "main" }

                    // Construct image tag and registry host
                    env.IMAGE_TAG = "${env.BRANCH_NAME}-${env.BUILD_NUMBER}-${sha}"
                    env.REG_HOST  = (env.REGISTRY.split('/')[0])

                    echo "✔ Checkout complete"
                    echo "IMAGE_TAG=${env.IMAGE_TAG}"
                    echo "REG_HOST=${env.REG_HOST}"
                }
            }
        }

        stage('2) PHP Lint (Docker)') {
            steps {
                // Run PHP lint inside official php:8.2-cli container
                bat '''
                docker run --rm -v "%CD%":/app -w /app php:8.2-cli ^
                  bash -lc "set -e; find . -type f -name '*.php' -print0 | xargs -0 -n1 php -l"
                '''
            }
        }

        stage('3) Code Quality (SonarCloud)') {
            steps {
                // Requires Jenkins credential: sonar-token
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

        stage('5) Security Scan (Trivy)') {
            when { expression { return false } } // enable later if Trivy installed
            steps {
                bat 'echo "Trivy scan placeholder"'
            }
        }

        stage('6) Free Port 8081 (if used)') {
            steps {
                // Ensure port 8081 is free before deploying staging
                bat '''
                for /f "tokens=5" %%p in ('netstat -ano ^| findstr :8081') do taskkill /F /PID %%p || exit /b 0
                '''
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

        stage('8) Monitoring: Smoke on Staging') {
            steps {
                // Check health endpoint and root page
                bat '''
                curl -fsS http://localhost:8081/health.php
                curl -I http://localhost:8081/ | find "200" >nul
                '''
            }
        }

        stage('9) Push to Registry (main only)') {
            when { branch 'main' }
            steps {
                // Requires Jenkins credential: ghcr
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
                bat '''
                docker compose -f docker-compose.prod.yml up -d
                '''
            }
        }

        stage('11) Monitoring: Smoke on Prod (main only)') {
            when { branch 'main' }
            steps {
                bat 'curl -fsS http://localhost/health.php'
            }
        }
    }

    post {
        success {
            echo "Pipeline SUCCESS — IMAGE_TAG=${env.IMAGE_TAG}"
        }
        unstable {
            echo "Pipeline UNSTABLE — check SonarCloud or Trivy stage"
        }
        failure {
            echo "Pipeline FAILED — check logs from first red stage"
        }
    }
}

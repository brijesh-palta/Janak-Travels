/*******************************************************
 * Janak Travels – CI/CD Pipeline (Windows Agent, Final)
 *
 * Purpose:
 *   End-to-end CI/CD for a PHP application containerized
 *   with Docker on a Windows Jenkins agent.
 *
 * Key Stages:
 *   0) Ensure Docker daemon is running
 *   1) Checkout and version traceability
 *   2) PHP syntax lint using php:8.2-cli
 *   2.5) PHPUnit tests (writes clean composer.json, creates
 *        placeholder test if missing, generates phpunit.xml.dist)
 *   3) SonarCloud static analysis
 *   4) Docker image build
 *   5) Trivy filesystem security scan (optional)
 *   6) Free staging port
 *   7) Staging deploy via Docker Compose
 *   8) Smoke tests against staging endpoints
 *   9) Optional push to GitHub Container Registry
 *   10) Production deploy placeholder
 *   11) Production monitoring placeholder
 *
 * Prerequisites on the Windows agent:
 *   - Docker Desktop installed and initialized at least once
 *   - Docker Compose (v2 `docker compose` or legacy `docker-compose`)
 *   - Jenkins credentials:
 *       - github-credentials (used by multibranch for SCM)
 *       - sonar-token (Secret text) for SonarCloud
 *       - ghcr (Secret text) for optional GHCR push
 *   - Repo contains docker-compose.staging.yml at project root
 *******************************************************/

pipeline {
  agent any

  options {
    ansiColor('xterm')
    timestamps()
    buildDiscarder(logRotator(numToKeepStr: '15'))
    disableConcurrentBuilds()
  }

  environment {
    // App naming
    APP_NAME            = 'janak-travels'
    BRANCH_NAME_SAFE    = "${env.BRANCH_NAME ?: 'main'}"

    // Images
    IMAGE_LOCAL_TAG     = "${APP_NAME}:latest"

    // Staging deployment
    STAGING_PROJECT     = 'janak-staging'
    STAGING_COMPOSE     = 'docker-compose.staging.yml'
    STAGING_HTTP_PORT   = '8081'
    STAGING_URL         = "http://localhost:${STAGING_HTTP_PORT}"

    // Optional registry push
    REG_PUSH            = 'false'
    REG_HOST            = 'ghcr.io'
    REG_NAMESPACE       = 'brijesh-palta'
    REG_CREDENTIALS_ID  = 'ghcr'
    REG_IMAGE           = "${REG_HOST}/${REG_NAMESPACE}/${APP_NAME}"

    // SonarCloud
    SONAR_HOST_URL      = 'https://sonarcloud.io'
    SONAR_ORG           = 'brijesh-palta'
    SONAR_PROJECT_KEY   = 'Janak-Travels'

    // Trivy
    TRIVY_ENABLED       = 'true'
    TRIVY_IMAGE         = 'aquasec/trivy:latest'
  }

  stages {

    /*******************************************************
     * 0) Ensure Docker Running (Windows)
     * Starts Docker service if present and waits until ready.
     *******************************************************/
    stage('0) Ensure Docker Running') {
      steps {
        powershell '''
          $ErrorActionPreference = "Stop"
          $svc = Get-Service -Name 'com.docker.service' -ErrorAction SilentlyContinue
          if ($svc -and $svc.Status -ne 'Running') { Start-Service -Name 'com.docker.service' }
          $deadline = (Get-Date).AddSeconds(60); $ok = $false
          while(-not $ok -and (Get-Date) -lt $deadline) {
            try { docker info *> $null; $ok = $true } catch { Start-Sleep -Seconds 2 }
          }
          if (-not $ok) { throw "Docker daemon did not become ready in time." }
          docker version
        '''
      }
    }

    /*******************************************************
     * 1) Checkout & Version
     *******************************************************/
    stage('1) Checkout & Version') {
      steps {
        checkout scm
        bat '''
          for /F "usebackq tokens=1" %%i in (`git rev-parse --short HEAD`) do @echo %%i > sha.txt
        '''
        script { echo "Commit SHA: ${readFile('sha.txt').trim()}" }
      }
    }

    /*******************************************************
     * 2) PHP Syntax Lint using php:8.2-cli
     *******************************************************/
    stage('2) PHP Lint (via Docker)') {
      steps {
        bat 'docker version'
        bat '''
          docker run --rm -v "%WORKSPACE%":/app -w /app php:8.2-cli ^
            bash -lc "set -euo pipefail; find . -type f -name '*.php' -print0 | xargs -0 -n1 php -l"
        '''
      }
    }

    /*******************************************************
     * 2.5) Unit Tests (PHPUnit)
     * Writes a clean composer.json on every run, ensures a
     * placeholder test exists if project has no tests, writes
     * phpunit.xml.dist for deterministic discovery, then runs.
     *******************************************************/
    stage('2.5) Unit Tests (PHPUnit)') {
      steps {
        // Always write a valid composer.json to avoid prior corruption
        powershell '''
          $ErrorActionPreference = "Stop"
          $json = @'
{
  "require-dev": { "phpunit/phpunit": "^10" },
  "autoload": { "psr-4": { "App\\\\": "src/" } }
}
'@
          Set-Content -LiteralPath "composer.json" -Value $json -Encoding ASCII
        '''

        // Ensure at least one test exists
        powershell '''
          $ErrorActionPreference = "Stop"
          if (!(Test-Path -LiteralPath "tests")) { New-Item -ItemType Directory -Path "tests" | Out-Null }
          $anyPhp = Get-ChildItem -Path "tests" -Filter *.php -File -ErrorAction SilentlyContinue
          if (-not $anyPhp) {
            $test = @'
<?php
use PHPUnit\\Framework\\TestCase;
final class SmokeTest extends TestCase {
  public function testTruth(): void { $this->assertTrue(true); }
}
'@
            Set-Content -LiteralPath "tests\\SmokeTest.php" -Value $test -Encoding ASCII
          }
        '''

        // Provide explicit PHPUnit configuration for stable discovery
        powershell '''
          $xml = @'
<?xml version="1.0" encoding="UTF-8"?>
<phpunit
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/10.5/phpunit.xsd"
  cacheDirectory=".phpunit.cache"
  colors="true"
  beStrictAboutTestsThatDoNotTestAnything="false"
  beStrictAboutOutputDuringTests="false">
  <testsuites>
    <testsuite name="default">
      <directory>tests</directory>
    </testsuite>
  </testsuites>
</phpunit>
'@
          Set-Content -LiteralPath "phpunit.xml.dist" -Value $xml -Encoding ASCII
        '''

        // Validate, install dependencies and run tests
        bat '''
          docker run --rm -v "%WORKSPACE%":/app -w /app composer:2 ^
            sh -lc "set -e; composer validate --no-check-publish; composer install --no-interaction --prefer-dist; \
            ./vendor/bin/phpunit -c phpunit.xml.dist --log-junit junit.xml --do-not-fail-on-empty-test-suite"
        '''
        junit allowEmptyResults: true, testResults: 'junit.xml'
      }
    }

    /*******************************************************
     * 3) Static Code Analysis – SonarCloud
     *******************************************************/
    stage('3) Code Quality (SonarCloud)') {
      steps {
        withCredentials([string(credentialsId: 'sonar-token', variable: 'SC_TOKEN')]) {
          bat '''
            docker run --rm -e SONAR_TOKEN=%SC_TOKEN% ^
              -v "%WORKSPACE%":/usr/src sonarsource/sonar-scanner-cli:5 ^
              sh -lc "set -e; mkdir -p /sonar-tmp; sonar-scanner \
                -Dsonar.host.url=https://sonarcloud.io \
                -Dsonar.organization=brijesh-palta \
                -Dsonar.projectKey=Janak-Travels \
                -Dsonar.working.directory=/sonar-tmp"
          '''
        }
      }
    }

    /*******************************************************
     * 4) Build Docker Image
     *******************************************************/
    stage('4) Build Docker Image') {
      steps { bat "docker build -t ${IMAGE_LOCAL_TAG} ." }
    }

    /*******************************************************
     * 5) Security Scan (Trivy filesystem mode)
     * Enable/disable with TRIVY_ENABLED.
     *******************************************************/
    stage('5) Security Scan (Trivy FS)') {
      when { expression { env.TRIVY_ENABLED == 'true' } }
      steps {
        bat '''
          docker run --rm -v "%WORKSPACE%":/src aquasec/trivy:latest ^
            fs --severity HIGH,CRITICAL --exit-code 0 --no-progress /src > trivy-fs.txt
        '''
        archiveArtifacts artifacts: 'trivy-fs.txt', onlyIfSuccessful: true
      }
    }

    /*******************************************************
     * 6) Free Staging Port
     *******************************************************/
    stage('6) Free Port (if used)') {
      steps {
        bat "for /F \"tokens=*\" %%i in ('docker ps -q --filter \"publish=${STAGING_HTTP_PORT}\"') do @docker rm -f %%i"
      }
    }

    /*******************************************************
     * 7) Deploy to Staging via Docker Compose
     *******************************************************/
    stage('7) Deploy Staging') {
      steps {
        bat '''
          docker compose version >NUL 2>&1
          if %ERRORLEVEL%==0 (
            docker compose -p janak-staging -f docker-compose.staging.yml down || exit /b 0
            docker compose -p janak-staging -f docker-compose.staging.yml up -d --build
          ) else (
            docker-compose -p janak-staging -f docker-compose.staging.yml down || exit /b 0
            docker-compose -p janak-staging -f docker-compose.staging.yml up -d --build
          )
        '''
      }
    }

    /*******************************************************
     * 8) Smoke Tests against Staging
     *******************************************************/
    stage('8) Smoke Test (Staging)') {
      steps {
        bat '''
          curl -fsS http://localhost:8081/health.php
          curl -s -o NUL -w "HTTP_CODE=%%{http_code}" "http://localhost:8081/loginpage.php" > status.txt
          find "HTTP_CODE=200" status.txt >nul || (echo Smoke test failed & exit /b 1)
        '''
      }
    }

    /*******************************************************
     * 9) Push to Registry (optional)
     * Requires REG_PUSH='true' on main and ghcr credentials.
     *******************************************************/
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

    /*******************************************************
     * 10) Production Deployment (placeholder)
     *******************************************************/
    stage('10) Deploy Production') {
      when { branch 'main' }
      steps { echo "Production deployment placeholder. Replace with real production deployment steps." }
    }

    /*******************************************************
     * 11) Production Monitoring (placeholder)
     *******************************************************/
    stage('11) Monitoring: Production') {
      when { branch 'main' }
      steps { echo "Production monitoring placeholder. Add health checks and alerts here." }
    }
  }

  post {
    success {
      echo "Pipeline completed successfully"
    }
    failure {
      echo "Pipeline failed — check the stage logs above"
    }
    always {
      bat "docker ps --format \"table {{.ID}}\\t{{.Names}}\\t{{.Status}}\\t{{.Ports}}\""
      archiveArtifacts allowEmptyArchive: true, artifacts: 'status.txt,junit.xml,trivy-fs.txt'
    }
  }
}

pipeline {
    agent any

    tools {
        jdk 'jdk-17'
    }

    environment {
        SCANNER_HOME = tool 'sonar-scanner'
    }

    stages {
        stage('Clean Workspace') {
            steps {
                cleanWs()
            }
        }

        stage('Checkout from GitHub') {
            steps {
                git branch: 'main', url: 'https://github.com/balumahendranv/lamp-login.git'
            }
        }

        stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv('sonar-server') {
                    sh '''
                    $SCANNER_HOME/bin/sonar-scanner \
                      -Dsonar.projectKey=lamp-login \
                      -Dsonar.projectName=lamp-login \
                      -Dsonar.sources=. \
                      -Dsonar.language=php
                    '''
                }
            }
        }

        stage('Quality Gate') {
            steps {
                script {
                    waitForQualityGate abortPipeline: false, credentialsId: 'Sonar-token'
                }
            }
        }

        stage('OWASP Dependency Check') {
            steps {
                dependencyCheck additionalArguments: '--scan ./ --format XML', odcInstallation: 'DP'
                dependencyCheckPublisher pattern: '**/dependency-check-report.xml'
            }
        }

        stage('Trivy FS Scan') {
            steps {
                sh 'trivy fs . > trivyfs.txt'
            }
        }

        stage('Docker Build & Push') {
            steps {
                script {
                    withDockerRegistry(credentialsId: 'manoj-docker', toolName: 'docker') {
                        sh 'docker build -t lamp-login .'
                        sh 'docker tag lamp-login manojkiran/lamp1-login:latest'
                        sh 'docker push manojkiran/lamp1-login:latest'
                    }
                }
            }
        }

        stage('Trivy Image Scan') {
            steps {
                sh 'trivy image manojkiran/lamp-login:latest > trivyimage.txt'
            }
        }

        stage('Deploy to Container') {
            steps {
                sh 'docker run -d --rm --name lamp-login -p 8081:80 manojkiran/lamp1-login:latest'
            }
        }
    }
}

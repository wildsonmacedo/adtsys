pipeline {
    agent { label 'php' }
    
    environment {
        WEB_SERVER = "172.31.4.155"
        GIT_COMMIT_HASH = "${sh(script: 'git rev-parse --short HEAD', returnStdout: true).trim()}"
    }
    
    stages {
        stage('Build') {
            steps {
                sh '''
                    docker build -t 00-web:${GIT_COMMIT_HASH}-${BUILD_NUMBER} -f Dockerfile .
                '''
            }
        }
        stage('Test') { 
            steps {
                sh '''
                    docker run -d --name 00-web-test-container -p 80:80 00-web:${GIT_COMMIT_HASH}-${BUILD_NUMBER}
                    sleep 5
                    TEST_PORT=$(curl -o /dev/null -s -w "%{http_code}" localhost)
                    docker stop 00-web-test-container
                    docker rm -f 00-web-test-container
                    if [ $TEST_PORT -eq 200 ]; then
                     echo "FUNCIONOU"
                     exit 0
                    else
                      echo "Deu treta"
                      exit 1
                    fi
                '''
            }
        }
        stage('Deploy') { 
            steps {
                sshagent(credentials: ['ssh']) {
                    sh "ssh -o StrictHostKeyChecking=no root@${WEB_SERVER} hostname"
                }
            }
        }
    }
}

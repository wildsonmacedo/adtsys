pipeline {
    agent { label 'php' }
    
    environment {
        GIT_COMMIT_HASH = ''
    }
    
    stages {
        stage('Build') {
            steps {
                script {
                    GIT_COMMIT_HASH = sh(script: 'git rev-parse --short HEAD', returnStdout: true)
                }
                sh '''
                    docker build -t 00-web:${env.GIT_COMMIT_HASH}-${BUILD_NUMBER} -f Dockerfile .
                '''
            }
        }
        stage('Test') { 
            steps {
                sh '''
                    docker run -d 00-web:$(cat ./tmp/docker-tag.txt) --name 00-web-test-container -p 80:80
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
                echo ''
            }
        }
    }
}

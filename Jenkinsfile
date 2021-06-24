pipeline {
    agent { label 'php' }
    
    environment {
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
                    docker run -d 00-web:${GIT_COMMIT_HASH}-${BUILD_NUMBER} --name 00-web-test-container -p 80:80
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
                echo ''
            }
        }
    }
}

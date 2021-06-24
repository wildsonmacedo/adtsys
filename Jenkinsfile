pipeline {
    agent { label 'php' }
    stages {
        stage('Build') { 
            steps {
              sh '''
                 GIT_COMMIT_HASH=$(git rev-parse --short HEAD)
                 mkdir .tmp
                 echo $GIT_COMMIT_HASH > .tmp/docker-tag.txt
                 docker build -t 00-web:${GIT_COMMIT_HASH}-${BUILD_NUMBER} -f Dockerfile .
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

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
                    if [ $TEST_PORT -ne 200 ]; then
                      echo "Deu treta"
                      exit 1
                    fi
                    echo  "FUNCIONOU"
                    docker tag 00-web:${GIT_COMMIT_HASH}-${BUILD_NUMBER} 00-web:latest
                '''
            }
        }
        stage('Docker Push') {
            steps {
                withCredentials([usernamePassword(credentialsId: 'dockerHub', passwordVariable: 'pass', usernameVariable: 'user')]) {
                sh '''
                docker login -u \$user -p \$pass 
                '''
                }
                sh '''
                    docker push 00-web:${GIT_COMMIT_HASH}-${BUILD_NUMBER}
                    docker push 00-web:latest
                    docker rmi 00-web:${GIT_COMMIT_HASH}-${BUILD_NUMBER}
                    docker logout
                '''
            }
        }
        stage('Deploy') { 
            steps {
                sshagent(credentials: ['ssh']) {
                    sh "chmod +x start_docker.sh && scp start_docker.sh root@${WEB_SERVER}:/root"
                    sh "ssh root@${WEB_SERVER} /root/start_docker.sh ${GIT_COMMIT_HASH}-${BUILD_NUMBER}" 
                }
            }
        }
    }
}

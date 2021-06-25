pipeline {
    agent { label 'php' }
    
    environment {
        WEB_SERVER = "172.31.7.87"
        GIT_COMMIT_HASH = "${sh(script: 'git rev-parse --short HEAD', returnStdout: true).trim()}"
        NOME_IMAGEM = "wildsonmacedo/00-web" 
    }
    
    stages {
        stage('Build') {
            steps {
                sh '''
                    docker build -t ${NOME_IMAGEM}:${GIT_COMMIT_HASH}-${BUILD_NUMBER} -f Dockerfile .
                '''
            }
        }
        stage('Test') { 
            steps {
                sh '''
                    docker run -d --name 00-web-${BUILD_NUMBER} -p 8080:80 ${NOME_IMAGEM}:${GIT_COMMIT_HASH}-${BUILD_NUMBER}
                    sleep 5
                    TEST_PORT=$(curl -o /dev/null -s -w "%{http_code}" localhost:8080)
                    docker stop 00-web-${BUILD_NUMBER}
                    docker rm -f 00-web-${BUILD_NUMBER}
                    if [ $TEST_PORT -ne 200 ]; then
                      echo "Deu treta"
                      exit 1
                    fi
                    echo  "FUNCIONOU"
                    docker tag ${NOME_IMAGEM}:${GIT_COMMIT_HASH}-${BUILD_NUMBER} ${NOME_IMAGEM}:latest
                '''
            }
        }
        stage('Docker Push') {
            steps {
                script {
                    docker.withRegistry('', 'dockerHub') {
                        sh '''
                            docker push ${NOME_IMAGEM}:${GIT_COMMIT_HASH}-${BUILD_NUMBER}
                            docker push ${NOME_IMAGEM}:latest
                            docker rmi ${NOME_IMAGEM}:${GIT_COMMIT_HASH}-${BUILD_NUMBER}
                        '''
                    }
                }
            }
        }
        stage('Deploy') { 
            steps {
                sshagent(credentials: ['ssh']) {
                    sh "chmod +x start_docker.sh && scp -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no start_docker.sh root@${WEB_SERVER}:/root"
                    sh "ssh -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no root@${WEB_SERVER} /root/start_docker.sh ${NOME_IMAGEM}:${GIT_COMMIT_HASH}-${BUILD_NUMBER}" 
                }
            }
        }
    }
}

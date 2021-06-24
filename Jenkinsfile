pipeline {
    agent { label 'php' }
    stages {
        stage('Build') { 
            steps {
              sh '''
                 GIT_COMMIT_HASH = $(git rev-parse --short HEAD)
                 docker build -t 00-web:${GIT_COMMIT_HASH}-${BUILD_NUMBER} -f Dockerfile .
              '''
            }
        }
        stage('Test') { 
            steps {
                echo 'teste'
            }
        }
        stage('Deploy') { 
            steps {
                echo 'deploy'  
            }
        }
    }
}

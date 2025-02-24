pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                git branch: 'develop', url: 'https://gitlab.com/test781701/telemetria2.git'
            }
        }

        stage('Exclude Directories') {
            steps {
                sh '''
                    rm -rf application/config
                    rm -rf assets/uploads
                '''
            }
        }

        stage('Install NPM Dependencies') {
            steps {
				sh 'echo "Instalando NPM..."'
                sh 'npm install'
            }
        }

        stage('Install Bower Dependencies') {
            steps {
				sh 'echo "Instalando Bower..."'
                sh 'bower install --allow-root'
            }
        }

        stage('Install Composer Dependencies') {
            steps {
				sh 'echo "Instalando Composer..."'
                sh 'composer install --no-dev --optimize-autoloader'
            }
        }

        stage('Build/Deploy') {
            steps {
                sh 'echo "Realizando build ou deploy..."'
            }
        }
    }

    post {
        success {
            echo 'Pipeline executada com sucesso!'
        }
        failure {
            echo 'Pipeline falhou. Verifique os logs.'
        }
    }
}
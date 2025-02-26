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
                    rm -rf assets/uploads/*
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

        stage('Deploy para Aplicacao') {
            steps {
                script {
                    echo "Fazendo deploy para o contêiner de aplicação..."
                    sh """
                        rsync -avz --delete ./application/ ./application/
                    """
                }
            }
        }

        stage('Deploy para Teste') {
            steps {
                script {
                    echo "Fazendo deploy para o contêiner de teste..."
                    sh """
                        rsync -avz --delete ./application/ ./teste/
                    """
                }
            }
        }
        
        stage('Reiniciar Contêineres') {
            steps {
                script {
                    echo "Reiniciando contêineres para aplicar as mudanças..."
                    sh "docker-compose restart aplicacao teste homologacao"
                }
            }
        }
    }

    post {
        success {
            echo "Pipeline executada com sucesso!"
        }
        failure {
            echo "Pipeline falhou. Verifique os logs."
        }
    }         
}



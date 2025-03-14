pipeline {
    agent any

    stages {
        stage('Clone Repository') {
            steps {
                script {
                    echo "Clonando repositório..."
                    git url: 'https://github.com/Lcsbg16/Test.git', branch: 'develop'

                    echo "Listando conteúdo do workspace..."
                    sh "ls -la"
                }
            }
        }

        stage('Sincronizar Arquivos com Contêineres') {
            steps {
                script {
                    echo "Sincronizando arquivos com o contêiner de teste..."
                    sh """
                        rsync -avz --delete ./application/ teste:/var/www/html/
                    """

                    echo "Sincronizando arquivos com o contêiner de homologação..."
                    sh """
                        rsync -avz --delete ./application/ homologacao:/var/www/html/
                    """
                }
            }
        }

        stage('Reiniciar Contêineres') {
            steps {
                script {
                    echo "Reiniciando contêineres para aplicar as mudanças..."
                    sh "docker-compose down"
                    sh "docker-compose up -d"
                }
            }
        }

        stage('Notificar Sucesso') {
            steps {
                script {
                    echo "Pipeline executada com sucesso!"
                    // Adicione aqui notificações (e-mail, Slack, etc.)
                }
            }
        }
    }

    post {
        failure {
            echo "Pipeline falhou. Verifique os logs."
            // Adicione aqui notificações de falha (e-mail, Slack, etc.)
        }
    }
}

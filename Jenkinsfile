pipeline {
    agent any

    stages {
        stage('Clone Repository') {
            steps {
                script {
                    echo "Clonando repositório..."
                    git url: 'https://github.com/Lcsbg16/Test.git', branch: 'develop'

                    echo "Listando conteúdo do workspace..."
                    bat "dir"
                }
            }
        }

        stage('Criar e Configurar Contêineres') {
            steps {
                script {
                    echo "Criando e configurando contêineres..."

                    bat "docker stop aplicacao teste homologacao || true"
                    bat "docker rm aplicacao teste homologacao || true"

                    bat """
                        docker run -d \
                        --name aplicacao \
                        -p 8081:80 \
                        -v ${WORKSPACE}/application:/var/www/html \
                        --network minha-rede \
                        php:8.1-apache
                    """

                    bat """
                        docker run -d \
                        --name teste \
                        -p 8082:80 \
                        -v ${WORKSPACE}:/var/www/html \
                        -v /var/www/html/homologacao \
                        -v /var/www/html/teste \
                        --network minha-rede \
                        php:8.1-apache
                    """

                    bat """
                        docker run -d \
                        --name homologacao \
                        -p 8083:80 \
                        -v ${WORKSPACE}/homologacao:/var/www/html \
                        --network minha-rede \
                        php:8.1-apache
                    """
                }
            }
        }

        stage('Notificar Sucesso') {
            steps {
                script {
                    echo "Pipeline executada com sucesso!"
                }
            }
        }
    }

    post {
        failure {
            echo "Pipeline falhou."            
        }
    }
}

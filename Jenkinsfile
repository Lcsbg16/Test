pipeline {
    agent any

    stages {
        stage('Clone Repository') {
            steps {
                script {
                    echo "Clonando repositório do GitLab..."
                    git url: 'https://github.com/Lcsbg16/Test.git', branch: 'develop'

                    echo "Listando conteúdo do workspace para verificação..."
                    bat "dir"
                }
            }
        }

        stage('Deploy para Aplicacao') {
            steps {
                script {
                    echo "Fazendo deploy para o contêiner de aplicação..."
                    bat "xcopy /E /I /Y .\\application\\ .\\application\\"
                }
            }
        }

        stage('Deploy para Teste') {
            steps {
                script {
                    echo "Fazendo deploy para o contêiner de teste..."
                    bat "xcopy /E /I /Y .\\application\\ .\\teste\\"
                }
            }
        }

        stage('Deploy para Homologacao') {
            steps {
                script {
                    echo "Fazendo deploy para o contêiner de homologação..."
                    bat "xcopy /E /I /Y .\\application\\ .\\homologacao\\"
                }
            }
        }

        stage('Reiniciar Contêineres') {
            steps {
                script {
                    echo "Reiniciando contêineres para aplicar as mudanças..."
                    bat "docker-compose restart aplicacao teste homologacao"
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

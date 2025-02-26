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

        stage('Desmontar Contêineres Antigos') {
            steps {
                script {
                    echo "Parando e removendo contêineres antigos..."
                    bat "docker-compose down"
                }
            }
        }

        stage('Levantar Contêineres Novamente') {
            steps {
                script {
                    echo "Iniciando contêineres novamente..."
                    bat "docker-compose up -d"
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

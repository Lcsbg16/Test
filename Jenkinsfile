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

        stage('Reiniciar Contêineres') {
            steps {
                script {
                    echo "Reiniciando contêineres para aplicar as mudanças..."
                    bat "docker-compose down"
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

node{
    
    stage('SCM Checkout'){
        checkout scm
    }
    
    stage('Build docker image') {
        sh "docker-compose build"
    }
    stage('Push Image') {
        sh "docker-compose push"
    }
    stage('Run Application') {
        sh "docker-compose up -d" 
    }
}

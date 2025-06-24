// Se connecter au serveur WebSocket
const socket = io('http://localhost:3000');

// Quand la connexion est établie
socket.on('connect', () => {
    console.log('Connecté au serveur WebSocket');
});

// Écouter la notification du démarrage du quiz
socket.on('quiz_started', (message) => {
    alert(message);
});

// Écouter la réponse du serveur après envoi de la réponse
socket.on('answer_received', (data) => {
    alert(data.result);
});

// Fonction pour démarrer le quiz
function startQuiz() {
    socket.emit('start_quiz', { user_id: 1, quiz_id: 101 }); // Exemple avec un user_id fictif
}

// Fonction pour envoyer une réponse au serveur
function sendAnswer(answer) {
    socket.emit('send_answer', { user_id: 1, answer: answer });
}
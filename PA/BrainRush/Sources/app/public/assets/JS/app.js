// Se connecter au serveur WebSocket
const socket = io('http://localhost:3000');

socket.on('connect', () => {
    console.log('Connecté au serveur WebSocket');
});

socket.on('quiz_started', (message) => {
    alert(message);
});

socket.on('answer_received', (data) => {
    alert(data.result);
});

function startQuiz() {
    socket.emit('start_quiz', { user_id: 1, quiz_id: 101 });
}

function sendAnswer(answer) {
    socket.emit('send_answer', { user_id: 1, answer: answer });
}
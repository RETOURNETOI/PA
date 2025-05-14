const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const mysql = require('mysql2');

// Création de l'application Express
const app = express();
const server = http.createServer(app);
const io = socketIo(server); // Attache Socket.io au serveur HTTP

// Configuration de la base de données MySQL
const db = mysql.createConnection({
  host: 'localhost',
  user: 'root', // Ton utilisateur MySQL
  password: '', // Ton mot de passe MySQL
  database: 'quiz_db' // Le nom de ta base de données
});

db.connect(err => {
  if (err) throw err;
  console.log('Connecté à la base de données MySQL!');
});

// Middleware pour servir les fichiers statiques (HTML, CSS, JS)
app.use(express.static('public'));

// Quand une nouvelle connexion WebSocket est établie
io.on('connection', (socket) => {
  console.log('Un utilisateur est connecté.');

  // Écouter un événement de démarrage de quiz
  socket.on('start_quiz', (data) => {
    console.log('Quiz commencé', data);
    socket.emit('quiz_started', 'Le quiz a commencé!');
  });

  // Écouter une réponse d'utilisateur
  socket.on('send_answer', (data) => {
    console.log('Réponse reçue : ', data);

    // Enregistrer la réponse dans la base de données
    const query = 'INSERT INTO quiz_answers (user_id, answer) VALUES (?, ?)';
    db.query(query, [data.user_id, data.answer], (err, result) => {
      if (err) throw err;
      console.log('Réponse enregistrée dans la base de données');
    });

    // Envoyer une réponse à l'utilisateur
    socket.emit('answer_received', { result: 'Réponse enregistrée avec succès.' });
  });

  // Quand un utilisateur se déconnecte
  socket.on('disconnect', () => {
    console.log('Utilisateur déconnecté.');
  });
});

// Démarrer le serveur sur le port 3000
server.listen(3000, () => {
  console.log('Serveur WebSocket en écoute sur http://localhost:3000');
});

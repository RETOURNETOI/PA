<?php
// db.php

$host = 'localhost';
$dbname = 'brainrush';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Gestion des erreurs
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Mode de récupération par défaut
} catch (PDOException $e) {
    // Journaliser en cas d'erreur
    error_log("Erreur de connexion BDD : " . $e->getMessage());
    
    // Réponse sécurisée sans fuite d'info sensible
    die("Impossible de se connecter à la base de données.");
}
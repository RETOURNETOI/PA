<?php
require_once 'db.php';
require_once 'User.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $data = $stmt->fetch();

            if ($data && password_verify($password, $data['password'])) {
                $user = new User($data['username'], $data['email'], $data['password']);
                $user->setUsername($data['username']);
                $user->setEmail($data['email']);
                $user->setPassword($data['password']);
                $_SESSION['user_id'] = $data['id'];
                $_SESSION['username'] = $data['username'];
                echo "Connexion réussie. Bienvenue, " . htmlspecialchars($data['username']) . " !";
                // header('Location: tableau_de_bord.php'); // à utiliser pour rediriger
            } else {
                echo "Email ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            error_log("Erreur connexion : " . $e->getMessage());
            echo "Erreur lors de la connexion.";
        }
    } else {
        echo "Tous les champs sont requis.";
    }
}
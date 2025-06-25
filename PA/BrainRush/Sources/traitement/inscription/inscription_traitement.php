<?php
require_once 'db.php';
require_once 'User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($email) && !empty($password)) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                echo "Un utilisateur avec cet e-mail existe déjà.";
                exit;
            }

            $user = new User($username, $email, $password);
            $user->save($pdo);
            echo "Inscription réussie. <a href='connexion.html'>Se connecter</a>";
        } catch (PDOException $e) {
            error_log("Erreur inscription : " . $e->getMessage());
            echo "Erreur lors de l'inscription.";
        }
    } else {
        echo "Tous les champs sont requis.";
    }
}
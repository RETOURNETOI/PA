<?php
require_once '../Bdd/connexion.php';
require_once '../utilisateur/user_funct.php';
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
                
                $_SESSION['user_id'] = $data['id'];
                $_SESSION['username'] = $data['username'];
                $_SESSION['email'] = $data['email'];
                
                header('Location: ../../public/index.php');
                exit();
            } else {
                header('Location: ../../public/connexion.html?error=invalid_credentials');
                exit();
            }
        } catch (PDOException $e) {
            error_log("Erreur connexion : " . $e->getMessage());
            header('Location: ../../public/connexion.html?error=database_error');
            exit();
        }
    } else {
        header('Location: ../../public/connexion.html?error=missing_fields');
        exit();
    }
}

header('Location: ../../public/connexion.html');
exit();
?>
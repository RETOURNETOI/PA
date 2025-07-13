<?php
session_start(); //corrigé

require_once __DIR__ . '/../../core/database.php';
require_once __DIR__ . '/../../models/user_model.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ../admin/dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        $user = getUserByEmail($pdo, $email);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: ../admin/dashboard.php");
            exit;
        } else {
            $error = "Identifiants incorrects.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="../../public/assets/CSS/index.css">
    <link rel="stylesheet" href="../../public/assets/CSS/main.css">
    <link rel="stylesheet" href="../../public/assets/CSS/connexion.css">
</head>
<body>
    <?php include __DIR__ . '/../../include/header.php'; ?>

    <div class="connexion-wrapper">
        <div class="login-container">
            <h2>Connexion</h2>
            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <input type="email" name="email" id="email" class="form-control" required placeholder="exemple@mail.com">
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control" required placeholder="••••••••">
                </div>
                <div class="form-group">
                    <button type="submit" id="evil-button">Se connecter</button>
                </div>
            </form>
            <div class="inscription-link">
                <a href="register.php">Pas encore de compte ? S'inscrire</a>
            </div>
        </div>
    </div>

    <script src="../../public/assets/JS/index.js"></script>
    <script src="../../public/assets/JS/main.js"></script>
    <script src="../../public/assets/JS/connexion.js"></script>
</body>
</html>

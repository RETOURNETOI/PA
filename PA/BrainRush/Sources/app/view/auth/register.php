<?php
session_start();

require_once __DIR__ . '/../../core/database.php';
require_once __DIR__ . '/../../models/user_model.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ../admin/dashboard.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($username && $email && $password && $confirm_password) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Adresse email invalide.";
        } elseif ($password !== $confirm_password) {
            $error = "Les mots de passe ne correspondent pas.";
        } elseif (getUserByEmail($pdo, $email)) {
            $error = "Un compte existe déjà avec cet email.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            if (createUser($pdo, $username, $email, $hashedPassword)) {
                $success = "Inscription réussie. Vous pouvez maintenant vous connecter.";
                header("Refresh:2; url=login.php");
            } else {
                $error = "Erreur lors de l'inscription. Veuillez réessayer.";
            }
        }
    } else {
        $error = "Tous les champs sont requis.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="../../public/assets/CSS/index.css">
    <link rel="stylesheet" href="../../public/assets/CSS/main.css">
    <link rel="stylesheet" href="../../public/assets/CSS/inscription.css">
</head>
<body>
    <?php include __DIR__ . '/../../include/header.php'; ?>

    <div class="connexion-wrapper">
        <div class="login-container">
            <h2>Inscription</h2>
            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php elseif ($success): ?>
                <div class="success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Pseudo</label>
                    <input type="text" name="username" id="username" class="form-control" required placeholder="Votre pseudo">
                </div>
                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <input type="email" name="email" id="email" class="form-control" required placeholder="exemple@mail.com">
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control" required placeholder="••••••••">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirmation</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" required placeholder="••••••••">
                </div>
                <div class="form-group">
                    <button type="submit" id="evil-button">Créer mon compte</button>
                </div>
            </form>
            <div class="inscription-link">
                <a href="login.php">Déjà inscrit ? Se connecter</a>
            </div>
        </div>
    </div>

    <script src="../../public/assets/JS/index.js"></script>
    <script src="../../public/assets/JS/main.js"></script>
    <script src="../../public/assets/JS/inscription.js"></script>
</body>
</html>

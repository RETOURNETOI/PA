<?php
session_start();
require_once 'config/database.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);
    
    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Erreur lors de l'inscription";
    }
}

$page_title = "Inscription";
require_once 'includes/header.php';
?>

<link rel="stylesheet" href="assets/css/register.css">

<div class="register-container">
    <form method="POST">
        <h2>Inscription</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">S'inscrire</button>
    </form>
</div>

<script src="assets/js/main.js"></script>
<script src="assets/js/register.js"></script>

<?php require_once 'includes/footer.php'; ?>
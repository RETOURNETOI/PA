<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['user'])) {
    header('Location: profile.php');
    exit;
}

if (!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_retype'])) {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $password_retype = htmlspecialchars($_POST['password_retype']);

    if ($password !== $password_retype) {
        header('Location: register.php?reg_err=password');
        exit();
    }

    $check = $bdd->prepare('SELECT email FROM utilisateurs WHERE email = ?');
    $check->execute(array($email));
    $data = $check->fetch();
    $row = $check->rowCount();

    if ($row == 0) {
        if (strlen($email) <= 100) {
            if (strlen($password) >= 6) {
                $password = password_hash($password, PASSWORD_BCRYPT);
                $ip = $_SERVER['REMOTE_ADDR'];

                $insert = $bdd->prepare('INSERT INTO utilisateurs(email, password, ip) VALUES(:email, :password, :ip)');
                $insert->execute(array(
                    'email' => $email,
                    'password' => $password,
                    'ip' => $ip
                ));
                header('Location: register.php?reg_err=success');
                exit();
            } else header('Location: register.php?reg_err=password_length');
        } else header('Location: register.php?reg_err=email_length');
    } else header('Location: register.php?reg_err=already');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <div class="form-container">
            <h2>Inscription</h2>
            <form action="register.php" method="POST">
                <div class="input-group">
                    <input type="email" name="email" placeholder="Adresse email" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Mot de passe" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password_retype" placeholder="Confirmez le mot de passe" required>
                </div>
                <button type="submit" class="moving-btn">S'inscrire</button>
            </form>
            <?php
            if (isset($_GET['reg_err'])) {
                $err = htmlspecialchars($_GET['reg_err']);
                switch ($err) {
                    case 'success':
                        echo '<p class="success">Inscription réussie!</p>';
                        break;
                    case 'password':
                        echo '<p class="error">Les mots de passe ne correspondent pas</p>';
                        break;
                    case 'already':
                        echo '<p class="error">Ce compte existe déjà</p>';
                        break;
                    case 'email_length':
                        echo '<p class="error">Email trop long</p>';
                        break;
                    case 'password_length':
                        echo '<p class="error">Le mot de passe doit contenir au moins 6 caractères</p>';
                        break;
                }
            }
            ?>
            <p class="link">Déjà inscrit? <a href="login.php">Connectez-vous ici</a></p>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/index.js"></script>
    <script>
        // Animation du bouton qui bouge
        const movingBtn = document.querySelector('.moving-btn');
        
        movingBtn.addEventListener('mouseover', () => {
            const randomX = Math.random() * 20 - 10;
            const randomY = Math.random() * 20 - 10;
            movingBtn.style.transform = `translate(${randomX}px, ${randomY}px)`;
        });
        
        movingBtn.addEventListener('mouseout', () => {
            movingBtn.style.transform = 'translate(0, 0)';
        });
    </script>
</body>
</html>
<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['user'])) {
    header('Location: profile.php');
    exit;
}

if (!empty($_POST['email']) && !empty($_POST['password'])) {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    $check = $bdd->prepare('SELECT email, password FROM utilisateurs WHERE email = ?');
    $check->execute(array($email));
    $data = $check->fetch();
    $row = $check->rowCount();

    if ($row > 0) {
        if (password_verify($password, $data['password'])) {
            $_SESSION['user'] = $data['email'];
            header('Location: profile.php');
            exit();
        } else header('Location: login.php?login_err=password');
    } else header('Location: login.php?login_err=email');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <div class="form-container">
            <h2>Connexion</h2>
            <form action="login.php" method="POST">
                <div class="input-group">
                    <input type="email" name="email" placeholder="Adresse email" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Mot de passe" required>
                </div>
                <button type="submit" class="moving-btn">Se connecter</button>
            </form>
            <?php
            if (isset($_GET['login_err'])) {
                $err = htmlspecialchars($_GET['login_err']);
                switch ($err) {
                    case 'password':
                        echo '<p class="error">Mot de passe incorrect</p>';
                        break;
                    case 'email':
                        echo '<p class="error">Email incorrect</p>';
                        break;
                }
            }
            ?>
            <p class="link">Pas encore inscrit? <a href="register.php">Inscrivez-vous ici</a></p>
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
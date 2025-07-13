<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../../public/assets/CSS/index.css">
    <link rel="stylesheet" href="../../public/assets/CSS/main.css">
    <link rel="stylesheet" href="../../public/assets/CSS/dashboard.css">
</head>
<body>

    <div class="dashboard-container">
        <h1>Bienvenue sur votre tableau de bord</h1>
        <p>Vous êtes connecté avec succès.</p>
        <form action="../../lougout.php" method="POST">
            <button type="submit" class="logout-button">Se déconnecter</button>
        </form>
    </div>

    <script src="../../public/assets/JS/index.js"></script>
    <script src="../../public/assets/JS/main.js"></script>
    <script src="../../public/assets/JS/dashboard.js"></script>
</body>
</html>

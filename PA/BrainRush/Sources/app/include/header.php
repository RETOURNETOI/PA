<?php
// Sources/app/include/header.php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrainRush - <?= $pageTitle ?? 'Jeu Quiz Éducatif' ?></title>
    <link rel="stylesheet" href="/assets/CSS/main.css">
    <?php if(isset($cssFiles)): ?>
        <?php foreach($cssFiles as $cssFile): ?>
            <link rel="stylesheet" href="/assets/CSS/<?= $cssFile ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <header class="main-header">
        <div class="container">
            <a href="/" class="logo">
                <img src="/assets/images/lion.png" alt="Logo BrainRush">
                <span>BrainRush</span>
            </a>
            
            <nav class="main-nav">
                <a href="/quizz_solo">Solo</a>
                <a href="/vs">1vs1</a>
                <a href="/forum">Forum</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="/compte">Mon Compte</a>
                    <a href="/logout">Déconnexion</a>
                <?php else: ?>
                    <a href="/auth/login">Connexion</a>
                    <a href="/auth/register">Inscription</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="notification-icon">
    <span id="notification-badge" class="hidden"></span>
    <div id="notification-panel" class="hidden"></div>
</div>
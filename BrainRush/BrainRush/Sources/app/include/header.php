<?php
// Sources/app/include/header.php
session_start();
$baseUrl = '/BrainRush';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrainRush - <?= $pageTitle ?? 'Jeu Quiz Éducatif' ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/Sources/app/public/assets/CSS/main.css">
    <?php if(isset($cssFiles)): ?>
        <?php foreach($cssFiles as $cssFile): ?>
            <link rel="stylesheet" href="<?= $baseUrl ?>/Sources/app/public/assets/CSS/<?= $cssFile ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <header class="main-header">
        <div class="container">
            <a href="<?= $baseUrl ?>/" class="logo">
                <img src="<?= $baseUrl ?>/Sources/app/public/assets/images/lion.png" alt="Logo BrainRush">
                <span>BrainRush</span>
            </a>
            
            <nav class="main-nav">
                <a href="<?= $baseUrl ?>/quizz_solo">Solo</a>
                <a href="<?= $baseUrl ?>/vs">1vs1</a>
                <a href="<?= $baseUrl ?>/forum">Forum</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="<?= $baseUrl ?>/compte">Mon Compte</a>
                    <a href="<?= $baseUrl ?>/logout">Déconnexion</a>
                <?php else: ?>
                    <a href="<?= $baseUrl ?>/auth/login">Connexion</a>
                    <a href="<?= $baseUrl ?>/auth/register">Inscription</a>
                <?php endif; ?>
            </nav>
            
            <div class="language-switcher">
                <button class="lang-btn" data-lang="fr">FR</button>
                <button class="lang-btn" data-lang="en">EN</button>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="notification-icon">
            <span id="notification-badge" class="hidden"></span>
            <div id="notification-panel" class="hidden"></div>
        </div>
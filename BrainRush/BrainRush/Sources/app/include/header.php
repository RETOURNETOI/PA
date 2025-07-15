<?php
if (!isset($basePath)) {
    $basePath = '/BrainRush/BrainRush/public';
}
$baseUrl = '/BrainRush/BrainRush';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'BrainRush' ?></title>
    
    <link rel="stylesheet" href="<?= $basePath ?>/assets/CSS/main.css">
    <?php
    if (isset($cssFiles) && is_array($cssFiles)) {
        foreach ($cssFiles as $css) {
            echo '<link rel="stylesheet" href="'.$basePath.'/assets/CSS/'.$css.'">'."\n";
        }
    }
    ?>
</head>
<body>
    <nav class="custom-navbar">
        <div class="navbar-container">
            <a href="<?= $baseUrl ?>" class="navbar-brand">🧠 BrainRush</a>
            
            <ul class="navbar-links" id="navbar-menu">
                <li><a href="<?= $baseUrl ?>/" id="navHome">Accueil</a></li>
                <li><a href="<?= $baseUrl ?>/quizz_solo" id="navSolo">Solo</a></li>
                <li><a href="<?= $baseUrl ?>/vs" id="navVS">VS</a></li>
                <li><a href="<?= $baseUrl ?>/classement" id="navRank">Classement</a></li>
                <li><a href="<?= $baseUrl ?>/forum" id="navForum">Forum</a></li>
            </ul>
            
            <div class="navbar-actions">
                <button id="langToggle" class="navbar-btn icon">
                    <span id="langIcon">🇫🇷</span>
                </button>
                
                <button id="themeToggle" class="navbar-btn icon">🌙</button>
                
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <a href="<?= $baseUrl ?>/compte" class="navbar-btn secondary">
                        <?= htmlspecialchars($_SESSION['user_name']) ?>
                    </a>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <a href="<?= $baseUrl ?>/admin/dashboard" class="navbar-btn admin">Admin</a>
                    <?php endif; ?>
                    <a href="<?= $baseUrl ?>/auth/logout" class="navbar-btn">Déconnexion</a>
                <?php else: ?>
                    <a href="<?= $baseUrl ?>/auth/login" class="navbar-btn secondary" id="loginBtn">Se connecter</a>
                    <a href="<?= $baseUrl ?>/auth/register" class="navbar-btn primary" id="signupBtn">S'inscrire</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['message']) ?>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div id="chatbot-box" class="hidden">
            <div id="chatbox" class="chatbox-content"></div>
            <div class="chatbox-input">
                <input type="text" id="userInput" placeholder="Écris ton message..." />
            </div>
            <button id="close-chatbot" class="close-chatbot">×</button>
        </div>

        <button id="chatbot-icon" class="chatbot-open-button">💬</button>
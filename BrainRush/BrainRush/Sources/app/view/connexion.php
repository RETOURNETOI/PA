<?php
$pageTitle = "Connexion";
$cssFiles = ['connexion.css', 'chatbot.css'];
require_once __DIR__.'/../../include/header.php';
?>

<div class="connexion-wrapper">
    <div class="login-container">
        <h2 id="title">🔐 Connexion</h2>
        <form id="loginForm" action="/BrainRush/BrainRush/auth/login" method="POST">
            <div class="form-group">
                <label for="emailInput">Adresse email</label>
                <input type="email" id="emailInput" name="email" class="form-control" placeholder="Entrez votre email" required />
            </div>
            <div class="form-group">
                <label for="passwordInput">Mot de passe</label>
                <input type="password" id="passwordInput" name="password" class="form-control" placeholder="Entrez votre mot de passe" required />
            </div>
        </form>
        <div class="inscription-link" id="signupLine">
            <a href="/BrainRush/BrainRush/auth/register">Vous n'avez pas de compte ? Inscrivez-vous</a>
        </div>
        <button type="submit" form="loginForm" id="evil-button">Connexion</button>
    </div>
</div>

<?php 
$jsFiles = ['connexion.js', 'chatbot.js'];
require_once __DIR__.'/../../include/footer.php';
?>
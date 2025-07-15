<?php
$pageTitle = "Inscription";
$cssFiles = ['inscription.css', 'chatbot.css'];
require_once __DIR__.'/../../include/header.php';
?>

<div class="inscription-wrapper">
    <div class="signin-container">
        <h2 id="inscriptionTitle">Inscription</h2>
        <form action="/BrainRush/BrainRush/auth/register" method="POST" class="inscription-form" id="loginForm">
            <div class="form-group">
                <input type="text" name="username" id="firstNameInput" placeholder="Nom d'utilisateur" required />
            </div>
            <div class="form-group">
                <input type="email" name="email" id="emailInput" placeholder="Adresse email" required />
            </div>
            <div class="form-group">
                <input type="password" name="password" id="passwordInput" placeholder="Mot de passe" required />
            </div>
            <div class="form-group">
                <input type="password" name="confirm_password" id="passwordConfirmInput" placeholder="Confirmer le mot de passe" required />
            </div>
            <button type="submit" class="btn btn-outline-primary mt-3 w-100" id="evil-button">S'inscrire</button>
        </form>
        
        <div class="connexion-link">
            <p>Déjà un compte ? <a href="/BrainRush/BrainRush/auth/login">Se connecter</a></p>
        </div>
    </div>
</div>

<?php 
$jsFiles = ['inscription.js', 'chatbot.js'];
require_once __DIR__.'/../../include/footer.php';
?>
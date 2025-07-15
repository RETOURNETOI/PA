<?php
$pageTitle = "Mot de passe oublié";
$cssFiles = ['auth.css', 'chatbot.css'];
require_once __DIR__.'/../../include/header.php';
?>

<div class="auth-container">
    <div class="auth-form">
        <h2>Mot de passe oublié</h2>
        <p>Entrez votre adresse email pour recevoir un lien de réinitialisation.</p>
        
        <form method="POST" action="/BrainRush/BrainRush/auth/forgot-password">
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit" class="btn-submit">Envoyer le lien</button>
        </form>
        
        <p class="auth-link">
            <a href="/BrainRush/BrainRush/auth/login">← Retour à la connexion</a>
        </p>
    </div>
</div>

<?php 
$jsFiles = ['auth.js', 'chatbot.js'];
require_once __DIR__.'/../../include/footer.php';
?>
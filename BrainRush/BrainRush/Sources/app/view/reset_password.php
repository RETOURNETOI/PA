<?php
$pageTitle = "Réinitialiser le mot de passe";
$cssFiles = ['auth.css', 'chatbot.css'];
require_once __DIR__.'/../../include/header.php';
?>

<div class="auth-container">
    <div class="auth-form">
        <h2>Nouveau mot de passe</h2>
        <p>Entrez votre nouveau mot de passe.</p>
        
        <form method="POST" action="/BrainRush/BrainRush/auth/reset-password">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">
            
            <div class="form-group">
                <label for="password">Nouveau mot de passe</label>
                <input type="password" id="password" name="password" required minlength="6">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
            </div>
            
            <button type="submit" class="btn-submit">Mettre à jour</button>
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
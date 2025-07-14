<?php
$pageTitle = "Inscription";
$cssFiles = ['auth.css', 'chatbot.css'];
require_once __DIR__.'/../include/header.php';
?>

<div class="auth-container">
    <h1>Inscription</h1>
    <form action="/auth/register" method="POST">
        <div class="form-group">
            <label for="username">Pseudo</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirmer le mot de passe</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn">S'inscrire</button>
    </form>
    <p>Déjà un compte ? <a href="/auth/login">Se connecter</a></p>
</div>

<?php 
$jsFiles = ['auth.js', 'chatbot.js'];
require_once __DIR__.'/../include/footer.php';
?>
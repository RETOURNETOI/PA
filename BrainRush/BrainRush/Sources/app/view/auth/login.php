<?php
$pageTitle = "Connexion";
$cssFiles = ['auth.css', 'chatbot.css'];
require_once __DIR__.'/../include/header.php';
?>

<div class="auth-container">
    <h1>Connexion</h1>
    <form action="/auth/login" method="POST">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn">Se connecter</button>
    </form>
    <p>Pas encore de compte ? <a href="/auth/register">S'inscrire</a></p>
</div>

<?php 
$jsFiles = ['auth.js', 'chatbot.js'];
require_once __DIR__.'/../include/footer.php';
?>
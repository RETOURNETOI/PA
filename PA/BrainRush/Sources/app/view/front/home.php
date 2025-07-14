<?php
$pageTitle = "Accueil";
$cssFiles = ['index.css', 'chatbot.css'];
require_once __DIR__.'/../include/header.php';
?>

<div class="home-container">
    <section class="hero-section">
        <h1 id="welcomeTitle">Bienvenue sur BrainRush !</h1>
        <p id="welcomeSubtitle">Testez vos connaissances, affrontez vos amis, grimpez dans le classement !</p>
    </section>

    <div class="game-options">
        <div class="option-card">
            <h3 id="soloTitle">🧠 Quizz Solo</h3>
            <p id="soloDesc">Jouez en solo sur des dizaines de thèmes !</p>
            <a href="/quizz_solo" class="btn" id="soloBtn">Commencer</a>
        </div>
        
        <div class="option-card">
            <h3 id="vsTitle">⚔️ Quizz VS</h3>
            <p id="vsDesc">Affrontez vos amis en temps réel.</p>
            <a href="/vs" class="btn" id="vsBtn">Défier</a>
        </div>
    </div>
</div>

<?php 
$jsFiles = ['index.js', 'chatbot.js'];
require_once __DIR__.'/../include/footer.php';
?>
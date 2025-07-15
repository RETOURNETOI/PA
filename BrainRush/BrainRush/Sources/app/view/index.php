<?php
// Sources/app/view/index.php

// Définition des métadonnées et inclusions
$pageTitle = "Accueil";
$cssFiles = ['index.css', 'chatbot.css'];
$jsFiles = ['index.js', 'chatbot.js'];

// Chemin de base dynamique pour MAMP
$basePath = '/BrainRush';
$isLocal = ($_SERVER['SERVER_NAME'] === 'localhost' || strpos($_SERVER['SERVER_NAME'], '.local') !== false);
$assetsPath = $isLocal ? $basePath.'/Sources/app/public/assets' : '/assets';

// Démarrer la session si pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure le header
require_once __DIR__.'/../include/header.php';
?>

<!-- Section Hero -->
<section class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4" id="welcomeTitle">Bienvenue sur BrainRush !</h1>
        <p class="lead" id="welcomeSubtitle">Testez vos connaissances, affrontez vos amis, grimpez dans le classement !</p>
    </div>
</section>

<!-- Section Options de Jeu -->
<section class="container text-center py-5" id="game-options">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title" id="soloTitle">🧠 Quizz Solo</h5>
                    <p class="card-text" id="soloDesc">Jouez en solo sur des dizaines de thèmes !</p>
                    <a href="<?= $basePath ?>/quizz_solo" class="btn btn-outline-primary" id="soloBtn">Commencer</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title" id="vsTitle">⚔️ Quizz VS</h5>
                    <p class="card-text" id="vsDesc">Affrontez vos amis en temps réel.</p>
                    <a href="<?= $basePath ?>/vs" class="btn btn-outline-danger" id="vsBtn">Défier</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title" id="rankTitle">🏆 Classement</h5>
                    <p class="card-text" id="rankDesc">Découvrez les meilleurs joueurs.</p>
                    <a href="<?= $basePath ?>/classement" class="btn btn-outline-success" id="rankBtn">Voir</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Podium -->
<section class="container text-center py-5">
    <h2 id="podiumTitle">🏆 Podium des 3 MVP All-Time</h2>
    
    <div class="podium">
        <div class="step second">
            <div class="avatar"><img src="<?= $assetsPath ?>/images/lion.png" alt="2ème place"></div>
            <div class="block">2</div>
            <div class="info">
                <p class="pseudo">Joueur2</p>
                <p class="points">⚡ 8900 pts</p>
            </div>
        </div>

        <div class="step first">
            <div class="avatar"><img src="<?= $assetsPath ?>/images/avatar_def1.png" alt="1ère place"></div>
            <div class="block">1</div>
            <div class="info">
                <p class="pseudo">Joueur1</p>
                <p class="points">🌟 10250 pts</p>
            </div>
        </div>

        <div class="step third">
            <div class="avatar"><img src="<?= $assetsPath ?>/images/tigre.png" alt="3ème place"></div>
            <div class="block">3</div>
            <div class="info">
                <p class="pseudo">Joueur3</p>
                <p class="points">🔥 8450 pts</p>
            </div>
        </div>
    </div>
</section>

<?php
// Inclure le footer
require_once __DIR__.'/../include/footer.php';
?>
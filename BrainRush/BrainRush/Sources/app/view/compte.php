<?php
$pageTitle = "Mon Compte";
$cssFiles = ['dashboard.css', 'chatbot.css'];
require_once __DIR__.'/../include/header.php';
?>

<div class="account-container">
    <div class="profile-section">
        <div class="avatar">
            <img src="/assets/images/avatar_def1.png" alt="Avatar">
        </div>
        <div class="profile-info">
            <h1><?= htmlspecialchars($_SESSION['username'] ?? 'Utilisateur') ?></h1>
            <p>Membre depuis : <?= date('d/m/Y', strtotime($_SESSION['created_at'] ?? 'now')) ?></p>
        </div>
    </div>
    
    <div class="stats-section">
        <h2>Mes Statistiques</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Parties jouées</h3>
                <p><?= $_SESSION['games_played'] ?? 0 ?></p>
            </div>
            <div class="stat-card">
                <h3>Taux de réussite</h3>
                <p><?= $_SESSION['win_rate'] ?? 0 ?>%</p>
            </div>
        </div>
    </div>
</div>

<?php 
$jsFiles = ['dashboard.js', 'chatbot.js'];
require_once __DIR__.'/../include/footer.php';
?>
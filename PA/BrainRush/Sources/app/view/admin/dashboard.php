<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$page_title = "Tableau de bord";
require_once 'includes/header_dashboard.php'; // Header spécial sans nav
?>

<link rel="stylesheet" href="assets/css/dashboard.css">

<div class="dashboard-container">
    <h1>Bienvenue sur votre tableau de bord</h1>
    <a href="logout.php" class="logout-btn">Déconnexion</a>
    
    <!-- Contenu du dashboard -->
</div>

<script src="assets/js/main.js"></script>
<script src="assets/js/dashboard.js"></script>

<?php require_once 'includes/footer.php'; ?>
<?php
session_start();
$page_title = "Accueil";
require_once __DIR__ . '/../app/core/router.php';
require_once 'includes/header.php';
?>

<!-- Contenu spécifique à la page d'accueil -->
<div class="content">
    <h1>Bienvenue sur notre site</h1>
    <p>Contenu de la page d'accueil...</p>
</div>

<script src="assets/js/main.js"></script>
<script src="assets/js/index.js"></script>

<?php require_once 'includes/footer.php'; ?>
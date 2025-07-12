<?php
require_once __DIR__.'/../../traitement/Controllers/AdminController.php';
session_start();
AdminController::requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $bdd = Connexion::getInstance();
    $req = $bdd->prepare("DELETE FROM publications WHERE id = ?");
    $req->execute([$_POST['post_id']]);
    AdminController::logAction("Suppression de la publication ID " . $_POST['post_id']);
}

header("Location: dashboard.php");
exit;
?>
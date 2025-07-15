<?php
require_once __DIR__.'/../../traitement/Controllers/AdminController.php';
session_start();
AdminController::requireAdmin();

$bdd = Connexion::getInstance();
$timeout = date('Y-m-d H:i:s', strtotime('-5 minutes'));
$visitors = $bdd->query("SELECT COUNT(*) FROM utilisateurs WHERE last_activity > '$timeout'")->fetchColumn();

echo $visitors;
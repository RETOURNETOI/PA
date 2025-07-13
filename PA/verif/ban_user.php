<?php
require_once __DIR__.'/../../traitement/Controllers/AdminController.php';
session_start();
AdminController::requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];
    
    if (isset($_POST['ban_1d'])) {
        AdminController::banUser($userId, '+1 day');
    } elseif (isset($_POST['ban_7d'])) {
        AdminController::banUser($userId, '+7 days');
    } elseif (isset($_POST['ban_permanent'])) {
        AdminController::banUser($userId);
    }
    
    header("Location: dashboard.php");
    exit;
}

header("Location: dashboard.php");
exit;
?>
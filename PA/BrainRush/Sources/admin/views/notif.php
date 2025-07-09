<?php
require 'AdminController.php';
AdminController::requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
  $stmt = $pdo->prepare("INSERT INTO notifications (type, data) VALUES (?, ?)");
  $stmt->execute([$_POST['type'], $_POST['data']]);
  AdminController::logAction("Ajout d'une notification ({$_POST['type']})");
  header("Location: dashboard.php");
  exit;
}
header("Location: dashboard.php");
exit;

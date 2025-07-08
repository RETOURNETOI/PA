<?php
require 'AdminController.php';
AdminController::requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark'])) {
  AdminController::markNotificationRead($_POST['id']);
}
header("Location: dashboard.php");
exit;

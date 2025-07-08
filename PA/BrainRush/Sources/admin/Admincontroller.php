<?php
session_start();
require_once __DIR__.'/../config/db.php';

class AdminController {
  public static function requireAdmin() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
      header("Location: /login.php");
      exit;
    }
  }

  public static function listUsers() {
    global $pdo;
    return $pdo->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function createUser($data) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$data['username'], $data['email'], password_hash($data['password'], PASSWORD_DEFAULT)]);
  }

  public static function updateUser($id, $data) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET username=?, email=? WHERE id=?");
    $stmt->execute([$data['username'], $data['email'], $id]);
  }

  public static function deleteUser($id) {
    global $pdo;
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
  }

  public static function getPendingPhotos() {
    global $pdo;
    return $pdo->query("SELECT id, username, photo FROM users WHERE is_approved = 0 AND photo IS NOT NULL")->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function approvePhoto($id) {
    global $pdo;
    $pdo->prepare("UPDATE users SET is_approved = 1 WHERE id = ?")->execute([$id]);
  }

  public static function rejectPhoto($id) {
    global $pdo;
    $u = $pdo->prepare("SELECT photo FROM users WHERE id=?")->execute([$id]);
    $file = $pdo->query("SELECT photo FROM users WHERE id=$id")->fetch(PDO::FETCH_ASSOC)['photo'];
    if ($file && file_exists(__DIR__.'/../uploads/'.$file)) unlink(__DIR__.'/../uploads/'.$file);
    $pdo->prepare("UPDATE users SET photo = NULL WHERE id = ?")->execute([$id]);
  }

  public static function getNotifications() {
    global $pdo;
    return $pdo->query("SELECT * FROM notifications_admin WHERE read_flag = 0 ORDER BY date DESC")->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function markNotificationRead($id) {
    global $pdo;
    $pdo->prepare("UPDATE notifications_admin SET read_flag = 1 WHERE id = ?")->execute([$id]);
  }

  public static function getUserCount() {
    global $pdo;
    return $pdo->query("SELECT COUNT(*) AS total FROM users")->fetch(PDO::FETCH_ASSOC)['total'];
  }

  public static function getLiveVisitors() {
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM active_visitors WHERE last_ping > (NOW() - INTERVAL 1 MINUTE)");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
  }
}

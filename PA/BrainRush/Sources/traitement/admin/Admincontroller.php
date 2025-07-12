<?php
require_once '../traitement/Bdd/connexion.php';
session_start();

class AdminController {
  
  public static function requireAdmin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
      header("Location: /login.php");
      exit;
    }
  }

  public static function listUsers() {
    global $pdo;
    $stmt = $pdo->query("SELECT id, username, email, role, banned_until FROM users");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function createUser($data) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    $role = in_array($data['role'], ['admin', 'user']) ? $data['role'] : 'user';
    $stmt->execute([
      $data['username'],
      $data['email'],
      $hashedPassword,
      $role
    ]);
    self::logAction("Création de l'utilisateur " . $data['email']);
  }

  public static function updateUser($id, $data) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
    $role = in_array($data['role'], ['admin', 'user']) ? $data['role'] : 'user';
    $stmt->execute([
      $data['username'],
      $data['email'],
      $role,
      $id
    ]);
    self::logAction("Modification de l'utilisateur ID " . $id);
  }

  public static function deleteUser($id) {
    global $pdo;
    if ($_SESSION['user_id'] == $id) {
      return;
    }
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    self::logAction("Suppression de l'utilisateur ID " . $id);
  }

  public static function banUser($id, $duration = null) {
    global $pdo;
    $until = $duration ? date('Y-m-d H:i:s', strtotime($duration)) : null;
    $stmt = $pdo->prepare("UPDATE users SET banned_until = ? WHERE id = ?");
    $stmt->execute([$until, $id]);
    self::logAction("Bannissement de l'utilisateur ID " . $id . ($duration ? " pour $duration" : " définitivement"));
  }

  public static function unbanUser($id) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET banned_until = NULL WHERE id = ?");
    $stmt->execute([$id]);
    self::logAction("Débannissement de l'utilisateur ID " . $id);
  }

  public static function getAdminCount() {
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'");
    return $stmt->fetchColumn();
  }

  public static function getUserCount() {
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    return $stmt->fetchColumn();
  }

  public static function getTotalVisits() {
    global $pdo;
    $stmt = $pdo->query("SELECT SUM(visit_count) FROM users");
    return $stmt->fetchColumn() ?? 0;
  }

  public static function getLiveVisitors() {
    global $pdo;
    $timeout = date('Y-m-d H:i:s', strtotime('-5 minutes'));
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE last_activity > '$timeout'");
    return $stmt->fetchColumn();
  }

  public static function getFlaggedMessages() {
    global $pdo;
    $forbiddenWords = require 'forbidden_words.php';
    $wordsPattern = implode('|', array_map('preg_quote', $forbiddenWords));
    
    $stmt = $pdo->query("SELECT m.id, m.content, m.created_at, u.username 
                         FROM messages m 
                         JOIN users u ON m.user_id = u.id 
                         WHERE m.content REGEXP '$wordsPattern'
                         ORDER BY m.created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function logAction($action) {
    global $pdo;
    if (!isset($_SESSION['user_id'])) return;
    $stmt = $pdo->prepare("INSERT INTO admin_logs (admin_id, action) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $action]);
  }
}
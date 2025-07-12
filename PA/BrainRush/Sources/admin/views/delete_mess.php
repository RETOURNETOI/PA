<?php
require_once __DIR__.'/../Bdd/connexion.php';

class AdminController {
  
  public static function requireAdmin() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
      header("Location: /connexion");
      exit;
    }
  }

  public static function listUsers() {
    $bdd = Connexion::getInstance();
    $req = $bdd->prepare("SELECT id, pseudo, email, role, banned_until FROM utilisateurs");
    $req->execute();
    return $req->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function createUser($data) {
    $bdd = Connexion::getInstance();
    $req = $bdd->prepare("INSERT INTO utilisateurs (pseudo, email, mdp, role) VALUES (?, ?, ?, ?)");
    $hashedPassword = password_hash($data['mdp'], PASSWORD_DEFAULT);
    $role = in_array($data['role'], ['admin', 'user']) ? $data['role'] : 'user';
    $req->execute([
      $data['pseudo'],
      $data['email'],
      $hashedPassword,
      $role
    ]);
    self::logAction("Création de l'utilisateur " . $data['email']);
  }

  public static function updateUser($id, $data) {
    $bdd = Connexion::getInstance();
    $req = $bdd->prepare("UPDATE utilisateurs SET pseudo = ?, email = ?, role = ? WHERE id = ?");
    $role = in_array($data['role'], ['admin', 'user']) ? $data['role'] : 'user';
    $req->execute([
      $data['pseudo'],
      $data['email'],
      $role,
      $id
    ]);
    self::logAction("Modification de l'utilisateur ID " . $id);
  }

  public static function deleteUser($id) {
    $bdd = Connexion::getInstance();
    if ($_SESSION['user']['id'] == $id) {
      return;
    }
    $req = $bdd->prepare("DELETE FROM utilisateurs WHERE id = ?");
    $req->execute([$id]);
    self::logAction("Suppression de l'utilisateur ID " . $id);
  }

  public static function banUser($id, $duration = null) {
    $bdd = Connexion::getInstance();
    $until = $duration ? date('Y-m-d H:i:s', strtotime($duration)) : null;
    $req = $bdd->prepare("UPDATE utilisateurs SET banned_until = ? WHERE id = ?");
    $req->execute([$until, $id]);
    self::logAction("Bannissement de l'utilisateur ID " . $id . ($duration ? " pour $duration" : " définitivement"));
  }

  public static function unbanUser($id) {
    $bdd = Connexion::getInstance();
    $req = $bdd->prepare("UPDATE utilisateurs SET banned_until = NULL WHERE id = ?");
    $req->execute([$id]);
    self::logAction("Débannissement de l'utilisateur ID " . $id);
  }

  public static function getAdminCount() {
    $bdd = Connexion::getInstance();
    $req = $bdd->query("SELECT COUNT(*) FROM utilisateurs WHERE role = 'admin'");
    return $req->fetchColumn();
  }

  public static function getUserCount() {
    $bdd = Connexion::getInstance();
    $req = $bdd->query("SELECT COUNT(*) FROM utilisateurs");
    return $req->fetchColumn();
  }

  public static function getLiveVisitors() {
    $bdd = Connexion::getInstance();
    $timeout = date('Y-m-d H:i:s', strtotime('-5 minutes'));
    $req = $bdd->query("SELECT COUNT(*) FROM utilisateurs WHERE last_activity > '$timeout'");
    return $req->fetchColumn();
  }

  public static function getFlaggedPosts() {
    $bdd = Connexion::getInstance();
    $forbiddenWords = require __DIR__.'/../forbidden_words.php';
    $wordsPattern = implode('|', array_map('preg_quote', $forbiddenWords));
    
    $req = $bdd->query("SELECT p.id, p.contenu, p.date_creation, u.pseudo 
                         FROM publications p 
                         JOIN utilisateurs u ON p.id_utilisateur = u.id 
                         WHERE p.contenu REGEXP '$wordsPattern'
                         ORDER BY p.date_creation DESC");
    return $req->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function logAction($action) {
    if (!isset($_SESSION['user'])) return;
    $bdd = Connexion::getInstance();
    $req = $bdd->prepare("INSERT INTO admin_logs (admin_id, action) VALUES (?, ?)");
    $req->execute([$_SESSION['user']['id'], $action]);
  }
}
<?php
require_once __DIR__.'/../Bdd/connexion.php';

class AdminController {
  
  public static function requireAdmin() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
      header("Location: /connexion");
      exit;
    }
  }

  public static function getDashboardData() {
    $bdd = Connexion::getInstance();
    
    // Statistiques
    $data['users'] = $bdd->query("SELECT COUNT(*) FROM utilisateurs")->fetchColumn();
    $data['admins'] = $bdd->query("SELECT COUNT(*) FROM utilisateurs WHERE role = 'admin'")->fetchColumn();
    
    // Visiteurs en temps réel
    $timeout = date('Y-m-d H:i:s', strtotime('-5 minutes'));
    $data['visitors'] = $bdd->query("SELECT COUNT(*) FROM utilisateurs WHERE last_activity > '$timeout'")->fetchColumn();
    
    // Publications signalées
    $forbiddenWords = require __DIR__.'/../forbidden_words.php';
    $wordsPattern = implode('|', array_map('preg_quote', $forbiddenWords));
    $data['flaggedPosts'] = $bdd->query("SELECT p.id, p.contenu, p.date_creation, u.pseudo, u.id as user_id 
                                        FROM publications p 
                                        JOIN utilisateurs u ON p.id_utilisateur = u.id 
                                        WHERE p.contenu REGEXP '$wordsPattern'
                                        ORDER BY p.date_creation DESC")
                               ->fetchAll(PDO::FETCH_ASSOC);
    
    // Logs admin
    $data['logs'] = $bdd->query("SELECT l.action, l.created_at, u.pseudo
                                FROM admin_logs l
                                JOIN utilisateurs u ON u.id = l.admin_id
                                ORDER BY l.created_at DESC
                                LIMIT 10")
                       ->fetchAll(PDO::FETCH_ASSOC);
    
    return $data;
  }

  public static function handleActions() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['ban_user'])) {
        self::banUser($_POST['user_id'], $_POST['duration']);
      }
      elseif (isset($_POST['delete_post'])) {
        self::deletePost($_POST['post_id']);
      }
      header("Location: /admin/dashboard");
      exit;
    }
  }

  private static function banUser($userId, $duration) {
    $bdd = Connexion::getInstance();
    $until = $duration !== 'permanent' ? date('Y-m-d H:i:s', strtotime($duration)) : null;
    $req = $bdd->prepare("UPDATE utilisateurs SET banned_until = ? WHERE id = ?");
    $req->execute([$until, $userId]);
    self::logAction("Bannissement de l'utilisateur ID $userId ($duration)");
  }

  private static function deletePost($postId) {
    $bdd = Connexion::getInstance();
    $req = $bdd->prepare("DELETE FROM publications WHERE id = ?");
    $req->execute([$postId]);
    self::logAction("Suppression de la publication ID $postId");
  }

  private static function logAction($action) {
    if (!isset($_SESSION['user'])) return;
    $bdd = Connexion::getInstance();
    $req = $bdd->prepare("INSERT INTO admin_logs (admin_id, action) VALUES (?, ?)");
    $req->execute([$_SESSION['user']['id'], $action]);
  }
}
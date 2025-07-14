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
    $data['visitors'] = self::getLiveVisitors();
    $data['total_visits'] = $bdd->query("SELECT SUM(visites) FROM utilisateurs")->fetchColumn();
    
    // Publications signalées
    $data['flaggedPosts'] = self::getFlaggedPosts();
    
    // Logs admin
    $data['logs'] = $bdd->query("SELECT l.action, l.created_at, u.pseudo
                                FROM admin_logs l
                                JOIN utilisateurs u ON u.id = l.admin_id
                                ORDER BY l.created_at DESC
                                LIMIT 10")
                       ->fetchAll(PDO::FETCH_ASSOC);
    
    return $data;
  }

  private static function getLiveVisitors() {
    $bdd = Connexion::getInstance();
    $timeout = date('Y-m-d H:i:s', strtotime('-5 minutes'));
    return $bdd->query("SELECT COUNT(*) FROM utilisateurs WHERE last_activity > '$timeout'")->fetchColumn();
  }

  private static function getFlaggedPosts() {
    $bdd = Connexion::getInstance();
    $forbiddenWords = require __DIR__.'/../forbidden_words.php';
    $wordsPattern = implode('|', array_map('preg_quote', $forbiddenWords));
    
    return $bdd->query("SELECT p.id, p.contenu, p.date_creation, u.pseudo, u.id as user_id 
                       FROM publications p 
                       JOIN utilisateurs u ON p.id_utilisateur = u.id 
                       WHERE p.contenu REGEXP '$wordsPattern'
                       ORDER BY p.date_creation DESC")
              ->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function handleActions() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $bdd = Connexion::getInstance();
      
      if (isset($_POST['ban_user'])) {
        $until = $_POST['duration'] !== 'permanent' 
               ? date('Y-m-d H:i:s', strtotime($_POST['duration'])) 
               : null;
        $req = $bdd->prepare("UPDATE utilisateurs SET banned_until = ? WHERE id = ?");
        $req->execute([$until, $_POST['user_id']]);
        self::logAction("Bannissement utilisateur ID ".$_POST['user_id']);
      }
      elseif (isset($_POST['delete_post'])) {
        $req = $bdd->prepare("DELETE FROM publications WHERE id = ?");
        $req->execute([$_POST['post_id']]);
        self::logAction("Suppression publication ID ".$_POST['post_id']);
      }
      
      header("Location: /admin/dashboard");
      exit;
    }
  }

  private static function logAction($action) {
    if (!isset($_SESSION['user'])) return;
    $bdd = Connexion::getInstance();
    $req = $bdd->prepare("INSERT INTO admin_logs (admin_id, action) VALUES (?, ?)");
    $req->execute([$_SESSION['user']['id'], $action]);
  }
}

function showDashboard() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /login");
        exit;
    }
    require_once __DIR__ . '/../view/admin/dashboard.php';
}

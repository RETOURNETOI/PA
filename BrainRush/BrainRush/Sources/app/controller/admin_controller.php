<?php
require_once __DIR__.'/../models/user_model.php';
require_once __DIR__.'/../models/report_model.php';
require_once __DIR__.'/../core/database.php';

class AdminController {
    private $userModel;
    private $reportModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->reportModel = new ReportModel();
    }

    public static function requireAdmin() {
        session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: /BrainRush/BrainRush/auth/login");
            exit;
        }
    }

    public function dashboard() {
        self::requireAdmin();
        
        $data = [
            'total_users' => $this->userModel->countAllUsers(),
            'active_users' => $this->userModel->countActiveUsers(),
            'today_visits' => $this->getTodayVisits(),
            'reports' => $this->getPendingReports()
        ];

        require_once __DIR__.'/../../admin/views/dashboard.php';
    }

    public function manageUsers() {
        self::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleUserAction();
            return;
        }

        $users = $this->userModel->getAllUsers();
        require_once __DIR__.'/../../admin/views/user.php';
    }

    public function manageReports() {
        self::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $reportId = intval($_POST['report_id'] ?? 0);
            
            if ($reportId > 0) {
                $this->reportModel->resolveReport($reportId, $action, $_SESSION['user_id']);
                $this->logAction("Traitement signalement ID $reportId : $action");
            }
            
            header("Location: /BrainRush/BrainRush/admin/reports");
            exit;
        }

        $reports = $this->reportModel->getPendingReports();
        require_once __DIR__.'/../../admin/views/reports.php';
    }

    private function handleUserAction() {
        session_start(); // Nécessaire pour accéder à $_SESSION

        $action = $_POST['action'] ?? '';
        $userId = intval($_POST['user_id'] ?? 0);

        if ($userId <= 0) return;

        switch ($action) {
            case 'ban':
                $duration = $_POST['duration'] ?? '1 day';
                self::banUser($userId, $duration);
                $this->logAction("Bannissement utilisateur ID $userId pour $duration");
                break;

            case 'unban':
                self::unbanUser($userId); // ✅ Correction ici
                $this->logAction("Débannissement utilisateur ID $userId");
                break;

            case 'delete':
                if ($userId !== $_SESSION['user_id']) {
                    self::deleteUser($userId);
                    $this->logAction("Suppression utilisateur ID $userId");
                }
                break;
        }

        header("Location: /BrainRush/BrainRush/admin/users");
        exit;
    }

    private function getTodayVisits() {
        $pdo = Connexion::getInstance();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE DATE(last_activity) = CURDATE()");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    private function getPendingReports() {
        return $this->reportModel->getPendingReports();
    }

    private function logAction($action) {
        if (!isset($_SESSION['user_id'])) return;
        
        $pdo = Connexion::getInstance();
        $stmt = $pdo->prepare("INSERT INTO admin_logs (admin_id, action, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$_SESSION['user_id'], $action]);
    }

    public static function listUsers() {
        $pdo = Connexion::getInstance();
        $stmt = $pdo->query("SELECT id, pseudo, email, role, banned_until, created_at FROM utilisateurs ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function banUser($userId, $duration) {
        $pdo = Connexion::getInstance();
        $until = $duration === 'permanent' ? '2099-12-31 23:59:59' : date('Y-m-d H:i:s', strtotime($duration));
        $stmt = $pdo->prepare("UPDATE utilisateurs SET banned_until = ? WHERE id = ?");
        return $stmt->execute([$until, $userId]);
    }

    public static function unbanUser($userId) {
        $pdo = Connexion::getInstance();
        $stmt = $pdo->prepare("UPDATE utilisateurs SET banned_until = NULL WHERE id = ?");
        return $stmt->execute([$userId]);
    }

    public static function deleteUser($userId) { //erreur avec deleteUser
        $pdo = Connexion::getInstance();
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
        return $stmt->execute([$userId]);
    }

    public static function getUserCount() {
        $pdo = Connexion::getInstance();
        $stmt = $pdo->query("SELECT COUNT(*) FROM utilisateurs");
        return $stmt->fetchColumn();
    }

    public static function getLiveVisitors() {
        $pdo = Connexion::getInstance();
        $timeout = date('Y-m-d H:i:s', strtotime('-5 minutes'));
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE last_activity > ?");
        $stmt->execute([$timeout]);
        return $stmt->fetchColumn();
    }

    public static function getPendingPhotos() {
        $pdo = Connexion::getInstance();
        $stmt = $pdo->query("SELECT id, pseudo as username, photo FROM utilisateurs WHERE photo_status = 'pending'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function approvePhoto($id) {
        $pdo = Connexion::getInstance();
        $stmt = $pdo->prepare("UPDATE utilisateurs SET photo_status = 'approved' WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function rejectPhoto($id) {
        $pdo = Connexion::getInstance();
        $stmt = $pdo->prepare("UPDATE utilisateurs SET photo_status = 'rejected', photo = NULL WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
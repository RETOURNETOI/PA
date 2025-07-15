<?php
require_once __DIR__.'/../core/database.php';

class UserModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Connexion::getInstance();
    }

    public function createUser($email, $password, $username = null) {
        $stmt = $this->pdo->prepare("INSERT INTO utilisateurs (email, mdp, pseudo, role, created_at) VALUES (?, ?, ?, 'user', NOW())");
        return $stmt->execute([$email, $password, $username ?? 'User']);
    }

    public function getUserByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function emailExists($email) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    public function updateUserActivity($userId) {
        $stmt = $this->pdo->prepare("UPDATE utilisateurs SET last_activity = NOW() WHERE id = ?");
        $stmt->execute([$userId]);
    }

    public function countAllUsers() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM utilisateurs");
        return $stmt->fetchColumn();
    }

    public function countActiveUsers() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE banned_until IS NULL OR banned_until < NOW()");
        return $stmt->fetchColumn();
    }

    public function getAllUsers() {
        $stmt = $this->pdo->query("SELECT id, pseudo, email, role, created_at, banned_until FROM utilisateurs ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function banUser($userId, $duration = null) {
        $until = $duration ? date('Y-m-d H:i:s', strtotime($duration)) : '2099-12-31 23:59:59';
        $stmt = $this->pdo->prepare("UPDATE utilisateurs SET banned_until = ? WHERE id = ?");
        return $stmt->execute([$until, $userId]);
    }

    public function unbanUser($userId) {
        $stmt = $this->pdo->prepare("UPDATE utilisateurs SET banned_until = NULL WHERE id = ?");
        return $stmt->execute([$userId]);
    }

    public function isUserBanned($userId) {
        $stmt = $this->pdo->prepare("SELECT banned_until FROM utilisateurs WHERE id = ?");
        $stmt->execute([$userId]);
        $bannedUntil = $stmt->fetchColumn();
        
        if (!$bannedUntil) return false;
        return strtotime($bannedUntil) > time();
    }

    public function createPasswordReset($email, $token, $expires) {
        $stmt = $this->pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        return $stmt->execute([$email, $token, $expires]);
    }

    public function validateResetToken($token) {
        $stmt = $this->pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePasswordWithToken($token, $hashedPassword) {
        $resetData = $this->validateResetToken($token);
        
        if (!$resetData) {
            return false;
        }

        $stmt = $this->pdo->prepare("UPDATE utilisateurs SET mdp = ? WHERE email = ?");
        $success = $stmt->execute([$hashedPassword, $resetData['email']]);

        if ($success) {
            $stmt = $this->pdo->prepare("DELETE FROM password_resets WHERE token = ?");
            $stmt->execute([$token]);
        }

        return $success;
    }
}
?>
<?php
// Sources/app/models/user_model.php
require_once __DIR__.'/../core/database.php';

class UserModel extends Database {
    public function countAllUsers() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users");
        return $stmt->fetchColumn();
    }

    public function countActiveUsers() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users WHERE banned = 0");
        return $stmt->fetchColumn();
    }

    public function getAllUsers() {
        $stmt = $this->pdo->query("SELECT id, username, email, created_at, banned FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function banUser($userId) {
        $stmt = $this->pdo->prepare("UPDATE users SET banned = 1 WHERE id = ?");
        $stmt->execute([$userId]);
        
        // Envoyer un email (simplifié)
        $user = $this->getUserById($userId);
        $to = $user['email'];
        $subject = "Compte suspendu";
        $message = "Votre compte a été suspendu par un administrateur.";
        mail($to, $subject, $message);
    }

    private function getUserById($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
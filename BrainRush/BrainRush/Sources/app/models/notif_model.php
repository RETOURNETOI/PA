<?php
// Sources/app/models/notification_model.php
require_once __DIR__.'/../core/database.php';

class NotificationModel extends Database {
    public function getUnreadCount($userId) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM notifications 
            WHERE user_id = ? AND is_read = 0
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public function getNotifications($userId, $limit = 5) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM notifications 
            WHERE user_id = ? 
            ORDER BY created_at DESC 
            LIMIT ?
        ");
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAsRead($notificationId) {
        $stmt = $this->pdo->prepare("
            UPDATE notifications SET is_read = 1 
            WHERE id = ?
        ");
        $stmt->execute([$notificationId]);
    }
}
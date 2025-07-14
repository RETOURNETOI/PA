<?php
// Sources/app/models/chat_model.php
require_once __DIR__.'/../core/database.php';

class ChatModel extends Database {
    public function getMessages($userId, $lastId = 0) {
        $stmt = $this->pdo->prepare("
            SELECT m.*, u.username, u.avatar 
            FROM messages m
            JOIN users u ON m.sender_id = u.id
            WHERE m.id > ? AND (m.receiver_id = ? OR m.receiver_id IS NULL)
            ORDER BY m.created_at ASC
            LIMIT 50
        ");
        $stmt->execute([$lastId, $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function sendMessage($senderId, $receiverId, $message) {
        $stmt = $this->pdo->prepare("
            INSERT INTO messages (sender_id, receiver_id, content)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$senderId, $receiverId, $message]);
        return $this->pdo->lastInsertId();
    }
}
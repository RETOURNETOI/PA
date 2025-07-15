<?php
// Sources/app/models/forum_model.php
require_once __DIR__.'/../core/database.php';

class ForumModel extends Database {
    public function searchPosts($query) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, u.username 
            FROM posts p
            JOIN users u ON p.user_id = u.id
            WHERE p.title LIKE ? OR p.content LIKE ?
            ORDER BY p.created_at DESC
        ");
        $searchTerm = "%$query%";
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
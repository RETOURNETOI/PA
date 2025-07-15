<?php
require_once __DIR__.'/../core/database.php';

class ForumModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Connexion::getInstance();
    }

    public function getAllPosts($limit = 20, $offset = 0) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, u.pseudo as username, u.id as user_id,
                   COUNT(r.id) as reply_count,
                   COALESCE(rep.report_count, 0) as report_count
            FROM forum_posts p
            JOIN utilisateurs u ON p.user_id = u.id
            LEFT JOIN forum_replies r ON p.id = r.post_id
            LEFT JOIN (
                SELECT content_id, COUNT(*) as report_count 
                FROM reports 
                WHERE content_type = 'post' AND status = 'pending'
                GROUP BY content_id
            ) rep ON p.id = rep.content_id
            WHERE p.is_deleted = 0
            GROUP BY p.id
            ORDER BY p.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createPost($userId, $title, $content) {
        $stmt = $this->pdo->prepare("
            INSERT INTO forum_posts (user_id, title, content, created_at) 
            VALUES (?, ?, ?, NOW())
        ");
        return $stmt->execute([$userId, $title, $content]);
    }

    public function getPostById($id) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, u.pseudo as username
            FROM forum_posts p
            JOIN utilisateurs u ON p.user_id = u.id
            WHERE p.id = ? AND p.is_deleted = 0
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getReplies($postId) {
        $stmt = $this->pdo->prepare("
            SELECT r.*, u.pseudo as username
            FROM forum_replies r
            JOIN utilisateurs u ON r.user_id = u.id
            WHERE r.post_id = ? AND r.is_deleted = 0
            ORDER BY r.created_at ASC
        ");
        $stmt->execute([$postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addReply($postId, $userId, $content) {
        $stmt = $this->pdo->prepare("
            INSERT INTO forum_replies (post_id, user_id, content, created_at)
            VALUES (?, ?, ?, NOW())
        ");
        return $stmt->execute([$postId, $userId, $content]);
    }

    public function searchPosts($query, $limit = 20) {
        $searchTerm = "%$query%";
        $stmt = $this->pdo->prepare("
            SELECT p.*, u.pseudo as username,
                   COUNT(r.id) as reply_count
            FROM forum_posts p
            JOIN utilisateurs u ON p.user_id = u.id
            LEFT JOIN forum_replies r ON p.id = r.post_id
            WHERE (p.title LIKE ? OR p.content LIKE ?) 
            AND p.is_deleted = 0
            GROUP BY p.id
            ORDER BY p.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$searchTerm, $searchTerm, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deletePost($id) {
        $stmt = $this->pdo->prepare("UPDATE forum_posts SET is_deleted = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function deleteReply($id) {
        $stmt = $this->pdo->prepare("UPDATE forum_replies SET is_deleted = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function checkForBannedWords($content) {
        $bannedWords = ['spam', 'hack', 'cheat', 'bot', 'idiot', 'connard', 'salope'];
        $content = strtolower($content);
        
        foreach ($bannedWords as $word) {
            if (strpos($content, $word) !== false) {
                return $word;
            }
        }
        return false;
    }
}
?>
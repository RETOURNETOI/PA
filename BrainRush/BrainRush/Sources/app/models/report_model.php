<?php
require_once __DIR__.'/../core/database.php';

class ReportModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Connexion::getInstance();
    }

    public function createReport($userId, $contentType, $contentId, $reason, $isAuto = false) {
        $stmt = $this->pdo->prepare("
            INSERT INTO reports (user_id, content_type, content_id, reason, status, is_auto, created_at)
            VALUES (?, ?, ?, ?, 'pending', ?, NOW())
        ");
        return $stmt->execute([$userId, $contentType, $contentId, $reason, $isAuto ? 1 : 0]);
    }

    public function getPendingReports() {
        $stmt = $this->pdo->prepare("
            SELECT r.*, 
                   CASE 
                       WHEN r.content_type = 'post' THEN fp.title
                       WHEN r.content_type = 'reply' THEN fr.content
                       WHEN r.content_type = 'user' THEN u.pseudo
                   END as content_preview,
                   reporter.pseudo as reporter_name
            FROM reports r
            LEFT JOIN forum_posts fp ON r.content_type = 'post' AND r.content_id = fp.id
            LEFT JOIN forum_replies fr ON r.content_type = 'reply' AND r.content_id = fr.id
            LEFT JOIN utilisateurs u ON r.content_type = 'user' AND r.content_id = u.id
            LEFT JOIN utilisateurs reporter ON r.user_id = reporter.id
            WHERE r.status = 'pending'
            ORDER BY r.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function resolveReport($reportId, $action, $adminId) {
        $status = ($action === 'ban') ? 'resolved_banned' : 'resolved_ignored';
        
        $stmt = $this->pdo->prepare("
            UPDATE reports 
            SET status = ?, resolved_by = ?, resolved_at = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([$status, $adminId, $reportId]);
    }

    public function getReportById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM reports WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function autoCheckContent($content, $userId, $contentType, $contentId) {
        $bannedWords = [
            'spam', 'hack', 'cheat', 'bot', 'connard', 'salope', 'idiot', 
            'fdp', 'pute', 'merde', 'putin', 'con', 'debile'
        ];
        
        $contentLower = strtolower($content);
        
        foreach ($bannedWords as $word) {
            if (strpos($contentLower, $word) !== false) {
                $this->createReport(
                    null,
                    $contentType,
                    $contentId,
                    "Mot interdit détecté: $word",
                    true
                );
                return true;
            }
        }
        return false;
    }

    public function getReportStats() {
        $stmt = $this->pdo->query("
            SELECT 
                COUNT(*) as total_reports,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_reports,
                SUM(CASE WHEN is_auto = 1 THEN 1 ELSE 0 END) as auto_reports
            FROM reports
        ");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
<?php
// Sources/app/models/report_model.php
require_once __DIR__.'/../core/database.php';

class ReportModel extends Database {
    private $bannedWords = ['insulte1', 'insulte2', 'motinterdit']; // À compléter

    public function getPendingReports() {
        $stmt = $this->pdo->query("SELECT * FROM reports WHERE status = 'pending'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createReport($contentType, $contentId, $reason) {
        $stmt = $this->pdo->prepare("INSERT INTO reports (content_type, content_id, reason, status) VALUES (?, ?, ?, 'pending')");
        $stmt->execute([$contentType, $contentId, $reason]);
    }

    public function resolveReport($reportId, $action) {
        $stmt = $this->pdo->prepare("UPDATE reports SET status = ? WHERE id = ?");
        $stmt->execute([$action === 'ban' ? 'resolved_banned' : 'resolved_ignored', $reportId]);
    }

    public function checkForBannedWords($text) {
        foreach ($this->bannedWords as $word) {
            if (stripos($text, $word) !== false) {
                return true;
            }
        }
        return false;
    }

    public function autoReportContent($contentType, $contentId, $content) {
        if ($this->checkForBannedWords($content)) {
            $this->createReport($contentType, $contentId, 'Auto-report: banned word detected');
        }
    }
}
?>
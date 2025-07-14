<?php
// Sources/app/models/leaderboard_model.php
require_once __DIR__.'/../core/database.php';

class LeaderboardModel extends Database {
    public function getTopPlayers($limit = 10) {
        $stmt = $this->pdo->prepare("
            SELECT u.username, l.score 
            FROM leaderboard l
            JOIN users u ON l.user_id = u.id
            ORDER BY l.score DESC 
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserPosition($userId) {
        $stmt = $this->pdo->prepare("
            SELECT position FROM (
                SELECT user_id, RANK() OVER (ORDER BY score DESC) as position
                FROM leaderboard
            ) ranked WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public function updateUserScore($userId, $score) {
        $stmt = $this->pdo->prepare("
            INSERT INTO leaderboard (user_id, score, last_played)
            VALUES (?, ?, NOW())
            ON DUPLICATE KEY UPDATE 
            score = GREATEST(score, VALUES(score)),
            last_played = NOW()
        ");
        $stmt->execute([$userId, $score]);
    }
}
?>
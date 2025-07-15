<?php
require_once __DIR__.'/../core/database.php';

class VSModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Connexion::getInstance();
    }

    public function createGame($userId, $roomCode) {
        $stmt = $this->pdo->prepare("
            INSERT INTO game_sessions (player1_id, room_code, status, created_at)
            VALUES (?, ?, 'waiting', NOW())
        ");
        
        if ($stmt->execute([$userId, $roomCode])) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    public function findGameByRoomCode($roomCode) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM game_sessions 
            WHERE room_code = ? AND status = 'waiting'
            ORDER BY created_at DESC 
            LIMIT 1
        ");
        $stmt->execute([$roomCode]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function joinGame($gameId, $userId) {
        $stmt = $this->pdo->prepare("
            UPDATE game_sessions 
            SET player2_id = ?, status = 'active'
            WHERE id = ? AND status = 'waiting'
        ");
        return $stmt->execute([$userId, $gameId]);
    }

    public function getGameById($gameId) {
        $stmt = $this->pdo->prepare("SELECT * FROM game_sessions WHERE id = ?");
        $stmt->execute([$gameId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRandomQuestion() {
        $stmt = $this->pdo->query("
            SELECT * FROM quiz_questions 
            ORDER BY RAND() 
            LIMIT 1
        ");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateScore($gameId, $userId, $points) {
        $game = $this->getGameById($gameId);
        
        if (!$game) return false;

        $column = ($game['player1_id'] == $userId) ? 'player1_score' : 'player2_score';
        
        $stmt = $this->pdo->prepare("
            UPDATE game_sessions 
            SET $column = COALESCE($column, 0) + ?
            WHERE id = ?
        ");
        
        $result = $stmt->execute([$points, $gameId]);
        
        $this->checkGameEnd($gameId);
        
        return $result;
    }

    public function getGameScores($gameId, $userId) {
        $game = $this->getGameById($gameId);
        
        if (!$game) {
            return ['your_score' => 0, 'opponent_score' => 0];
        }

        if ($game['player1_id'] == $userId) {
            return [
                'your_score' => $game['player1_score'] ?? 0,
                'opponent_score' => $game['player2_score'] ?? 0
            ];
        } else {
            return [
                'your_score' => $game['player2_score'] ?? 0,
                'opponent_score' => $game['player1_score'] ?? 0
            ];
        }
    }

    private function checkGameEnd($gameId) {
        $game = $this->getGameById($gameId);
        
        if (!$game) return;

        $score1 = $game['player1_score'] ?? 0;
        $score2 = $game['player2_score'] ?? 0;

        if ($score1 >= 100 || $score2 >= 100) {
            $winnerId = ($score1 > $score2) ? $game['player1_id'] : $game['player2_id'];
            
            $stmt = $this->pdo->prepare("
                UPDATE game_sessions 
                SET status = 'completed', winner_id = ?, completed_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$winnerId, $gameId]);

            $this->updateLeaderboard($game['player1_id'], $score1);
            $this->updateLeaderboard($game['player2_id'], $score2);
        }
    }

    private function updateLeaderboard($userId, $score) {
        $stmt = $this->pdo->prepare("
            INSERT INTO leaderboard (user_id, score, last_played)
            VALUES (?, ?, NOW())
            ON DUPLICATE KEY UPDATE 
            score = GREATEST(score, VALUES(score)),
            last_played = NOW()
        ");
        $stmt->execute([$userId, $score]);
    }

    public function cancelGame($gameId) {
        $stmt = $this->pdo->prepare("
            UPDATE game_sessions 
            SET status = 'cancelled'
            WHERE id = ? AND status = 'waiting'
        ");
        return $stmt->execute([$gameId]);
    }

    public function forfeitGame($gameId, $userId) {
        $game = $this->getGameById($gameId);
        
        if (!$game) return false;

        $winnerId = ($game['player1_id'] == $userId) ? $game['player2_id'] : $game['player1_id'];
        
        $stmt = $this->pdo->prepare("
            UPDATE game_sessions 
            SET status = 'completed', winner_id = ?, completed_at = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([$winnerId, $gameId]);
    }

    public function cleanupOldGames() {
        $stmt = $this->pdo->prepare("
            UPDATE game_sessions 
            SET status = 'expired'
            WHERE status = 'waiting' 
            AND created_at < DATE_SUB(NOW(), INTERVAL 10 MINUTE)
        ");
        return $stmt->execute();
    }
}
?>
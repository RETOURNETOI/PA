<?php
require_once __DIR__.'/../models/vs_model.php';

class VSController {
    private $vsModel;

    public function __construct() {
        $this->vsModel = new VSModel();
    }

    public function index() {
        require_once __DIR__.'/../../view/VS.php';
    }

    public function create() {
        session_start();
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Non connecté']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        
        if (($data['action'] ?? '') === 'create_room') {
            $roomCode = $this->generateRoomCode();
            $gameId = $this->vsModel->createGame($_SESSION['user_id'], $roomCode);
            
            if ($gameId) {
                echo json_encode([
                    'success' => true,
                    'game_id' => $gameId,
                    'room_code' => $roomCode
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur création']);
            }
        }
        exit;
    }

    public function join() {
        session_start();
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Non connecté']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        
        if (($data['action'] ?? '') === 'join_room') {
            $roomCode = $data['room_code'] ?? '';
            $game = $this->vsModel->findGameByRoomCode($roomCode);
            
            if (!$game) {
                echo json_encode(['success' => false, 'message' => 'Partie non trouvée']);
                exit;
            }

            if ($game['status'] !== 'waiting') {
                echo json_encode(['success' => false, 'message' => 'Partie déjà commencée']);
                exit;
            }

            if ($this->vsModel->joinGame($game['id'], $_SESSION['user_id'])) {
                echo json_encode([
                    'success' => true,
                    'game_id' => $game['id']
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur join']);
            }
        }
        exit;
    }

    public function status() {
        $gameId = $_GET['game_id'] ?? 0;
        
        if (!$gameId) {
            echo json_encode(['status' => 'error']);
            exit;
        }

        $game = $this->vsModel->getGameById($gameId);
        
        if ($game) {
            echo json_encode(['status' => $game['status']]);
        } else {
            echo json_encode(['status' => 'not_found']);
        }
        exit;
    }

    public function question() {
        $gameId = $_GET['game_id'] ?? 0;
        
        if (!$gameId) {
            echo json_encode(['success' => false]);
            exit;
        }

        $question = $this->vsModel->getRandomQuestion();
        
        if ($question) {
            echo json_encode([
                'success' => true,
                'question' => $question
            ]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }

    public function answer() {
        session_start();
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false]);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $gameId = $data['game_id'] ?? 0;
        $isCorrect = $data['is_correct'] ?? false;

        if ($isCorrect) {
            $this->vsModel->updateScore($gameId, $_SESSION['user_id'], 10);
        }

        echo json_encode(['success' => true]);
        exit;
    }

    public function gameState() {
        session_start();
        
        $gameId = $_GET['game_id'] ?? 0;
        
        if (!$gameId || !isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false]);
            exit;
        }

        $scores = $this->vsModel->getGameScores($gameId, $_SESSION['user_id']);
        $game = $this->vsModel->getGameById($gameId);

        echo json_encode([
            'success' => true,
            'your_score' => $scores['your_score'],
            'opponent_score' => $scores['opponent_score'],
            'status' => $game['status'],
            'winner_id' => $game['winner_id'] ?? null
        ]);
        exit;
    }

    public function cancel() {
        session_start();
        
        $data = json_decode(file_get_contents('php://input'), true);
        $gameId = $data['game_id'] ?? 0;

        if ($gameId && isset($_SESSION['user_id'])) {
            $this->vsModel->cancelGame($gameId);
        }

        echo json_encode(['success' => true]);
        exit;
    }

    public function forfeit() {
        session_start();
        
        $data = json_decode(file_get_contents('php://input'), true);
        $gameId = $data['game_id'] ?? 0;

        if ($gameId && isset($_SESSION['user_id'])) {
            $this->vsModel->forfeitGame($gameId, $_SESSION['user_id']);
        }

        echo json_encode(['success' => true]);
        exit;
    }

    private function generateRoomCode() {
        return strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
    }
}
?>
<?php
require_once __DIR__.'/../models/user_model.php';

class FrontController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->updateUserActivity();
    }

    private function updateUserActivity() {
        session_start();
        if (isset($_SESSION['user_id'])) {
            $this->userModel->updateUserActivity($_SESSION['user_id']);
        }
    }

    public function home() {
        require_once __DIR__.'/../../app/view/index.php';
    }

    public function quizzSolo() {
        $this->requireLogin();
        require_once __DIR__.'/../../app/view/quizz_solo.php';
    }

    public function vs() {
        $this->requireLogin();
        require_once __DIR__.'/../../app/view/VS.php';
    }

    public function classement() {
        $topPlayers = $this->getTopPlayers();
        require_once __DIR__.'/../../app/view/classement.php';
    }

    public function compte() {
        $this->requireLogin();
        require_once __DIR__.'/../../app/view/compte.php';
    }

    public function forum() {
        require_once __DIR__.'/../../app/view/forum.php';
    }

    public function notFound() {
        http_response_code(404);
        echo "<h1>404 - Page non trouvée</h1>";
    }

    private function requireLogin() {
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            header("Location: /auth/login");
            exit;
        }
    }

    private function getTopPlayers() {
        $pdo = Connexion::getInstance();
        $stmt = $pdo->query("
            SELECT u.id, u.pseudo as username, 
                   COALESCE(l.score, 0) as score,
                   COALESCE(l.last_played, u.created_at) as last_played,
                   'avatar_def1.png' as avatar
            FROM utilisateurs u 
            LEFT JOIN leaderboard l ON u.id = l.user_id 
            ORDER BY l.score DESC, u.created_at ASC 
            LIMIT 10
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
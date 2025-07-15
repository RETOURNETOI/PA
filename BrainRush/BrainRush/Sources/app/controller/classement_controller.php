<?php
// Sources/app/controller/leaderboard_controller.php
require_once __DIR__.'/../models/leaderboard_model.php';

class LeaderboardController {
    private $model;

    public function __construct() {
        $this->model = new LeaderboardModel();
    }

    public function showLeaderboard() {
        $topPlayers = $this->model->getTopPlayers(10);
        $userPosition = isset($_SESSION['user_id']) 
            ? $this->model->getUserPosition($_SESSION['user_id'])
            : null;
        
        require_once __DIR__.'/../../app/view/leaderboard.php';
    }

    public function updateScore($userId, $score) {
        $this->model->updateUserScore($userId, $score);
    }
}
?>
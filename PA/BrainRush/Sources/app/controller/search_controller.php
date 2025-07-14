<?php
// Sources/app/controller/search_controller.php
require_once __DIR__.'/../models/forum_model.php';

class SearchController {
    private $forumModel;

    public function __construct() {
        $this->forumModel = new ForumModel();
    }

    public function searchForum($query) {
        $results = $this->forumModel->searchPosts($query);
        
        header('Content-Type: application/json');
        echo json_encode($results);
    }
}
?>
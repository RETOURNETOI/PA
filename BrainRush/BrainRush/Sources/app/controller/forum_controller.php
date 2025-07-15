<?php
require_once __DIR__.'/../models/forum_model.php';
require_once __DIR__.'/../models/report_model.php';

class ForumController {
    private $forumModel;
    private $reportModel;

    public function __construct() {
        $this->forumModel = new ForumModel();
        $this->reportModel = new ReportModel();
    }

    public function index() {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $posts = $this->forumModel->getAllPosts($limit, $offset);
        $search = $_GET['search'] ?? '';

        if ($search) {
            $posts = $this->forumModel->searchPosts($search);
        }

        require_once __DIR__.'/../../view/forum.php';
    }

    public function createPost() {
        session_start();
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: /BrainRush/BrainRush/auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');

            if (empty($title) || empty($content)) {
                $_SESSION['error'] = "Titre et contenu requis";
                header('Location: /BrainRush/BrainRush/forum');
                exit;
            }

            if ($this->forumModel->createPost($_SESSION['user_id'], $title, $content)) {
                $pdo = Connexion::getInstance();
                $postId = $pdo->lastInsertId();

                if ($bannedWord = $this->forumModel->checkForBannedWords($content)) {
                    $this->reportModel->createReport(
                        null,
                        'post',
                        $postId,
                        "Mot interdit détecté: $bannedWord",
                        true
                    );
                }

                $_SESSION['message'] = "Post créé avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de la création";
            }
        }

        header('Location: /BrainRush/BrainRush/forum');
        exit;
    }

    public function viewPost() {
        $id = $_GET['id'] ?? 0;
        $post = $this->forumModel->getPostById($id);
        
        if (!$post) {
            header('Location: /BrainRush/BrainRush/forum');
            exit;
        }

        $replies = $this->forumModel->getReplies($id);
        require_once __DIR__.'/../../view/forum_post.php';
    }

    public function addReply() {
        session_start();
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: /BrainRush/BrainRush/auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postId = $_POST['post_id'] ?? 0;
            $content = trim($_POST['content'] ?? '');

            if (empty($content)) {
                $_SESSION['error'] = "Contenu requis";
            } else {
                if ($this->forumModel->addReply($postId, $_SESSION['user_id'], $content)) {
                    $pdo = Connexion::getInstance();
                    $replyId = $pdo->lastInsertId();

                    if ($bannedWord = $this->forumModel->checkForBannedWords($content)) {
                        $this->reportModel->createReport(
                            null,
                            'reply',
                            $replyId,
                            "Mot interdit détecté: $bannedWord",
                            true
                        );
                    }

                    $_SESSION['message'] = "Réponse ajoutée";
                } else {
                    $_SESSION['error'] = "Erreur lors de l'ajout";
                }
            }
        }

        $postId = $_POST['post_id'] ?? $_GET['post_id'] ?? 0;
        header("Location: /BrainRush/BrainRush/forum/post?id=$postId");
        exit;
    }

    public function reportContent() {
        session_start();
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Non connecté']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $contentType = $data['type'] ?? '';
        $contentId = $data['id'] ?? 0;
        $reason = $data['reason'] ?? 'Contenu inapproprié';

        if ($this->reportModel->createReport($_SESSION['user_id'], $contentType, $contentId, $reason)) {
            echo json_encode(['success' => true, 'message' => 'Signalement envoyé']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur']);
        }
        exit;
    }

    public function search() {
        $query = $_GET['q'] ?? '';
        
        if (empty($query)) {
            header('Location: /BrainRush/BrainRush/forum');
            exit;
        }

        $posts = $this->forumModel->searchPosts($query);
        $search = $query;
        
        require_once __DIR__.'/../../view/forum.php';
    }
}
?>
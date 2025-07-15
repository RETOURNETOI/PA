<?php
require_once __DIR__ . '/../controller/auth_controller.php';
require_once __DIR__ . '/../controller/admin_controller.php';
require_once __DIR__ . '/../controller/front_controller.php';
require_once __DIR__ . '/../controller/forum_controller.php';
require_once __DIR__ . '/../controller/vs_controller.php';
require_once __DIR__ . '/../controller/chat_controller.php';
require_once __DIR__ . '/../core/database.php';

class Router {
    public function __construct() {
        $this->route();
    }

    private function route() {
        $basePath = '/BrainRush/BrainRush/Sources';
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = substr($requestUri, strlen($basePath));
        $method = $_SERVER['REQUEST_METHOD'];

        if (preg_match('/\.(css|js|png|jpg|jpeg|gif|ico|svg)$/', $path)) {
            $this->serveAsset($path);
            return;
        }

        if (strpos($path, '/admin') === 0) {
            $this->handleAdmin($path, $method);
        } elseif (strpos($path, '/auth') === 0) {
            $this->handleAuth($path, $method);
        } elseif (strpos($path, '/forum') === 0) {
            $this->handleForum($path, $method);
        } elseif (strpos($path, '/vs') === 0) {
            $this->handleVS($path, $method);
        } elseif (strpos($path, '/chat') === 0) {
            $this->handleChat($path, $method);
        } elseif (strpos($path, '/api') === 0) {
            $this->handleAPI($path, $method);
        } else {
            $this->handleFront($path, $method);
        }
    }

    private function serveAsset($path) {
        $filePath = __DIR__ . '/../../public' . $path;
        if (file_exists($filePath)) {
            $mimeTypes = [
                'css' => 'text/css',
                'js' => 'application/javascript',
                'png' => 'image/png',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'ico' => 'image/x-icon',
                'svg' => 'image/svg+xml'
            ];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            if (isset($mimeTypes[$ext])) {
                header('Content-Type: ' . $mimeTypes[$ext]);
            }
            readfile($filePath);
        } else {
            http_response_code(404);
        }
        exit;
    }

    private function handleAdmin($path, $method) {
        $admin = new AdminController();

        switch ($path) {
            case '/admin':
            case '/admin/':
            case '/admin/dashboard':
                $admin->dashboard();
                break;
            case '/admin/users':
                $admin->manageUsers();
                break;
            case '/admin/moderation':
                require_once __DIR__ . '/../../admin/views/moderation.php';
                break;
            case '/admin/reports':
                $admin->manageReports();
                break;
            default:
                http_response_code(404);
                echo "Page admin non trouvée";
        }
    }

    private function handleAuth($path, $method) {
        $auth = new AuthController();

        switch ($path) {
            case '/auth/login':
                $method === 'GET' ? $auth->showLogin() : $auth->login();
                break;
            case '/auth/register':
                $method === 'GET' ? $auth->showRegister() : $auth->register();
                break;
            case '/auth/logout':
                $auth->logout();
                break;
            case '/auth/forgot-password':
                $auth->forgotPassword();
                break;
            case '/auth/reset-password':
                $auth->resetPassword();
                break;
            default:
                http_response_code(404);
                echo "Page auth non trouvée";
        }
    }

    private function handleForum($path, $method) {
        $forum = new ForumController();

        switch (true) {
            case $path === '/forum':
            case $path === '/forum/':
                $forum->index();
                break;
            case $path === '/forum/create':
                $forum->createPost();
                break;
            case $path === '/forum/post':
                $forum->viewPost();
                break;
            case $path === '/forum/reply':
                $forum->addReply();
                break;
            case $path === '/forum/report':
                $forum->reportContent();
                break;
            case $path === '/forum/search':
                $forum->search();
                break;
            default:
                http_response_code(404);
                echo "Page forum non trouvée";
        }
    }

    private function handleVS($path, $method) {
        $vs = new VSController();

        switch ($path) {
            case '/vs':
            case '/vs/':
                $vs->index();
                break;
            case '/vs/create':
                $vs->create();
                break;
            case '/vs/join':
                $vs->join();
                break;
            case '/vs/status':
                $vs->status();
                break;
            case '/vs/question':
                $vs->question();
                break;
            case '/vs/answer':
                $vs->answer();
                break;
            case '/vs/game-state':
                $vs->gameState();
                break;
            case '/vs/cancel':
                $vs->cancel();
                break;
            case '/vs/forfeit':
                $vs->forfeit();
                break;
            default:
                http_response_code(404);
                echo "Page VS non trouvée";
        }
    }

    private function handleChat($path, $method) {
        $chat = new ChatController();

        switch ($path) {
            case '/chat/messages':
                $chat->getNewMessages();
                break;
            case '/chat/send':
                $chat->sendMessage();
                break;
            default:
                http_response_code(404);
        }
    }

    private function handleAPI($path, $method) {
        switch ($path) {
            case '/api/admin/live-stats':
                $this->getLiveStats();
                break;
            case '/api/admin/reports':
                $this->getReports();
                break;
            case '/api/notifications/unread':
                $this->getUnreadNotifications();
                break;
            default:
                http_response_code(404);
                echo json_encode(['error' => 'API endpoint not found']);
        }
    }

    private function handleFront($path, $method) {
        $front = new FrontController();

        switch ($path) {
            case '':
            case '/':
                $front->home();
                break;
            case '/quizz_solo':
                $front->quizzSolo();
                break;
            case '/classement':
                $front->classement();
                break;
            case '/compte':
                $front->compte();
                break;
            default:
                $front->notFound();
        }
    }

    private function getLiveStats() {
        header('Content-Type: application/json');
        session_start();

        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $userCount = AdminController::getUserCount();
        $liveVisitors = AdminController::getLiveVisitors();

        $pdo = Connexion::getInstance();
        $stmt = $pdo->query("SELECT COUNT(*) FROM reports WHERE status = 'pending'");
        $pendingReports = $stmt->fetchColumn();

        echo json_encode([
            'user_count' => $userCount,
            'live_visitors' => $liveVisitors,
            'pending_reports' => $pendingReports,
            'timestamp' => time()
        ]);
        exit;
    }

    private function getReports() {
        header('Content-Type: application/json');
        session_start();

        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        require_once __DIR__ . '/../models/report_model.php';
        $reportModel = new ReportModel();
        $reports = $reportModel->getPendingReports();

        echo json_encode(['reports' => $reports]);
        exit;
    }

    private function getUnreadNotifications() {
        header('Content-Type: application/json');
        session_start();

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['count' => 0]);
            exit;
        }

        // TODO: Ajouter logique de récupération des notifications réelles
        echo json_encode(['count' => 0]);
        exit;
    }
}
?>

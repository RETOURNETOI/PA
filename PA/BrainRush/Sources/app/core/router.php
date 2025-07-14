<?php
// Sources/app/core/router.php
require_once __DIR__.'/../controller/admin_controller.php';
require_once __DIR__.'/../controller/auth_controller.php';
require_once __DIR__.'/../controller/front_controller.php';

class Router {
    public function __construct() {
        $this->route();
    }

    private function route() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // Routes Admin
        if (strpos($path, '/admin') === 0) {
            $admin = new AdminController();
            
            if ($path === '/admin/dashboard' && $method === 'GET') {
                $admin->dashboard();
            }
            elseif ($path === '/admin/users' && $method === 'GET') {
                $admin->manageUsers();
            }
            elseif (preg_match('/^\/admin\/ban\/(\d+)$/', $path, $matches) && $method === 'POST') {
                $admin->banUser($matches[1]);
            }
        }
        
        // Routes Auth
        elseif (strpos($path, '/auth') === 0) {
            $auth = new AuthController();
            
            if ($path === '/auth/login' && $method === 'GET') {
                $auth->showLogin();
            }
            // Ajouter ces routes dans la méthode route()
elseif ($path === '/search' && $method === 'GET') {
    $search = new SearchController();
    $search->searchForum($_GET['q']);
}
elseif ($path === '/chat/send' && $method === 'POST') {
    $chat = new ChatbotController();
    $chat->handleMessage($_SESSION['user_id'], $_POST['message']);
}
elseif ($path === '/auth/forgot-password') {
    $auth->forgotPassword();
}
elseif ($path === '/auth/reset-password') {
    $auth->resetPassword();
}
        }
    }
    // Routes chat
$router->add('/chat/messages', 'GET', 'ChatController', 'getNewMessages');
$router->add('/chat/send', 'POST', 'ChatController', 'sendMessage');

// Routes notifications
$router->add('/api/notifications/unread-count', 'GET', 'NotificationController', 'getUnreadCount');
$router->add('/api/notifications/list', 'GET', 'NotificationController', 'getNotifications');
$router->add('/api/notifications/mark-read', 'POST', 'NotificationController', 'markAsRead');
}
?>
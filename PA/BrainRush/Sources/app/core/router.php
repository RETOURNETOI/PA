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
            elseif ($path === '/admin/notifications' && $method === 'GET') {
                $admin->viewNotifications();
            }
        }
        
        // Routes Auth
        elseif (strpos($path, '/auth') === 0) {
            $auth = new AuthController();
            
            if ($path === '/auth/login' && $method === 'GET') {
                $auth->showLogin();
            }
            elseif ($path === '/auth/login' && $method === 'POST') {
                $auth->processLogin();
            }
            elseif ($path === '/auth/register' && $method === 'GET') {
                $auth->showRegister();
            }
            elseif ($path === '/auth/register' && $method === 'POST') {
                $auth->processRegister();
            }
            elseif ($path === '/auth/logout' && $method === 'GET') {
                $auth->logout();
            }
            elseif ($path === '/auth/forgot-password' && $method === 'GET') {
                $auth->showForgotPassword();
            }
            elseif ($path === '/auth/reset-password' && $method === 'GET') {
                $auth->showResetPassword();
            }
        }
        
        // Routes Front
        else {
            $front = new FrontController();
            
            if ($path === '/' && $method === 'GET') {
                $front->home();
            }
            elseif ($path === '/quizz_solo' && $method === 'GET') {
                $front->quizzSolo();
            }
            elseif ($path === '/vs' && $method === 'GET') {
                $front->vs();
            }
            elseif ($path === '/forum' && $method === 'GET') {
                $front->forum();
            }
            elseif ($path === '/compte' && $method === 'GET') {
                $front->account();
            }
            elseif ($path === '/search' && $method === 'GET') {
                $front->searchForum($_GET['q']);
            }
            elseif ($path === '/chat/send' && $method === 'POST') {
                $front->handleChatMessage($_SESSION['user_id'], $_POST['message']);
            }
            else {
                $front->notFound();
            }
        }
    }
}
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
        $basePath = '/BrainRush';
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = str_replace($basePath, '', $requestUri);
        $method = $_SERVER['REQUEST_METHOD'];

        // Routes pour les assets statiques
        if (preg_match('/\.(css|js|png|jpg|jpeg|gif)$/', $path)) {
            $filePath = __DIR__.'/../public'.$path;
            if (file_exists($filePath)) {
                $mimeTypes = [
                    'css' => 'text/css',
                    'js' => 'application/javascript',
                    'png' => 'image/png',
                    'jpg' => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'gif' => 'image/gif'
                ];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                header('Content-Type: '.$mimeTypes[$ext]);
                readfile($filePath);
                exit;
            }
        }

        // Routes Admin
        if (strpos($path, '/admin') === 0) {
            $admin = new AdminController();
            
            if ($path === '/admin/dashboard' && $method === 'GET') {
                $admin->dashboard();
            }
            // ... autres routes admin
        }
        
        // Routes Auth
        elseif (strpos($path, '/auth') === 0) {
            $auth = new AuthController();
            
            if ($path === '/auth/login' && $method === 'GET') {
                $auth->showLogin();
            }
            // ... autres routes auth
        }
        
        // Routes Front
        else {
            $front = new FrontController();
            
            if ($path === '/' || $path === '') {
                $front->home();
            }
            elseif ($path === '/quizz_solo') {
                $front->quizzSolo();
            }
            elseif ($path === '/vs') {
                $front->vs();
            }
            elseif ($path === '/forum') {
                $front->forum();
            }
            elseif ($path === '/compte') {
                $front->account();
            }
            else {
                $front->notFound();
            }
        }
    }
}
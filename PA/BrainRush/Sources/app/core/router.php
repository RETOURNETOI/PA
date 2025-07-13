<?php
class Router {
    private $routes = [];

    public function add($route, $controller, $method = 'GET') {
        $this->routes[strtoupper($method)][$route] = $controller;
    }

    public function dispatch() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // Routes statiques
        if (isset($this->routes[$method][$uri])) {
            $this->callController($this->routes[$method][$uri]);
            return;
        }

        // Routes dynamiques (ex: /user/123)
        foreach ($this->routes[$method] as $route => $controller) {
            $pattern = '#^' . preg_replace('/\{[a-z]+\}/', '([^/]+)', $route) . '$#';
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $this->callController($controller, $matches);
                return;
            }
        }

        // 404
        http_response_code(404);
        require __DIR__ . '/../view/errors/404.php';
    }

    private function callController($controller, $params = []) {
        list($controllerName, $action) = explode('@', $controller);
        $controllerFile = __DIR__ . '/../controller/' . $controllerName . '.php';

        if (!file_exists($controllerFile)) {
            throw new Exception("Controller $controllerName not found");
        }

        require $controllerFile;
        $controllerClass = new $controllerName();
        call_user_func_array([$controllerClass, $action], $params);
    }
}

// Exemple d'utilisation (à mettre dans public/index.php) :
/*
$router = new Router();
$router->add('/', 'FrontController@home');
$router->add('/login', 'AuthController@login');
$router->add('/admin', 'AdminController@dashboard');
$router->dispatch();
*/
?>
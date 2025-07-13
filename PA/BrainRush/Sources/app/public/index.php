// public/index.php
require __DIR__.'/../app/core/Router.php';
$router = new Router();
$router->dispatch(); // Redirige vers les contrôleurs
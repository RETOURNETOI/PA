<?php
session_start();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Nettoie et supprime les éventuels `/Sources/` ou `/public/` de l’URL
$uri = str_replace(['/BrainRush', '/public', '/Sources'], '', $uri);
$uri = trim($uri, '/');

// Liste des routes
switch ($uri) {

    // ACCUEIL
    case '':
    case 'index':
        require_once __DIR__ . '/../controller/front_controller.php';
        showHome();
        break;

    // AUTH
    case 'login':
        require_once __DIR__ . '/../controller/auth_controller.php';
        showLogin();
        break;

    case 'register':
        require_once __DIR__ . '/../controller/auth_controller.php';
        showRegister();
        break;

    case 'logout':
        require_once __DIR__ . '/../controller/auth_controller.php';
        logout();
        break;

    // DASHBOARD
    case 'dashboard':
        require_once __DIR__ . '/../controller/admin_controller.php';
        showDashboard();
        break;

    // 404 - non trouvé
    default:
        http_response_code(404);
        echo "<h1>404 - Page non trouvée</h1>";
        break;
}
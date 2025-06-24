<?php
// traitement/routeur/routeur.php - Version finale sécurisée + support 404

// Liste des pages autorisées
$routes = [
    'quizz_solo'  => 'quizz_solo.php',
    'vs'          => 'vs.php',
    'classement'  => 'classement.php',
    'tournois'    => 'tournois.php',
    'forum'       => 'forum.php',
    'connexion'   => 'connexion.html',
    'inscription' => 'inscription.php',
    'avatar'      => 'avatar.php',
    'index'       => 'index.html'
];

// Récupération sécurisée de la page demandée
$page = isset($_GET['page']) ? $_GET['page'] : 'index';

// Redirection vers la bonne page ou vers la 404
if (array_key_exists($page, $routes)) {
    header('Location: ../../' . $routes[$page]);
    exit;
} else {
    header('Location: ../../404.php');
    exit;
}
?>
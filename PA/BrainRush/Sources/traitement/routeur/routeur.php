<?php
// traitement/routeur/routeur.php - Version finale sécurisée + support 404

// Liste des pages autorisées
$routes = [
    'quizz_solo'  => 'public/quizz_solo.html',
    'vs'          => 'public/vs.html',
    'classement'  => 'public/classement.html',
    'tournois'    => 'public/tournois.html',
    'forum'       => 'public/forum.html',
    'connexion'   => 'public/connexion.html',
    'inscription' => 'public/inscription.html',
    'avatar'      => 'public/avatar.html',
    'index'       => 'public/index.html'
];

// Récupération sécurisée de la page demandée
$page = isset($_GET['page']) ? $_GET['page'] : 'index';

// Redirection vers la bonne page ou vers la 404
if (array_key_exists($page, $routes)) {
    header('Location: ../../' . $routes[$page]);
    exit;
} else {
    header('Location: ../404.php');
    exit;
}
?>
<?php

$routes = [
    'quizz_solo'  => 'public/quizz_solo.html',
    'vs'          => 'public/VS.html',
    'classement'  => 'public/classement.html',
    'tournois'    => 'public/tournois.html',
    'forum'       => 'public/forum.html',
    'connexion'   => 'public/connexion.html',
    'inscription' => 'public/inscription.html',
    'avatar'      => 'public/avatar.html',
    'index'       => 'public/index.html'
];

$page = isset($_GET['page']) ? $_GET['page'] : 'index';

if (array_key_exists($page, $routes)) {
    header('Location: ../../' . $routes[$page]);
    exit;
} else {
    header('Location: ../404.php');
    exit;
}
?>
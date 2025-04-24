<?php
// Vérifie si une action est spécifiée
if (isset($_GET['page'])) {
    switch ($_GET['page']) {
        case 'quizz_solo':
            header('Location: quizz_solo.php');
            exit;
        case 'vs':
            header('Location: vs.php');
            exit;
        case 'classement':
            header('Location: classement.php');
            exit;
        case 'tournois':
            header('Location: tournois.php');
            exit;
        case 'forum':
            header('Location: forum.php');
            exit;
        case 'connexion':
            header('Location: ../../connexion.html');
            exit;            
        case 'inscription':
            header('Location: inscription.php');
            exit;
        case 'avatar':
            header('Location: avatar.php');
            exit;
        default:
            // Page inconnue, redirige vers l'accueil
            header('Location: index.html');
            exit;
    }
} else {
    // Aucun paramètre spécifié, redirige vers l'accueil
    header('Location: index.html');
    exit;
}
?>
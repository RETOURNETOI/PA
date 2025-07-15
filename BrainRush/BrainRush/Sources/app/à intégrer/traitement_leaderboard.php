<?php
$pdo = new PDO("mysql:host=localhost;dbname=brainrush;charset=utf8", "root", "");

$res = $pdo->query("SELECT prenom, nom, score, photo_profil FROM utilisateurs ORDER BY score DESC LIMIT 10");
$joueurs = $res->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($joueurs);
?>

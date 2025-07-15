<?php
$pdo = new PDO("mysql:host=localhost;dbname=brainrush;charset=utf8", "root", "");
$data = json_decode(file_get_contents("php://input"), true);

$score = $data['score'];
$theme = $data['theme'];
$email = $data['email'];

$req = $pdo->prepare("UPDATE utilisateurs SET score = ? WHERE email = ?");
$req->execute([$score, $email]);

echo "score enregistré";
?>

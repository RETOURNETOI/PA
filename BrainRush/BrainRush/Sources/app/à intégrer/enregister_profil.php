<?php
$pdo = new PDO("mysql:host=localhost;dbname=brainrush;charset=utf8", "root", "");

$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$email = $_POST['email'];
$genre = $_POST['genre'];
$age = $_POST['age'];
$mdp = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT);

$photo_nom = null;
if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0){
$tmp = $_FILES['photo']['tmp_name'];
$ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
$photo_nom = uniqid() . "." . $ext;
move_uploaded_file($tmp, "../uploads/" . $photo_nom);
}

$sql = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, motdepasse, genre, age, photo_profil) VALUES (?, ?, ?, ?, ?, ?, ?)");
$sql->execute([$nom, $prenom, $email, $mdp, $genre, $age, $photo_nom]);

echo "ok";
?>

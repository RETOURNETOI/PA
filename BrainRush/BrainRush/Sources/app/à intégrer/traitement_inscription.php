<?php

$conn = mysqli_connect("localhost", "nomtutilisateur", "mdp", "nomedelabasededonnée");

if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

if (
    isset($_POST['nom']) &&
    isset($_POST['prenom']) &&
    isset($_POST['email']) &&
    isset($_POST['motdepasse']) &&
    isset($_POST['genre']) &&
    isset($_POST['age'])
) {

    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $motdepasse = $_POST['motdepasse'];
    $motdepasse = password_hash($motdepasse, PASSWORD_DEFAULT);
    $genre = $_POST['genre'];
    $age = $_POST['age'];

    $photo = "";
    if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] == 0) {
        $photo = "uploads/" . uniqid() . "_" . $_FILES['photo_profil']['name'];
        move_uploaded_file($_FILES['photo_profil']['tmp_name'], $photo);
    }

    $sql = "INSERT INTO utilisateurs (nom, prenom, email, motdepasse, genre, age, photo_profil)
            VALUES ('$nom', '$prenom', '$email', '$motdepasse', '$genre', $age, '$photo')";

    if (mysqli_query($conn, $sql)) {
        echo "Inscription réussie !";
    } else {
        echo "Erreur : " . mysqli_error($conn);
    }

} else {
    echo "Veuillez remplir tous les champs obligatoires.";
}

mysqli_close($conn);
?>

<?php

$conn = mysqli_connect("localhost", "nomtutilisateur", "mdp", "nomedelabasededonnée");

if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

if (isset($_POST['email']) && isset($_POST['motdepasse'])) {

    $email = $_POST['email'];
    $motdepasse = $_POST['motdepasse'];

    $sql = "SELECT * FROM utilisateurs WHERE email='$email'";
    $resultat = mysqli_query($conn, $sql);

    if (mysqli_num_rows($resultat) == 1) {
        $utilisateur = mysqli_fetch_assoc($resultat);
        if (password_verify($motdepasse, $utilisateur['motdepasse'])) {
            echo "Connexion réussie ! Bienvenue " . $utilisateur['prenom'];
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Email non trouvé.";
    }

} else {
    echo "Veuillez remplir tous les champs.";
}

mysqli_close($conn);
?>

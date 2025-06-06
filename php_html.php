<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Inscription - BrainRush</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="C:\Users\lance\Desktop\test.inscr\inscription.css" />
  <link rel="stylesheet" href="css/main.css" />
</head>
<body class="light-background">

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="traitement/routeur/routeur.php?page=index">🧠 BrainRush</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNavbar">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link" href="traitement/routeur/routeur.php?page=quizz_solo">Quizz Solo</a></li>
            <li class="nav-item"><a class="nav-link" href="traitement/routeur/routeur.php?page=vs">VS</a></li>
            <li class="nav-item"><a class="nav-link" href="traitement/routeur/routeur.php?page=classement">Classement</a></li>
            <li class="nav-item"><a class="nav-link" href="traitement/routeur/routeur.php?page=tournois">Tournois</a></li>
            <li class="nav-item"><a class="nav-link" href="traitement/routeur/routeur.php?page=forum">Forum</a></li>            
        </ul>
        <div class="d-flex align-items-center gap-3">
          <button id="langToggle" class="btn btn-outline-light btn-sm">
            <img src="assets/langue.png" alt="Langue" width="20" />
          </button>
          <button id="themeToggle" class="btn btn-outline-light btn-sm">🌙</button>
          <a href="traitement/routeur/routeur.php?page=avatar">
            <img src="assets/avatar_def1.png" alt="Avatar" class="rounded-circle" width="40" height="40">
          </a>          
        </div>
      </div>
    </div>
  </nav>

  <!-- FORMULAIRE DE D'INSCRIPTION -->
  <div class="inscription-wrapper">
    <div class="signup-container">
      <h2 id="title">Inscription</h2>
      <form id="signupForm">
        <input type="email" name="email" placeholder="Adresse email" required id="emailInput" />
        <input type="lastName" name ="lastName" placeholder="Nom" required id="lastNameInput" />
        <input type="firstName" name ="firstName" placeholder="Prénom" required id="firstNameInput" />
        <input type="password" name="password" placeholder="Mot de passe"  id="passwordInput" />
        <input type="password" name="passwordConfirm" placeholder="Confirmation mot de passe" id="PasswordConfirmInput" />
      </form>
      <p class="inscription-link" id="signin">
        <a href="traitement_index.php?page=connection">Vous avez déja un compte ? Connectez-vous</a>
      </p>
    </div>
  </div>
 <script>

        //pas encore fini
        function checkPassword(form) {
            passwordInput = form.passwordInput.value;
            passwordConfirm = form.passwordConfirmInput.value;

            if (passwordInput == '')
                alert("Please enter Password");

            else if (passwordConfirmInput == '')
                alert("Please enter confirm password");

            else if (passwordInput != passwordConfirmInput) {
                alert("\nPassword did not match: Please try again...")
                return false;
            }

            else {
                alert("Password Match: Perfect :)")
                return true;
            }
        }
    </script>

  <button id="evil-button">Inscription</button>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/inscription.js"></script>
</body>
</html>
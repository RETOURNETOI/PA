<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Profile Form</title>
    <link rel="stylesheet" href="./CSS/index.css">
    <link rel="stylesheet" href="./CSS/chatbot.css">
    <link rel="stylesheet" href="./CSS/main.css">
    <link rel="stylesheet" href="./CSS/profilChange.css">
</head>


<body class="light-background">

    <nav class="custom-navbar">
      <div class="navbar-container">
        <a href="./public/index.html" class="navbar-brand">🧠 BrainRush</a>
        
        <ul class="navbar-links" id="navbar-menu">
          <li><a href="index.html" id="navHome">Accueil</a></li>
          <li><a href="../traitement/routeur/routeur.php?page=quizz_solo" id="navSolo">Solo</a></li>
          <li><a href="../traitement/routeur/routeur.php?page=vs" id="navVS">VS</a></li>
          <li><a href="../traitement/routeur/routeur.php?page=classement" id="navRank">Classement</a></li>
          <li><a href="../traitement/routeur/routeur.php?page=tournois" id="navTournament">Tournois</a></li>
          <li><a href="../traitement/routeur/routeur.php?page=forum" id="navForum">Forum</a></li>
        </ul>
        
        <div class="navbar-actions">
          <button id="langToggle" class="navbar-btn icon" title="Changer la langue">
           <span id="langIcon">🇫🇷</span>
          </button>
          
          <button id="themeToggle" class="navbar-btn icon" title="Changer le thème">
            🌙
          </button>
          
          <a href="connexion.html" class="navbar-btn secondary" id="loginBtn">
            <span class="text">Se connecter</span>
          </a>
          
          <a href="inscription.html" class="navbar-btn primary" id="signupBtn">
            <span class="text">S'inscrire</span>
          </a>
          <div href="compte.html" class="avatar-container">
            <?php
                $photo = $_SESSION['photo_profil'] ?? 'avatar_def1.png';
                echo "
                <img src='./uploads/$photo' alt='Profil' class='avatar-icon'>";
              ?>>
          </div>
        </div>
      </div>
    </nav>
<div class="conteneur-form">
<form id="formulaireProfil" action="../traitement/enregistrer_profil.php" method="POST" enctype="multipart/form-data">
<div class="colonne-gauche">
<label for="nom">Nom :</label>
<input type="text" id="nom" name="nom" required>
<label for="prenom">Prénom :</label>
<input type="text" id="prenom" name="prenom" required>
<label for="email">Email :</label>
<input type="email" id="email" name="email" required>
<label for="genre">Genre :</label>
<select id="genre" name="genre">
<option value="homme">Homme</option>
<option value="femme">Femme</option>
<option value="autre">Autre</option>
</select>
<label for="age">Âge :</label>
<input type="number" id="age" name="age" required>

<div class="bloc-mdp">
<label for="motdepasse">Mot de passe :</label>
<input type="password" id="motdepasse" name="motdepasse" placeholder="mot de passe">
<img src="./images/eye-close.png" id="icone-oeil">
</div>

<button type="button" onclick="soumettreFormulaire()">Enregistrer</button>
</div>

<div class="colonne-droite">
<label for="photo">Photo de profil :</label>
<input type="file" id="photo" accept=".jpg, .jpeg" onchange="verifierEtMontrerPhoto()" required>
<div id="zone-captcha" style="display:none;margin-top:20px">
<div style="position:relative;height:65px;width:100%;background:black;border-radius:5px;">
<img src="./images/captcha-bg.png" style="width:100%;height:100%;border-radius:5px;opacity:0.9;">
<span class="captcha"></span>
</div>
<div style="display:flex;gap:10px;margin-top:10px;">
<input type="text" id="champCaptcha" placeholder="Saisir captcha" style="flex:1;padding:10px;border-radius:4px;border:1px solid #ccc;">
<button id="btnVerifierCaptcha" style="background:#4caf50;color:white;padding:10px 16px;border:none;border-radius:4px;">Vérifier</button>
</div>
<div id="etatCaptcha" style="margin-top:10px;color:red;font-weight:bold;"></div>
</div>

<img id="aperçuPhoto" src="#" alt="aperçu" style="display:none">

<a href="../traitement/logout.php" class="bouton-nav danger">Se déconnecter</a>
</div>
</form>

        <footer class="bg-dark text-white text-center py-3">
      <p class="mb-0" id="footerText">© 2025 BrainRush. Tous droits réservés.</p>
    </footer>

  </div>
    <div id="chatbot-box" class="hidden">
      <div id="chatbox" class="chatbox-content">
      </div>
      <div class="chatbox-input">
        <input type="text" id="userInput" placeholder="Écris ton message..." />
      </div>
      <button id="close-chatbot" class="close-chatbot">×</button>
    </div>

    
    <button id="chatbot-icon" class="chatbot-open-button">
      💬
    </button>
    <script src="./JS/profilChange.js"></script>
    <script src="./JS/index.js"></script>
    <script src="./JS/chatbot.js"></script>
    <script src="./JS/main.js"></script>
</body>
</html>
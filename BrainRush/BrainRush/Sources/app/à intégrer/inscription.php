<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Inscription</title>
  <link rel="stylesheet" href="./CSS/inscription.css" />
    <link rel="stylesheet" href="./CSS/main.css">
    <link rel="stylesheet" href="./CSS/chatbot.css">
    <link rel="stylesheet" href="./CSS/index.css">
</head>
<body>

    <nav class="custom-navbar">
      <div class="navbar-container">
        <a href="index.html" class="navbar-brand">🧠 BrainRush</a>
        
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
    <form action="traitement_inscription.php" method="POST" enctype="multipart/form-data">
        <label for="nom" id="nome">Nom:</label>
        <input type="text" name="nom" required><br>

        <label for="prenom" id="prenom">Prénom:</label>
        <input type="text" name="prenom" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="motdepasse" id="Mdp">Mot de passe:</label>
        <input type="password" name="motdepasse" required><br>

        <label for="genre" id="gender">Genre:</label>
        <select name="genre" required>
            <option value="">--Sélectionner--</option>
            <option value="Homme">Homme</option>
            <option value="Femme">Femme</option>
            <option value="Autre">Autre</option>
        </select><br>

        <label for="age">Age:</label>
        <input type="number" name="age" min="0" required><br>

        <label for="photo_profil" id="profil_picture">Photo de profil:</label>
        <input type="file" name="photo_profil" accept="image/*"><br>

        <button type="submit" id="">S'inscrire</button>
    </form>
  
    <div id="chatbot-box" class="hidden">
      <div id="chatbox" class="chatbox-content">
      </div>
      <div class="chatbox-input">
        <input type="text" id="userInput" placeholder="Écris ton message..." />
      </div>
      <button id="close-chatbot" class="close-chatbot">×</button>
    </div>
    <script src="./JS/main.js"></script>
    <script src="./JS/chatbot.js"></script>
    <script src="./JS/index.js"></script>

</body>
</html>
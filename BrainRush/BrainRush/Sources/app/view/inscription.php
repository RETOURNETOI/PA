<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inscription - BrainRush</title>
    <link rel="stylesheet" href="../CSS/index.css">
    <link rel="stylesheet" href="../CSS/inscription.css">
    <link rel="stylesheet" href="../CSS/chatbot.css">
    <link rel="stylesheet" href="../CSS/main.css">
  </head>
  <body class="light-background">

    <nav class="custom-navbar">
      <div class="navbar-container">
        <a href="index.html" class="navbar-brand">🧠 BrainRush</a>
        
        <ul class="navbar-links" id="navbar-menu">
          <li><a href="index.html" id="navHome">Accueil</a></li>
          <li><a href="../traitement/routeur/routeur.php?page=quizz_solo" id="navSolo">Solo</a></li>
          <li><a href="../traitement/routeur/routeur.php?page=vs" id="navVS">VS</a></li>
          <li><a href="../traitement/routeur/routeur.php?page=classement" id="navRank">Classement</a></li>
          <li><a href="../traitement/routeur/routeur.php?page=tournois" id="navTournois">Tournois</a></li>
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
            <img src="../assets/avatar_def1.png" alt="Profil" class="avatar-icon">
          </div>
        </div>
      </div>
    </nav>

    <div class="inscription-wrapper">
      <div class="signin-container">
        <h2 id="inscriptionTitle">Inscription</h2>
        <form action="../traitement/inscription/inscription_traitement.php" method="POST" class="inscription-form">
          <div class="form-group">
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
          </div>
          <div class="form-group">
            <input type="email" name="email" placeholder="Adresse email" required>
          </div>
          <div class="form-group">
            <input type="password" name="password" placeholder="Mot de passe" required>
          </div>
          <button type="submit" class="btn btn-outline-primary mt-3 w-100">S'inscrire</button>
        </form>
        
        <div class="connexion-link">
          <p>Déjà un compte ? <a href="../traitement/routeur/routeur.php?page=connexion">Se connecter</a></p>
        </div>
      </div>
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
    <div id="captcha-popup" class="captcha-popup">
      <div class="captcha-content">
        <span class="close-captcha">&times;</span>
        <div class="wrapper">
          <header>Vérification CAPTCHA</header>
          <div class="captcha-area">
            <div class="captcha-img">
              <img src="../assets/captcha-bg.png" alt="Captch Background">
              <span class="captcha"></span>
            </div>
            <button class="reload-btn"><i class="fas fa-redo-alt"></i></button>
          </div>
          <form class="input-area">
            <input type="text" placeholder="Enter captcha" maxlength="6" spellcheck="false" required>
            <button class="check-btn">Vérifier</button>
          </form>
          <div class="status-text"></div>
        </div>
      </div>
    </div>

    <script src="../JS/inscription.js"></script>
    <script src="../JS/chatbot.js"></script>
    <script src="../JS/main.js"></script>
  </body>
</html>
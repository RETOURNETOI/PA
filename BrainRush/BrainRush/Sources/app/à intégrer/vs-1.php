<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Versus</title>
    <link rel="stylesheet" href="./CSS/VS.css">
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
          <li><a href="../traitement/routeur/routeur.php?p age=tournois" id="navTournament">Tournois</a></li>
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
<div class="section">
    <h2>Recommandé pour vous</h2>
<div class="row" style="position: relative;">
      <button class="flechedegauche" onclick="scrollgauche('recommender')">←</button>
      <div class="carousel" id="recommender">
        <a href="traitement/routeur/routeur.php?page=mathematique"><img class="icon" src="assets/mathematique.png"  alt="exemple" /><span id="math">Mathématique</span></a>
        <a href="traitement/routeur/routeur.php?page=physique"><img class="icone" src="assets/physique.png"  alt="exemple" /><span id="physique">Physique</span></a>        
        <a href="traitement/routeur/routeur.php?page=biologie"><img class="icone" src="assets/biologie.png"  alt="exemple" /><span id="biologie">Biologie</span></a>        
        <a href="traitement/routeur/routeur.php?page=manga"><img class="icone" src="assets/manga.png"  alt="exemple" /><span id="manga">Manga</span></a>
        <a href="traitement/routeur/routeur.php?page=jeuxvideo"><img class="icone" src="assets/jeuVideo.png"  alt="exemple" /><span id="jeux">jeux Vidéo</span></a>
        <a href="traitement/routeur/routeur.php?page=mathematique"><img class="icon" src="assets/mathematique.png"  alt="exemple" /><span id="math">Mathématique</span></a>
        <a href="traitement/routeur/routeur.php?page=physique"><img class="icone" src="assets/physique.png"  alt="exemple" /><span id="physique">Physique</span></a>        
        <a href="traitement/routeur/routeur.php?page=biologie"><img class="icone" src="assets/biologie.png"  alt="exemple" /><span id="biologie">Biologie</span></a>        
        <a href="traitement/routeur/routeur.php?page=manga"><img class="icone" src="assets/manga.png"  alt="exemple" /><span id="manga">Manga</span></a>
        <a href="traitement/routeur/routeur.php?page=jeuxvideo"><img class="icone" src="assets/jeuVideo.png"  alt="exemple" /><span id="jeux">jeux Vidéo</span></a>
        <a href="traitement/routeur/routeur.php?page=mathematique"><img class="icon" src="assets/mathematique.png"  alt="exemple" /><span id="math">Mathématique</span></a>
        <a href="traitement/routeur/routeur.php?page=physique"><img class="icone" src="assets/physique.png"  alt="exemple" /><span id="physique">Physique</span></a>        
        <a href="traitement/routeur/routeur.php?page=biologie"><img class="icone" src="assets/biologie.png"  alt="exemple" /><span id="biologie">Biologie</span></a>        
        <a href="traitement/routeur/routeur.php?page=manga"><img class="icone" src="assets/manga.png"  alt="exemple" /><span id="manga">Manga</span></a>
        <a href="traitement/routeur/routeur.php?page=jeuxvideo"><img class="icone" src="assets/jeuVideo.png"  alt="exemple" /><span id="jeux">jeux Vidéo</span></a>  
          
      </div>
      <button class="flechededroite" onclick="scrolldroit('recommender')">→</button>
          </div>
  </div>

  <div class="section">
    <h2>Populaires</h2>
<div class="row" style="position: relative;">
    <button class="flechedegauche" onclick="scrollgauche('Populaires')">←</button>
    <div class="carousel" id="Populaires">
        <a href="traitement/routeur/routeur.php?page=mathematique"><img class="icon" src="assets/mathematique.png"  alt="exemple" /><span id="math">Mathématique</span></a>
        <a href="traitement/routeur/routeur.php?page=physique"><img class="icone" src="assets/physique.png"  alt="exemple" /><span id="physique">Physique</span></a>        
        <a href="traitement/routeur/routeur.php?page=biologie"><img class="icone" src="assets/biologie.png"  alt="exemple" /><span id="biologie">Biologie</span></a>        
        <a href="traitement/routeur/routeur.php?page=manga"><img class="icone" src="assets/manga.png"  alt="exemple" /><span id="manga">Manga</span></a>
        <a href="traitement/routeur/routeur.php?page=jeuxvideo"><img class="icone" src="assets/jeuVideo.png"  alt="exemple" /><span id="jeux">jeux Vidéo</span></a>
        <a href="traitement/routeur/routeur.php?page=mathematique"><img class="icon" src="assets/mathematique.png"  alt="exemple" /><span id="math">Mathématique</span></a>
        <a href="traitement/routeur/routeur.php?page=physique"><img class="icone" src="assets/physique.png"  alt="exemple" /><span id="physique">Physique</span></a>        
        <a href="traitement/routeur/routeur.php?page=biologie"><img class="icone" src="assets/biologie.png"  alt="exemple" /><span id="biologie">Biologie</span></a>        
        <a href="traitement/routeur/routeur.php?page=manga"><img class="icone" src="assets/manga.png"  alt="exemple" /><span id="manga">Manga</span></a>
        <a href="traitement/routeur/routeur.php?page=jeuxvideo"><img class="icone" src="assets/jeuVideo.png"  alt="exemple" /><span id="jeux">jeux Vidéo</span></a>
        <a href="traitement/routeur/routeur.php?page=mathematique"><img class="icon" src="assets/mathematique.png"  alt="exemple" /><span id="math">Mathématique</span></a>
        <a href="traitement/routeur/routeur.php?page=physique"><img class="icone" src="assets/physique.png"  alt="exemple" /><span id="physique">Physique</span></a>        
        <a href="traitement/routeur/routeur.php?page=biologie"><img class="icone" src="assets/biologie.png"  alt="exemple" /><span id="biologie">Biologie</span></a>        
        <a href="traitement/routeur/routeur.php?page=manga"><img class="icone" src="assets/manga.png"  alt="exemple" /><span id="manga">Manga</span></a>
        <a href="traitement/routeur/routeur.php?page=jeuxvideo"><img class="icone" src="assets/jeuVideo.png"  alt="exemple" /><span id="jeux">jeux Vidéo</span></a>  
      </div>
      <button class="flechededroite" onclick="scrolldroit('Populaires')">→</button>
    </div>
  </div>
  
  <div class="section">
    <h2>Communautaires</h2>
<div class="row" style="position: relative;">
      <button class="flechedegauche" onclick="scrollgauche('communauté')">←</button>
      <div class="carousel" id="communauté">
        <a href="traitement/routeur/routeur.php?page=mathematique"><img class="icon" src="assets/mathematique.png"  alt="exemple" /><span id="math">Mathématique</span></a>
        <a href="traitement/routeur/routeur.php?page=physique"><img class="icone" src="assets/physique.png"  alt="exemple" /><span id="physique">Physique</span></a>        
        <a href="traitement/routeur/routeur.php?page=biologie"><img class="icone" src="assets/biologie.png"  alt="exemple" /><span id="biologie">Biologie</span></a>        
        <a href="traitement/routeur/routeur.php?page=manga"><img class="icone" src="assets/manga.png"  alt="exemple" /><span id="manga">Manga</span></a>
        <a href="traitement/routeur/routeur.php?page=jeuxvideo"><img class="icone" src="assets/jeuVideo.png"  alt="exemple" /><span id="jeux">jeux Vidéo</span></a>
        <a href="traitement/routeur/routeur.php?page=mathematique"><img class="icon" src="assets/mathematique.png"  alt="exemple" /><span id="math">Mathématique</span></a>
        <a href="traitement/routeur/routeur.php?page=physique"><img class="icone" src="assets/physique.png"  alt="exemple" /><span id="physique">Physique</span></a>        
        <a href="traitement/routeur/routeur.php?page=biologie"><img class="icone" src="assets/biologie.png"  alt="exemple" /><span id="biologie">Biologie</span></a>        
        <a href="traitement/routeur/routeur.php?page=manga"><img class="icone" src="assets/manga.png"  alt="exemple" /><span id="manga">Manga</span></a>
        <a href="traitement/routeur/routeur.php?page=jeuxvideo"><img class="icone" src="assets/jeuVideo.png"  alt="exemple" /><span id="jeux">jeux Vidéo</span></a>
        <a href="traitement/routeur/routeur.php?page=mathematique"><img class="icon" src="assets/mathematique.png"  alt="exemple" /><span id="math">Mathématique</span></a>
        <a href="traitement/routeur/routeur.php?page=physique"><img class="icone" src="assets/physique.png"  alt="exemple" /><span id="physique">Physique</span></a>        
        <a href="traitement/routeur/routeur.php?page=biologie"><img class="icone" src="assets/biologie.png"  alt="exemple" /><span id="biologie">Biologie</span></a>        
        <a href="traitement/routeur/routeur.php?page=manga"><img class="icone" src="assets/manga.png"  alt="exemple" /><span id="manga">Manga</span></a>
        <a href="traitement/routeur/routeur.php?page=jeuxvideo"><img class="icone" src="assets/jeuVideo.png"  alt="exemple" /><span id="jeux">jeux Vidéo</span></a>   
      </div>
      <button class="flechededroite" onclick="scrolldroit('communauté')">→</button>
    </div>
  </div>

    <section class="container text-center py-5">
      <div class="section-title">
        <h2>🏆 Podium des 3 MVP All-Time</h2>
      </div>

      <div class="podium">
        <div class="step second">
          <div class="avatar"><img src="./assets/lion.png" alt="Avatar 2"></div>
          <div class="block">2</div>
          <div class="info">
            <p class="pseudo">Pseudo2</p>
            <p class="points">⚡ 8900 pts</p>
          </div>
        </div>

        <div class="step first">
          <div class="avatar"><img src="./assets/avatar_def1.png" alt="Avatar 1"></div>
          <div class="block">1</div>
          <div class="info">
            <p class="pseudo">Pseudo1</p>
            <p class="points">🌟 10250 pts</p>
          </div>
        </div>

        <div class="step third">
          <div class="avatar"><img src="./assets/tigre.png" alt="Avatar 3"></div>
          <div class="block">3</div>
          <div class="info">
            <p class="pseudo">Pseudo3</p>
            <p class="points">🔥 8450 pts</p>
          </div>
        </div>
      </div>
    </section>

    <footer class="bg-dark text-white text-center py-3">
      <p class="mb-0">© 2025 BrainRush. Tous droits réservés.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

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
    <script src="./js/index.js"></script>
    <script src="./js/chatbot.js"></script>
    <script src="./JS/VS.js"></script>
</body>
</html>
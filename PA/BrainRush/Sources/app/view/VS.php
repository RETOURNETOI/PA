<?php
$pageTitle = "1 vs 1 - BrainRush";
$cssFiles = ['VS.css', 'main.css', 'chatbot.css'];
require_once __DIR__.'/../include/header.php';
?>

<div class="vs-container">
    <h1>Mode 1 contre 1</h1>
    
    <div class="section">
        <h2>Recommandé pour vous</h2>
        <div class="row" style="position: relative;">
            <button class="flechedegauche" onclick="scrollgauche('recommender')">←</button>
            <div class="carousel" id="recommender">
                <div class="item"> <img class="icon" src="/assets/images/mathematique.png" alt="Mathématiques" /><p>Mathématiques</p></div>
                <div class="item"> <img class="icone" src="/assets/images/physique.png" alt="Physique" /><p style="margin-top: 30px;">Physique</p></div>
                <div class="item"> <img class="icon" src="/assets/images/biologie.png" alt="Biologie" /><p>Biologie</p></div>
                <div class="item"> <img class="icon" src="/assets/images/manga.png" alt="Manga" /><p>Manga</p></div>
                <div class="item"> <img class="icon" src="/assets/images/jeuVideo.png" alt="Jeux Vidéo" /><p>Jeux Vidéo</p></div>
                <div class="item"> <img class="icon" src="/assets/images/mathematique.png" alt="Mathématiques" /><p>Mathématiques</p></div>
                <div class="item"> <img class="icone" src="/assets/images/physique.png" alt="Physique" /><p style="margin-top: 30px;">Physique</p></div>
                <div class="item"> <img class="icon" src="/assets/images/biologie.png" alt="Biologie" /><p>Biologie</p></div>
                <div class="item"> <img class="icon" src="/assets/images/manga.png" alt="Manga" /><p>Manga</p></div>
                <div class="item"> <img class="icon" src="/assets/images/jeuVideo.png" alt="Jeux Vidéo" /><p>Jeux Vidéo</p></div>
            </div>
            <button class="flechededroite" onclick="scrolldroit('recommender')">→</button>
        </div>
    </div>

    <div class="section">
        <h2>Populaires</h2>
        <div class="row" style="position: relative;">
            <button class="flechedegauche" onclick="scrollgauche('populaire')">←</button>
            <div class="carousel" id="populaire">
                <div class="item"> <img class="icon" src="/assets/images/mathematique.png" alt="Mathématiques" /><p>Mathématiques</p></div>
                <div class="item"> <img class="icone" src="/assets/images/physique.png" alt="Physique" /><p style="margin-top: 30px;">Physique</p></div>
                <div class="item"> <img class="icon" src="/assets/images/biologie.png" alt="Biologie" /><p>Biologie</p></div>
                <div class="item"> <img class="icon" src="/assets/images/manga.png" alt="Manga" /><p>Manga</p></div>
                <div class="item"> <img class="icon" src="/assets/images/jeuVideo.png" alt="Jeux Vidéo" /><p>Jeux Vidéo</p></div>
                <div class="item"> <img class="icon" src="/assets/images/mathematique.png" alt="Mathématiques" /><p>Mathématiques</p></div>
                <div class="item"> <img class="icone" src="/assets/images/physique.png" alt="Physique" /><p style="margin-top: 30px;">Physique</p></div>
                <div class="item"> <img class="icon" src="/assets/images/biologie.png" alt="Biologie" /><p>Biologie</p></div>
                <div class="item"> <img class="icon" src="/assets/images/manga.png" alt="Manga" /><p>Manga</p></div>
                <div class="item"> <img class="icon" src="/assets/images/jeuVideo.png" alt="Jeux Vidéo" /><p>Jeux Vidéo</p></div>
            </div>
            <button class="flechededroite" onclick="scrolldroit('populaire')">→</button>
        </div>
    </div>
    
    <div class="section">
        <h2>Communautaires</h2>
        <div class="row" style="position: relative;">
            <button class="flechedegauche" onclick="scrollgauche('communauté')">←</button>
            <div class="carousel" id="communauté">
                <div class="item"> <img class="icon" src="/assets/images/mathematique.png" alt="Mathématiques" /><p>Mathématiques</p></div>
                <div class="item"> <img class="icone" src="/assets/images/physique.png" alt="Physique" /><p style="margin-top: 30px;">Physique</p></div>
                <div class="item"> <img class="icon" src="/assets/images/biologie.png" alt="Biologie" /><p>Biologie</p></div>
                <div class="item"> <img class="icon" src="/assets/images/manga.png" alt="Manga" /><p>Manga</p></div>
                <div class="item"> <img class="icon" src="/assets/images/jeuVideo.png" alt="Jeux Vidéo" /><p>Jeux Vidéo</p></div>
                <div class="item"> <img class="icon" src="/assets/images/mathematique.png" alt="Mathématiques" /><p>Mathématiques</p></div>
                <div class="item"> <img class="icone" src="/assets/images/physique.png" alt="Physique" /><p style="margin-top: 30px;">Physique</p></div>
                <div class="item"> <img class="icon" src="/assets/images/biologie.png" alt="Biologie" /><p>Biologie</p></div>
                <div class="item"> <img class="icon" src="/assets/images/manga.png" alt="Manga" /><p>Manga</p></div>
                <div class="item"> <img class="icon" src="/assets/images/jeuVideo.png" alt="Jeux Vidéo" /><p>Jeux Vidéo</p></div>
            </div>
            <button class="flechededroite" onclick="scrolldroit('communauté')">→</button>
        </div>
    </div>

    <section class="container text-center py-5">
        <div class="section-title">
            <h2 id="podiumTitle">🏆 Podium des 3 MVP All-Time</h2>
        </div>

        <div class="podium">
            <div class="step second">
                <div class="avatar"><img src="/assets/images/lion.png" alt="Avatar 2"></div>
                <div class="block">2</div>
                <div class="info">
                    <p class="pseudo">Pseudo2</p>
                    <p class="points">⚡ 8900 pts</p>
                </div>
            </div>

            <div class="step first">
                <div class="avatar"><img src="/assets/images/avatar_def1.png" alt="Avatar 1"></div>
                <div class="block">1</div>
                <div class="info">
                    <p class="pseudo">Pseudo1</p>
                    <p class="points">🌟 10250 pts</p>
                </div>
            </div>

            <div class="step third">
                <div class="avatar"><img src="/assets/images/tigre.png" alt="Avatar 3"></div>
                <div class="block">3</div>
                <div class="info">
                    <p class="pseudo">Pseudo3</p>
                    <p class="points">🔥 8450 pts</p>
                </div>
            </div>
        </div>
    </section>

    <div class="vs-options">
        <div class="create-room">
            <h2>Créer une partie</h2>
            <button id="createRoomBtn" class="btn">Créer</button>
        </div>
        <div class="join-room">
            <h2>Rejoindre une partie</h2>
            <input type="text" id="roomCode" placeholder="Code de la partie">
            <button id="joinRoomBtn" class="btn">Rejoindre</button>
        </div>
    </div>
    <div id="gameArea" class="hidden">
        <!-- Zone de jeu dynamique -->
    </div>
</div>

<?php 
$jsFiles = ['VS.js', 'chatbot.js', 'main.js'];
require_once __DIR__.'/../include/footer.php';
?>
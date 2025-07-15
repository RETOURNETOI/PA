<?php
$pageTitle = "Quizz Solo";
$cssFiles = ['quizz_solo.css', 'chatbot.css'];
require_once __DIR__ . '/../include/header.php';
?>

<div class="quiz-container">
    <div class="quiz-header">
        <h1>Quizz Solo</h1>
        <div class="category-selector">
            <h2>Choisissez une catégorie :</h2>
            
            <div class="section">
                <h3>Recommandé pour vous</h3>
                <div class="row" style="position: relative;">
                    <button class="flechedegauche" onclick="scrollgauche('recommender')">←</button>
                    <div class="carousel" id="recommender">
                        <div class="item"><img class="icon" src="/BrainRush/BrainRush/public/assets/images/mathematique.png" alt="Mathématiques"><p>Mathématiques</p></div>
                        <div class="item"><img class="icon" src="/BrainRush/BrainRush/public/assets/images/physique.png" alt="Physique"><p>Physique</p></div>
                        <div class="item"><img class="icon" src="/BrainRush/BrainRush/public/assets/images/biologie.png" alt="Biologie"><p>Biologie</p></div>
                        <div class="item"><img class="icon" src="/BrainRush/BrainRush/public/assets/images/manga.png" alt="Manga"><p>Manga</p></div>
                        <div class="item"><img class="icon" src="/BrainRush/BrainRush/public/assets/images/jeuVideo.png" alt="Jeux Vidéo"><p>Jeux Vidéo</p></div>
                        <div class="item"><img class="icon" src="/BrainRush/BrainRush/public/assets/images/football.png" alt="Football"><p>Football</p></div>
                    </div>
                    <button class="flechededroite" onclick="scrolldroit('recommender')">→</button>
                </div>
            </div>

            <div class="section">
                <h3>Populaires</h3>
                <div class="row" style="position: relative;">
                    <button class="flechedegauche" onclick="scrollgauche('populaire')">←</button>
                    <div class="carousel" id="populaire">
                        <div class="item"><img class="icon" src="/BrainRush/BrainRush/public/assets/images/mathematique.png" alt="Mathématiques"><p>Mathématiques</p></div>
                        <div class="item"><img class="icon" src="/BrainRush/BrainRush/public/assets/images/biologie.png" alt="Biologie"><p>Biologie</p></div>
                        <div class="item"><img class="icon" src="/BrainRush/BrainRush/public/assets/images/physique.png" alt="Physique"><p>Physique</p></div>
                        <div class="item"><img class="icon" src="/BrainRush/BrainRush/public/assets/images/manga.png" alt="Manga"><p>Manga</p></div>
                        <div class="item"><img class="icon" src="/BrainRush/BrainRush/public/assets/images/jeuVideo.png" alt="Jeux Vidéo"><p>Jeux Vidéo</p></div>
                    </div>
                    <button class="flechededroite" onclick="scrolldroit('populaire')">→</button>
                </div>
            </div>

            <div class="section">
                <h3>Communautaires</h3>
                <div class="row" style="position: relative;">
                    <button class="flechedegauche" onclick="scrollgauche('communaute')">←</button>
                    <div class="carousel" id="communaute">
                        <div class="item"><img class="icon" src="/BrainRush/BrainRush/public/assets/images/manga.png" alt="Manga"><p>Manga</p></div>
                        <div class="item"><img class="icon" src="/BrainRush/BrainRush/public/assets/images/jeuVideo.png" alt="Jeux Vidéo"><p>Jeux Vidéo</p></div>
                        <div class="item"><img class="icon" src="/BrainRush/BrainRush/public/assets/images/football.png" alt="Football"><p>Football</p></div>
                        <div class="item"><img class="icon" src="/BrainRush/BrainRush/public/assets/images/mathematique.png" alt="Mathématiques"><p>Mathématiques</p></div>
                    </div>
                    <button class="flechededroite" onclick="scrolldroit('communaute')">→</button>
                </div>
            </div>
        </div>
    </div>
    <div class="quiz-questions" id="quizArea">
    </div>
</div>

<?php 
$jsFiles = ['quizz_solo.js', 'chatbot.js'];
require_once __DIR__ . '/../include/footer.php';
?>
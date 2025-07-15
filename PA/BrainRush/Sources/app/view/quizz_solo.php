<?php
$pageTitle = "Quizz Solo";
$cssFiles = ['quizz_solo.css', 'chatbot.css'];
require_once __DIR__.'/../include/header.php';
?>

<div class="quiz-container">
    <div class="quiz-header">
        <h1>Quizz Solo</h1>
        <div class="category-selector">
            <h2>Choisissez une catégorie :</h2>
            <div class="categories">
                <div class="category" data-category="math">
                    <img src="/assets/images/mathematique.png" alt="Mathématiques">
                    <span>Mathématiques</span>
                </div>
                <div class="category" data-category="biology">
                    <img src="/assets/images/biologie.png" alt="Biologie">
                    <span>Biologie</span>
                </div>
            </div>
        </div>
    </div>
    <div class="quiz-questions" id="quizArea">
        <!-- Les questions seront chargées ici dynamiquement -->
    </div>
</div>

<?php 
$jsFiles = ['quizz_solo.js', 'chatbot.js'];
require_once __DIR__.'/../include/footer.php';
?>
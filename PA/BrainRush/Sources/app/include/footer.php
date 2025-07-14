    </main>

    <footer class="main-footer">
        <div class="container">
            <div class="footer-section">
                <h3>À propos</h3>
                <p>BrainRush est un jeu éducatif développé sans framework</p>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <a href="mailto:contact@brainrush.com">contact@brainrush.com</a>
            </div>
            <div class="footer-section">
                <h3>Newsletter</h3>
                <form id="newsletter-form">
                    <input type="email" placeholder="Votre email" required>
                    <button type="submit">S'inscrire</button>
                </form>
            </div>
        </div>
        <div class="copyright">
            &copy; <?= date('Y') ?> BrainRush - Tous droits réservés
        </div>
    </footer>

    <script src="/assets/JS/main.js"></script>
    <?php if(isset($jsFiles)): ?>
        <?php foreach($jsFiles as $jsFile): ?>
            <script src="/assets/JS/<?= $jsFile ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
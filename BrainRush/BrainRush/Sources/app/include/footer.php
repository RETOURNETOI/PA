</main>

    <footer class="main-footer">
        <div class="container">
            <div class="footer-section">
                <h3>À propos</h3>
                <p>BrainRush - Jeu éducatif</p>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <a href="mailto:contact@brainrush.com">contact@brainrush.com</a>
            </div>
        </div>
        <div class="copyright">
            &copy; <?= date('Y') ?> BrainRush - Tous droits réservés
        </div>
    </footer>

    <?php if (!isset($basePath)) $basePath = '/BrainRush/BrainRush/public'; ?>
    <script src="<?= $basePath ?>/assets/JS/main.js"></script>
    <script src="<?= $basePath ?>/assets/JS/index.js"></script>
    <script src="<?= $basePath ?>/assets/JS/chatbot.js"></script>
    
    <?php if (isset($jsFiles) && is_array($jsFiles)): ?>
        <?php foreach ($jsFiles as $jsFile): ?>
            <script src="<?= $basePath ?>/assets/JS/<?= htmlspecialchars($jsFile) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

</body>
</html>
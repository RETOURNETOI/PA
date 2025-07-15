<?php
$pageTitle = "Forum - " . htmlspecialchars($post['title']);
$cssFiles = ['main.css', 'forum.css', 'chatbot.css'];
require_once __DIR__.'/../include/header.php';
?>

<div class="forum-container">
    <div class="breadcrumb">
        <a href="/BrainRush/BrainRush/forum">← Retour au forum</a>
    </div>

    <article class="post-detail">
        <header class="post-header">
            <h1><?= htmlspecialchars($post['title']) ?></h1>
            <div class="post-meta">
                <span class="author">👤 <?= htmlspecialchars($post['username']) ?></span>
                <span class="date">📅 <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></span>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== $post['user_id']): ?>
                <button class="report-btn" data-type="post" data-id="<?= $post['id'] ?>">🚩 Signaler</button>
                <?php endif; ?>
            </div>
        </header>
        
        <div class="post-content">
            <?= nl2br(htmlspecialchars($post['content'])) ?>
        </div>
    </article>

    <section class="replies-section">
        <h3>Réponses (<?= count($replies) ?>)</h3>
        
        <?php if (isset($_SESSION['user_id'])): ?>
        <div class="reply-form">
            <h4>Ajouter une réponse</h4>
            <form method="POST" action="/BrainRush/BrainRush/forum/reply">
                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                <div class="form-group">
                    <textarea name="content" placeholder="Votre réponse..." rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Répondre</button>
            </form>
        </div>
        <?php endif; ?>

        <div class="replies-list">
            <?php if (empty($replies)): ?>
                <p class="no-replies">Aucune réponse pour le moment.</p>
            <?php else: ?>
                <?php foreach ($replies as $reply): ?>
                <div class="reply">
                    <div class="reply-header">
                        <span class="author">👤 <?= htmlspecialchars($reply['username']) ?></span>
                        <span class="date">📅 <?= date('d/m/Y H:i', strtotime($reply['created_at'])) ?></span>
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== $reply['user_id']): ?>
                        <button class="report-btn" data-type="reply" data-id="<?= $reply['id'] ?>">🚩 Signaler</button>
                        <?php endif; ?>
                    </div>
                    <div class="reply-content">
                        <?= nl2br(htmlspecialchars($reply['content'])) ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</div>

<div id="reportModal" class="modal hidden">
    <div class="modal-content">
        <h3>Signaler ce contenu</h3>
        <form id="reportForm">
            <div class="form-group">
                <label>Raison du signalement :</label>
                <select name="reason" required>
                    <option value="">Choisir une raison</option>
                    <option value="Spam">Spam</option>
                    <option value="Contenu inapproprié">Contenu inapproprié</option>
                    <option value="Harcèlement">Harcèlement</option>
                    <option value="Fausses informations">Fausses informations</option>
                    <option value="Autre">Autre</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-danger">Signaler</button>
                <button type="button" id="closeModal" class="btn btn-secondary">Annuler</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reportModal = document.getElementById('reportModal');
    const reportForm = document.getElementById('reportForm');
    const closeModal = document.getElementById('closeModal');
    
    let currentReportData = null;

    document.querySelectorAll('.report-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            currentReportData = {
                type: e.target.dataset.type,
                id: e.target.dataset.id
            };
            reportModal.classList.remove('hidden');
        });
    });

    closeModal.addEventListener('click', () => {
        reportModal.classList.add('hidden');
    });

    reportForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(reportForm);
        const data = {
            ...currentReportData,
            reason: formData.get('reason')
        };

        try {
            const response = await fetch('/BrainRush/BrainRush/forum/report', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            });

            const result = await response.json();
            
            if (result.success) {
                alert('Signalement envoyé avec succès');
                reportModal.classList.add('hidden');
            } else {
                alert('Erreur: ' + result.message);
            }
        } catch (error) {
            alert('Erreur de connexion');
        }
    });
});
</script>

<?php 
$jsFiles = ['chatbot.js'];
require_once __DIR__.'/../include/footer.php';
?>
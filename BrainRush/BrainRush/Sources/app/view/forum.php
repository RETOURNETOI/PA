<?php
$pageTitle = "Forum";
$cssFiles = ['main.css', 'forum.css', 'chatbot.css'];
require_once __DIR__.'/../include/header.php';
?>

<div class="forum-container">
    <div class="forum-header">
        <h1>Forum BrainRush</h1>
        <p>Échangez avec la communauté !</p>
    </div>

    <div class="forum-actions">
        <div class="search-box">
            <form method="GET" action="/BrainRush/BrainRush/forum/search">
                <input type="text" name="q" placeholder="Rechercher dans le forum..." value="<?= htmlspecialchars($search ?? '') ?>">
                <button type="submit">🔍</button>
            </form>
        </div>
        
        <?php if (isset($_SESSION['user_id'])): ?>
        <button id="newPostBtn" class="btn btn-primary">Nouveau post</button>
        <?php endif; ?>
    </div>

    <?php if (isset($_SESSION['user_id'])): ?>
    <div id="newPostForm" class="post-form hidden">
        <h3>Créer un nouveau post</h3>
        <form method="POST" action="/BrainRush/BrainRush/forum/create">
            <div class="form-group">
                <input type="text" name="title" placeholder="Titre du post" required>
            </div>
            <div class="form-group">
                <textarea name="content" placeholder="Contenu de votre post..." rows="5" required></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Publier</button>
                <button type="button" id="cancelPost" class="btn btn-secondary">Annuler</button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <div class="forum-posts">
        <?php if (empty($posts)): ?>
            <div class="no-posts">
                <p>Aucun post trouvé.</p>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <p><a href="/BrainRush/BrainRush/auth/login">Connectez-vous</a> pour créer le premier post !</p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
            <article class="post-card">
                <div class="post-header">
                    <div class="post-meta">
                        <span class="author">👤 <?= htmlspecialchars($post['username']) ?></span>
                        <span class="date">📅 <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></span>
                        <span class="replies">💬 <?= $post['reply_count'] ?? 0 ?> réponses</span>
                        <?php if (($post['report_count'] ?? 0) > 0): ?>
                            <span class="reports">⚠️ <?= $post['report_count'] ?> signalements</span>
                        <?php endif; ?>
                    </div>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== $post['user_id']): ?>
                    <button class="report-btn" data-type="post" data-id="<?= $post['id'] ?>">🚩 Signaler</button>
                    <?php endif; ?>
                </div>
                
                <h3 class="post-title">
                    <a href="/BrainRush/BrainRush/forum/post?id=<?= $post['id'] ?>">
                        <?= htmlspecialchars($post['title']) ?>
                    </a>
                </h3>
                
                <div class="post-preview">
                    <?= htmlspecialchars(substr($post['content'], 0, 200)) ?>
                    <?php if (strlen($post['content']) > 200): ?>...<?php endif; ?>
                </div>
                
                <div class="post-actions">
                    <a href="/BrainRush/BrainRush/forum/post?id=<?= $post['id'] ?>" class="btn btn-outline">Lire la suite</a>
                </div>
            </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
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
    const newPostBtn = document.getElementById('newPostBtn');
    const newPostForm = document.getElementById('newPostForm');
    const cancelPost = document.getElementById('cancelPost');
    const reportModal = document.getElementById('reportModal');
    const reportForm = document.getElementById('reportForm');
    const closeModal = document.getElementById('closeModal');
    
    let currentReportData = null;

    if (newPostBtn) {
        newPostBtn.addEventListener('click', () => {
            newPostForm.classList.toggle('hidden');
        });
    }

    if (cancelPost) {
        cancelPost.addEventListener('click', () => {
            newPostForm.classList.add('hidden');
        });
    }

    document.querySelectorAll('.report-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            currentReportData = {
                type: e.target.dataset.type,
                id: e.target.dataset.id
            };
            reportModal.classList.remove('hidden');
        });
    });

    if (closeModal) {
        closeModal.addEventListener('click', () => {
            reportModal.classList.add('hidden');
        });
    }

    if (reportForm) {
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
    }
});
</script>

<?php 
$jsFiles = ['chatbot.js'];
require_once __DIR__.'/../include/footer.php';
?>
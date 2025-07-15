<?php 
$baseUrl = '/BrainRush/BrainRush'; 
$pageTitle = "Admin Dashboard";
$cssFiles = ['admin.css'];
require_once __DIR__.'/../../../app/include/header.php'; 
?>

<div class="admin-container">
    <h1>Tableau de bord administrateur</h1>
    
    <div class="stats-grid" id="stats-container">
        <div class="stat-card">
            <h3>👥 Utilisateurs total</h3>
            <p><?= htmlspecialchars($data['total_users'] ?? 0) ?></p>
        </div>
        
        <div class="stat-card">
            <h3>✅ Utilisateurs actifs</h3>
            <p><?= htmlspecialchars($data['active_users'] ?? 0) ?></p>
        </div>
        
        <div class="stat-card">
            <h3>👁️ Visites aujourd'hui</h3>
            <p id="live-visitors"><?= htmlspecialchars($data['today_visits'] ?? 0) ?></p>
        </div>
    </div>

    <section class="reports-section">
        <h2>Actions rapides</h2>
        <div class="admin-actions">
            <a href="<?= $baseUrl ?>/admin/users" class="btn btn-primary">Gérer les utilisateurs</a>
            <a href="<?= $baseUrl ?>/admin/moderation" class="btn btn-secondary">Modération</a>
        </div>
    </section>

    <section class="reports-section">
        <h2>Signalements en attente</h2>
        <div id="flagged-container">
            <?php if (empty($data['reports'] ?? [])): ?>
                <p>Aucun signalement en attente.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Contenu</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['reports'] as $report): ?>
                        <tr>
                            <td><?= htmlspecialchars($report['id']) ?></td>
                            <td><?= htmlspecialchars($report['content_type'] ?? 'Post') ?></td>
                            <td><?= htmlspecialchars(substr($report['content'] ?? 'Contenu signalé', 0, 50)) ?>...</td>
                            <td>
                                <button class="btn btn-danger" onclick="handleReport(<?= $report['id'] ?>, 'ban')">Bannir</button>
                                <button class="btn btn-secondary" onclick="handleReport(<?= $report['id'] ?>, 'ignore')">Ignorer</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </section>

    <section class="logs-section">
        <h2>Logs d'administration</h2>
        <div id="logs-container">
            <p>Aucune action récente.</p>
        </div>
    </section>
</div>

<script>
function handleReport(reportId, action) {
    if (confirm('Confirmer cette action ?')) {
        fetch('/BrainRush/BrainRush/admin/api/handle-report', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({reportId, action})
        }).then(() => location.reload());
    }
}
</script>

<?php 
$jsFiles = ['admin.js'];
require_once __DIR__.'/../../../app/include/footer.php'; 
?>
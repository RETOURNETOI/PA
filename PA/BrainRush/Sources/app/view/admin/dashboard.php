<!-- Sources/admin/views/dashboard.php -->
<?php require_once __DIR__.'/../../../app/include/header_dashboard.php'; ?>

<div class="admin-container">
    <h1>Tableau de bord administrateur</h1>
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Utilisateurs total</h3>
            <p><?= $data['total_users'] ?></p>
        </div>
        
        <div class="stat-card">
            <h3>Utilisateurs actifs</h3>
            <p><?= $data['active_users'] ?></p>
        </div>
        
        <div class="stat-card">
            <h3>Visites aujourd'hui</h3>
            <p><?= $data['today_visits'] ?></p>
        </div>
    </div>

    <section class="reports-section">
        <h2>Signalements en attente</h2>
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
                    <td><?= $report['id'] ?></td>
                    <td><?= $report['content_type'] ?></td>
                    <td><?= substr($report['content'], 0, 50) ?>...</td>
                    <td>
                        <a href="/admin/review/<?= $report['id'] ?>">Examiner</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</div>

<?php require_once __DIR__.'/../../../app/include/footer.php'; ?>
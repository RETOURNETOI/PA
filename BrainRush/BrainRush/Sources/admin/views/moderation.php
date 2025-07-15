<?php
require_once __DIR__.'/../../app/controller/admin_controller.php';
AdminController::requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    if ($id > 0) {
        if (isset($_POST['approve'])) {
            AdminController::approvePhoto($id);
        } elseif (isset($_POST['reject'])) {
            AdminController::rejectPhoto($id);
        }
    }

    header("Location: /BrainRush/BrainRush/admin/moderation");
    exit;
}

$pending = AdminController::getPendingPhotos();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modération des photos</title>
    <link rel="stylesheet" href="/BrainRush/BrainRush/public/assets/CSS/admin.css">
    <link rel="stylesheet" href="/BrainRush/BrainRush/public/assets/CSS/main.css">
</head>
<body>
    <div class="admin-container">
        <h1>Modération des photos</h1>

        <?php if (!empty($pending)): ?>
            <div class="moderation-grid">
                <?php foreach ($pending as $u): ?>
                    <div class="moderation-card">
                        <p><strong><?= htmlspecialchars($u['username'] ?? $u['pseudo'] ?? 'Utilisateur') ?></strong></p>
                        <?php if (isset($u['photo']) && $u['photo']): ?>
                            <img src="/BrainRush/BrainRush/public/uploads/<?= htmlspecialchars($u['photo']) ?>" height="120" alt="Photo à modérer">
                        <?php else: ?>
                            <div class="no-photo">Aucune photo</div>
                        <?php endif; ?>
                        <form method="post" class="moderation-actions">
                            <input type="hidden" name="id" value="<?= (int) $u['id'] ?>">
                            <button name="approve" class="btn btn-success">✔️ Approuver</button>
                            <button name="reject" class="btn btn-danger">❌ Rejeter</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-content">
                <p>Aucune photo en attente de modération.</p>
            </div>
        <?php endif; ?>

        <div class="back-link">
            <a href="/BrainRush/BrainRush/admin/dashboard" class="btn btn-primary">← Retour au tableau de bord</a>
        </div>
    </div>
</body>
</html>
<?php
require_once __DIR__ . '/../../controller/admin_controller.php';
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

    header("Location: moderation.php");
    exit;
}

$pending = AdminController::getPendingPhotos();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modération des photos</title>
  <link rel="stylesheet" href="../../public/assets/CSS/index.css">
  <link rel="stylesheet" href="../../public/assets/CSS/main.css">
  <link rel="stylesheet" href="../../public/assets/CSS/admin.css">
</head>
<body>
  <?php include __DIR__ . '/../../include/header_dashboard.php'; ?>

  <main class="container">
    <h1>Modération des photos</h1>

    <?php if (!empty($pending)): ?>
      <?php foreach ($pending as $u): ?>
        <div class="moderation-card">
          <p><strong><?= htmlspecialchars($u['username']) ?></strong></p>
          <img src="/uploads/<?= htmlspecialchars($u['photo']) ?>" height="80" alt="Photo à modérer">
          <form method="post">
            <input type="hidden" name="id" value="<?= (int) $u['id'] ?>">
            <button name="approve">✔️ Approuver</button>
            <button name="reject">❌ Rejeter</button>
          </form>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>Aucune photo en attente.</p>
    <?php endif; ?>
  </main>

  <script src="../../public/assets/JS/index.js"></script>
  <script src="../../public/assets/JS/main.js"></script>
</body>
</html>
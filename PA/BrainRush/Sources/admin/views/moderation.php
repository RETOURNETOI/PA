<?php
require 'AdminController.php';
AdminController::requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['approve'])) AdminController::approvePhoto($_POST['id']);
  if (isset($_POST['reject'])) AdminController::rejectPhoto($_POST['id']);
  header("Location: moderation.php");
  exit;
}

$pending = AdminController::getPendingPhotos();
?>

<!DOCTYPE html><body>
  <h1>Modération des photos</h1>

  <?php foreach ($pending as $u): ?>
    <div style="margin-bottom:15px;border:1px solid #ccc;padding:10px">
      <p><?= htmlspecialchars($u['username']) ?><br>
      <img src="/uploads/<?= htmlspecialchars($u['photo']) ?>" height="80"></p>
      <form method="post">
        <input type="hidden" name="id" value="<?= $u['id'] ?>">
        <button name="approve">✔️ Approuver</button>
        <button name="reject">❌ Rejeter</button>
      </form>
    </div>
  <?php endforeach; ?>

  <?php if (empty($pending)): ?>
    <p>Aucune photo en attente.</p>
  <?php endif; ?>
</body></html>

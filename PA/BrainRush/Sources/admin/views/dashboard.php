<?php
require 'AdminController.php';
AdminController::requireAdmin();

$userCount = AdminController::getUserCount();
$visitors = AdminController::getLiveVisitors();
$notes = AdminController::getNotifications();
?>

<!DOCTYPE html>
<html>
<head><title>Admin Dashboard</title></head>
<body>
  <h1>Dashboard</h1>
  <p>🧑‍🤝‍🧑 Utilisateurs inscrits : <strong><?= $userCount ?></strong></p>
  <p>👁️ Visiteurs actifs : <span id="live"><?= $visitors ?></span></p>

  <h2>Notifications</h2>
  <ul>
    <?php foreach ($notes as $n): ?>
      <li>
        <?= $n['type'] ?> — <?= htmlspecialchars($n['data']) ?>
        <form style="display:inline" method="post" action="notifications.php">
          <input type="hidden" name="id" value="<?= $n['id'] ?>">
          <button name="mark">Marquer comme lu</button>
        </form>
      </li>
    <?php endforeach; ?>
  </ul>

  <nav>
    <a href="users.php">Utilisateurs</a> |
    <a href="moderation.php">Modération des photos</a>
  </nav>

  <script>
    setInterval(() => {
      fetch('live.php')
        .then(r => r.text())
        .then(t => document.getElementById('live').innerText = t);
    }, 5000);
  </script>
</body>
</html>

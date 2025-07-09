<?php
require '../AdminController.php';
AdminController::requireAdmin();

$userCount = AdminController::getUserCount();
$adminCount = AdminController::getAdminCount(); // à ajouter dans AdminController
$visitors = AdminController::getLiveVisitors();
$notes = AdminController::getNotifications();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <h2>✉️ Ajouter une notification</h2>
  <form method="post" action="notifications.php">
    <input name="type" placeholder="Type (ex: info, alerte)" required>
    <input name="data" placeholder="Message" required>
    <button name="add">Envoyer</button>
  </form>

  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 30px;
      background: #f4f4f4;
    }
    h1, h2 {
      color: #333;
    }
    .cards {
      display: flex;
      gap: 20px;
      margin-bottom: 30px;
    }
    .card {
      background: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      flex: 1;
      min-width: 200px;
    }
    nav a {
      margin-right: 20px;
      text-decoration: none;
      color: #0056b3;
    }
    ul {
      list-style: none;
      padding-left: 0;
    }
    li {
      background: white;
      margin-bottom: 10px;
      padding: 10px;
      border-radius: 6px;
      box-shadow: 0 0 5px rgba(0,0,0,0.05);
    }
    form {
      display: inline;
    }
  </style>
</head>
<body>

  <h1>Tableau de bord Admin</h1>

  <div class="cards">
    <h2>📊 Répartition utilisateurs vs admins</h2>
<canvas id="userChart" width="400" height="300" style="border:1px solid #ccc; background:white;"></canvas>

<script>
  const canvas = document.getElementById('userChart');
  const ctx = canvas.getContext('2d');

  // Données venant de PHP
  const totalUsers = <?= $userCount ?>;
  const adminCount = <?= $adminCount ?>;
  const userOnlyCount = totalUsers - adminCount;

  // Configuration
  const labels = ['Utilisateurs', 'Admins'];
  const values = [userOnlyCount, adminCount];
  const colors = ['#4caf50', '#f44336'];

  const maxValue = Math.max(...values);
  const barWidth = 80;
  const gap = 60;
  const bottom = 250;

  // Dessin
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  ctx.font = "14px Arial";

  values.forEach((val, i) => {
    const barHeight = (val / maxValue) * 200;

    const x = 50 + i * (barWidth + gap);
    const y = bottom - barHeight;

    // Barre
    ctx.fillStyle = colors[i];
    ctx.fillRect(x, y, barWidth, barHeight);

    // Valeur au-dessus
    ctx.fillStyle = "#000";
    ctx.fillText(val, x + 20, y - 10);

    // Label en dessous
    ctx.fillText(labels[i], x + 5, bottom + 20);
  });
</script>


    <div class="card">
      <h2>🧑‍🤝‍🧑 Utilisateurs</h2>
      <p><strong><?= $userCount ?></strong></p>
    </div>
    <div class="card">
      <h2>👑 Admins</h2>
      <p><strong><?= $adminCount ?></strong></p>
    </div>
    <div class="card">
      <h2>👁️ Visiteurs actifs</h2>
      <p><strong><span id="live"><?= $visitors ?></span></strong></p>
    </div>
  </div>

  <h2>📢 Notifications</h2>
  <ul>
    <?php foreach ($notes as $n): ?>
      <li>
        <strong><?= htmlspecialchars($n['type']) ?></strong> — <?= htmlspecialchars($n['data']) ?>
        <form method="post" action="notifications.php">
          <input type="hidden" name="id" value="<?= $n['id'] ?>">
          <button name="mark">Marquer comme lu</button>
        </form>
      </li>
    <?php endforeach; ?>
  </ul>

  <nav>
    <a href="users.php">👤 Utilisateurs</a>
    <a href="moderation.php">🖼️ Modération</a>
  </nav>

  <script>
    setInterval(() => {
      fetch('live.php')
        .then(r => r.text())
        .then(t => document.getElementById('live').innerText = t);
    }, 5000);
  </script>
  <?php
    $logs = $pdo->query("
      SELECT l.action, l.created_at, u.username
      FROM admin_logs l
      JOIN users u ON u.id = l.admin_id
      ORDER BY l.created_at DESC
      LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);
  ?>

  <h2>📝 Dernières actions admin</h2>
  <ul>
    <?php foreach ($logs as $log): ?>
      <li><strong><?= htmlspecialchars($log['username']) ?></strong> : <?= htmlspecialchars($log['action']) ?> <em>(<?= $log['created_at'] ?>)</em></li>
    <?php endforeach; ?>
  </ul>
</body>
</html>
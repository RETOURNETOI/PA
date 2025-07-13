<?php
require_once __DIR__.'/../../traitement/Controllers/AdminController.php';
session_start();
AdminController::requireAdmin();
AdminController::handleActions();

$data = AdminController::getDashboardData();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | RETOURNETOI</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <style>
    .admin-container {
      max-width: 1200px;
      margin: 20px auto;
      padding: 20px;
    }
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin: 30px 0;
    }
    .stat-card {
      background: #fff;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      text-align: center;
    }
    .stat-card h3 {
      margin-top: 0;
      color: #555;
    }
    .stat-card p {
      font-size: 24px;
      font-weight: bold;
      margin: 10px 0 0;
      color: #2c3e50;
    }
    .flagged-post {
      background: #fff8f8;
      border-left: 4px solid #ff6b6b;
      padding: 15px;
      margin-bottom: 15px;
      border-radius: 4px;
    }
    .post-actions {
      margin-top: 10px;
      display: flex;
      gap: 10px;
    }
    .btn {
      padding: 8px 15px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 14px;
    }
    .btn-danger {
      background: #ff6b6b;
      color: white;
    }
    .btn-secondary {
      background: #6c757d;
      color: white;
    }
    .admin-section {
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      margin-bottom: 30px;
    }
    .log-entry {
      padding: 10px 0;
      border-bottom: 1px solid #eee;
    }
    .log-entry:last-child {
      border-bottom: none;
    }
  </style>
</head>
<body>
  <?php include __DIR__.'/../../includes/navbar.php'; ?>

  <div class="admin-container">
    <h1>Tableau de bord Administrateur</h1>
    
    <div class="stats-grid">
      <div class="stat-card">
        <h3>Utilisateurs</h3>
        <p><?= $data['users'] ?></p>
      </div>
      <div class="stat-card">
        <h3>Administrateurs</h3>
        <p><?= $data['admins'] ?></p>
      </div>
      <div class="stat-card">
        <h3>Visiteurs actifs</h3>
        <p><?= $data['visitors'] ?></p>
      </div>
      <div class="stat-card">
        <h3>Visites totales</h3>
        <p><?= $data['total_visits'] ?></p>
      </div>
    </div>

    <div class="admin-section">
      <h2>Publications signalées</h2>
      <?php if (empty($data['flaggedPosts'])): ?>
        <p>Aucune publication signalée.</p>
      <?php else: ?>
        <?php foreach ($data['flaggedPosts'] as $post): ?>
          <div class="flagged-post">
            <p><strong><?= htmlspecialchars($post['pseudo']) ?></strong> - 
            <em><?= date('d/m/Y H:i', strtotime($post['date_creation'])) ?></em></p>
            <p><?= htmlspecialchars($post['contenu']) ?></p>
            <div class="post-actions">
              <form method="post">
                <input type="hidden" name="user_id" value="<?= $post['user_id'] ?>">
                <input type="hidden" name="duration" value="+1 day">
                <button type="submit" name="ban_user" class="btn btn-danger">Bannir 1 jour</button>
              </form>
              <form method="post">
                <input type="hidden" name="user_id" value="<?= $post['user_id'] ?>">
                <input type="hidden" name="duration" value="+7 days">
                <button type="submit" name="ban_user" class="btn btn-danger">Bannir 7 jours</button>
              </form>
              <form method="post">
                <input type="hidden" name="user_id" value="<?= $post['user_id'] ?>">
                <input type="hidden" name="duration" value="permanent">
                <button type="submit" name="ban_user" class="btn btn-danger">Bannir définitivement</button>
              </form>
              <form method="post">
                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                <button type="submit" name="delete_post" class="btn btn-secondary">Supprimer</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <div class="admin-section">
      <h2>Historique des actions</h2>
      <?php if (empty($data['logs'])): ?>
        <p>Aucune action récente.</p>
      <?php else: ?>
        <?php foreach ($data['logs'] as $log): ?>
          <div class="log-entry">
            <strong><?= htmlspecialchars($log['pseudo']) ?></strong> : 
            <?= htmlspecialchars($log['action']) ?>
            <em>(<?= date('d/m/Y H:i', strtotime($log['created_at'])) ?>)</em>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <script>
    // Rafraîchissement des visiteurs en temps réel
    setInterval(() => {
      fetch('/admin/live_visitors')
        .then(response => response.text())
        .then(count => {
          document.querySelector('.stat-card:nth-child(3) p').textContent = count;
        });
    }, 5000);
  </script>
</body>
</html>
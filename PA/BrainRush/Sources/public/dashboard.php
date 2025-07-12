<?php
require_once __DIR__.'/../../traitement/Controllers/AdminController.php';
session_start();
AdminController::requireAdmin();

$userCount = AdminController::getUserCount();
$adminCount = AdminController::getAdminCount();
$visitors = AdminController::getLiveVisitors();
$flaggedPosts = AdminController::getFlaggedPosts();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Tableau de bord Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Tableau de bord Admin</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-2">👥 Utilisateurs</h2>
        <p class="text-2xl font-bold"><?= $userCount ?></p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-2">👑 Admins</h2>
        <p class="text-2xl font-bold"><?= $adminCount ?></p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-2">👁️ Visiteurs actifs</h2>
        <p class="text-2xl font-bold"><span id="live"><?= $visitors ?></span></p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-2">📊 Statistiques</h2>
        <p class="text-lg">Plus à venir...</p>
      </div>
    </div>

    <div class="mb-8">
      <h2 class="text-2xl font-bold mb-4">🚩 Publications signalées</h2>
      <?php if (empty($flaggedPosts)): ?>
        <p class="bg-white p-4 rounded-lg shadow">Aucune publication signalée.</p>
      <?php else: ?>
        <div class="space-y-4">
          <?php foreach ($flaggedPosts as $post): ?>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-red-500">
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="font-bold"><?= htmlspecialchars($post['pseudo']) ?></h3>
                  <p class="text-gray-500 text-sm"><?= htmlspecialchars($post['date_creation']) ?></p>
                </div>
                <div class="space-x-2">
                  <form method="post" action="ban_user.php" class="inline">
                    <input type="hidden" name="user_id" value="<?= $post['id_utilisateur'] ?>">
                    <button type="submit" name="ban_1d" class="bg-red-500 text-white px-3 py-1 rounded text-sm">
                      Bannir 1j
                    </button>
                    <button type="submit" name="ban_7d" class="bg-red-600 text-white px-3 py-1 rounded text-sm">
                      Bannir 7j
                    </button>
                    <button type="submit" name="ban_permanent" class="bg-red-700 text-white px-3 py-1 rounded text-sm">
                      Bannir
                    </button>
                  </form>
                  <form method="post" action="delete_post.php" class="inline">
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <button type="submit" class="bg-gray-500 text-white px-3 py-1 rounded text-sm">
                      Supprimer
                    </button>
                  </form>
                </div>
              </div>
              <p class="mt-2"><?= htmlspecialchars($post['contenu']) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="mb-8">
      <h2 class="text-2xl font-bold mb-4">📝 Dernières actions admin</h2>
      <div class="bg-white p-4 rounded-lg shadow">
        <?php
          $bdd = Connexion::getInstance();
          $req = $bdd->query("
            SELECT l.action, l.created_at, u.pseudo
            FROM admin_logs l
            JOIN utilisateurs u ON u.id = l.admin_id
            ORDER BY l.created_at DESC
            LIMIT 10
          ");
          $logs = $req->fetchAll(PDO::FETCH_ASSOC);
          
          if (empty($logs)) {
            echo "<p>Aucune action récente.</p>";
          } else {
            echo '<ul class="space-y-2">';
            foreach ($logs as $log) {
              echo '<li class="border-b pb-2 last:border-0">';
              echo '<strong>'.htmlspecialchars($log['pseudo']).'</strong> : ';
              echo htmlspecialchars($log['action']);
              echo ' <span class="text-gray-500 text-sm">('.htmlspecialchars($log['created_at']).')</span>';
              echo '</li>';
            }
            echo '</ul>';
          }
        ?>
      </div>
    </div>
  </div>

  <script>
    setInterval(() => {
      fetch('live.php')
        .then(r => r.text())
        .then(t => document.getElementById('live').innerText = t);
    }, 5000);
  </script>
</body>
</html>
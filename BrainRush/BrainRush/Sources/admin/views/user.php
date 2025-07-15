<?php
require_once __DIR__.'/../../app/controller/admin_controller.php';
AdminController::requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $userId = intval($_POST['user_id'] ?? 0);
    
    if ($userId > 0) {
        switch($action) {
            case 'ban':
                AdminController::banUser($userId, $_POST['duration'] ?? '1 day');
                break;
            case 'unban':
                AdminController::unbanUser($userId);
                break;
            case 'delete':
                if ($userId !== $_SESSION['user_id']) {
                    AdminController::deleteUser($userId);
                }
                break;
        }
    }
    
    header("Location: /BrainRush/BrainRush/admin/users");
    exit;
}

$users = AdminController::listUsers();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des utilisateurs</title>
    <link rel="stylesheet" href="/BrainRush/BrainRush/public/assets/CSS/admin.css">
    <link rel="stylesheet" href="/BrainRush/BrainRush/public/assets/CSS/main.css">
</head>
<body>
    <div class="admin-container">
        <h1>Gestion des utilisateurs</h1>

        <div class="users-list">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pseudo</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['pseudo']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <?php if ($user['banned_until'] && strtotime($user['banned_until']) > time()): ?>
                                <span class="status banned">Banni</span>
                            <?php else: ?>
                                <span class="status active">Actif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="post" style="display:inline">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                
                                <?php if ($user['banned_until'] && strtotime($user['banned_until']) > time()): ?>
                                    <button name="action" value="unban" class="btn btn-success">Débannir</button>
                                <?php else: ?>
                                    <select name="duration">
                                        <option value="1 hour">1 heure</option>
                                        <option value="1 day">1 jour</option>
                                        <option value="1 week">1 semaine</option>
                                        <option value="1 month">1 mois</option>
                                        <option value="permanent">Permanent</option>
                                    </select>
                                    <button name="action" value="ban" class="btn btn-warning">Bannir</button>
                                <?php endif; ?>
                                
                                <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                                    <button name="action" value="delete" class="btn btn-danger" 
                                            onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="back-link">
            <a href="/BrainRush/BrainRush/admin/dashboard" class="btn btn-primary">← Retour au tableau de bord</a>
        </div>
    </div>
</body>
</html>
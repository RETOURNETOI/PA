<?php
require 'AdminController.php';
AdminController::requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['create'])) {
    AdminController::createUser($_POST);
  }
  if (isset($_POST['update'])) {
    AdminController::updateUser($_POST['id'], $_POST);
  }
  if (isset($_POST['delete'])) {
    AdminController::deleteUser($_POST['id']);
  }
  header("Location: users.php");
  exit;
}

$users = AdminController::listUsers();
?>

<!DOCTYPE html>
<html>
<body>
  <h1>Gérer les utilisateurs</h1>

  <h2>Créer un nouvel utilisateur</h2>
  <form method="post">
    <input name="username" required placeholder="Nom">
    <input name="email" required placeholder="Email">
    <input name="password" required type="password" placeholder="Mot de passe">
    <select name="role" required>
      <option value="user">Utilisateur</option>
      <option value="admin">Administrateur</option>
    </select>
    <button name="create">Créer</button>
  </form>

  <h2>Liste des utilisateurs</h2>
  <table border="1" cellpadding="5">
    <tr><th>Nom</th><th>Email</th><th>Rôle</th><th>Actions</th></tr>
    <?php foreach ($users as $u): ?>
    <tr>
      <td><?= htmlspecialchars($u['username']) ?></td>
      <td><?= htmlspecialchars($u['email']) ?></td>
      <td><?= htmlspecialchars($u['role']) ?></td>
      <td>
        <form method="post" style="display:inline">
          <input type="hidden" name="id" value="<?= $u['id'] ?>">
          <input name="username" value="<?= htmlspecialchars($u['username']) ?>">
          <input name="email" value="<?= htmlspecialchars($u['email']) ?>">
          <select name="role">
            <option value="user" <?= $u['role'] === 'user' ? 'selected' : '' ?>>Utilisateur</option>
            <option value="admin" <?= $u['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
          </select>
          <button name="update">Modifier</button>
          <button name="delete" onclick="return confirm('Supprimer cet utilisateur ? Attention, vous ne pouvez pas supprimer votre propre compte.')">Supprimer</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tableau de bord Admin | RETOURNETOI</title>
  <link rel="stylesheet" href="../CSS/index.css">
  <link rel="stylesheet" href="../CSS/main.css">
  <link rel="stylesheet" href="../CSS/admin.css">
</head>
<body>
  <!-- Navigation (identique à index.html) -->
  <nav class="navbar">
    <div class="container">
      <a href="/" class="logo">RETOURNETOI</a>
      <div class="nav-links">
        <a href="/">Accueil</a>
        <a href="/explorer">Explorer</a>
        <a href="/profil">Profil</a>
        <a href="/admin/dashboard" class="active">Admin</a>
        <a href="/deconnexion">Déconnexion</a>
      </div>
    </div>
  </nav>

  <main class="admin-dashboard">
    <div class="container">
      <h1>Tableau de bord Admin</h1>
      
      <div class="stats-grid" id="stats-container">
        <!-- Rempli dynamiquement par JS -->
      </div>

      <section class="flagged-posts">
        <h2>🚩 Publications signalées</h2>
        <div id="flagged-container">
          <!-- Rempli dynamiquement par JS -->
        </div>
      </section>

      <section class="admin-logs">
        <h2>📝 Historique des actions</h2>
        <div id="logs-container">
          <!-- Rempli dynamiquement par JS -->
        </div>
      </section>
    </div>
  </main>

  <script src="/assets/js/admin.js"></script>
</body>
</html>
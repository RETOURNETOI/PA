<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PA - Tableau de bord</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
</head>
<body>
    <!-- Barre de navigation -->
    <nav class="navbar">
        <div class="container">
            <a href="<?= base_url() ?>" class="logo">PA</a>
            <div class="mobile-menu-icon">☰</div>
            <ul class="nav-links">
                <li><a href="<?= base_url('dashboard') ?>" class="active">Dashboard</a></li>
                <li><a href="<?= base_url('patients') ?>">Patients</a></li>
                <li><a href="<?= base_url('visits') ?>">Visites</a></li>
                <li><a href="<?= base_url('logout') ?>" class="btn-logout">Déconnexion</a></li>
            </ul>
        </div>
    </nav>

    <main class="dashboard-container">
        <section class="stats-cards">
            <div class="card">
                <h3>Patients</h3>
                <p><?= $patient_count ?></p>
            </div>
            <div class="card">
                <h3>Visites (7j)</h3>
                <p><?= $recent_visits ?></p>
            </div>
            <div class="card">
                <h3>Alertes</h3>
                <p><?= $alerts_count ?></p>
            </div>
        </section>

        <section class="recent-activity">
            <h2>Activité récente</h2>
            <div class="activity-list">
                <?php foreach($recent_activities as $activity): ?>
                <div class="activity-item">
                    <span class="date"><?= $activity['date'] ?></span>
                    <p><?= $activity['description'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <script src="<?= base_url('assets/js/main.js') ?>"></script>
    <script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
</body>
</html>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PA - Accueil</title>
    <!-- Chemins adaptés à la structure source/app/public/ -->
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/index.css') ?>">
</head>
<body>
    <!-- Barre de navigation -->
    <nav class="navbar">
        <div class="container">
            <a href="<?= base_url() ?>" class="logo">PA</a>
            <div class="mobile-menu-icon">☰</div>
            <ul class="nav-links">
                <li><a href="<?= base_url('home') ?>">Accueil</a></li>
                <li><a href="<?= base_url('patients') ?>">Patients</a></li>
                <li><a href="<?= base_url('visits') ?>">Visites</a></li>
                <li><a href="<?= base_url('login') ?>" class="btn-login">Connexion</a></li>
            </ul>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main class="main-content">
        <section class="hero">
            <h1>Plateforme Médicale PA</h1>
            <p>Gestion centralisée des patients et visites</p>
            <a href="<?= base_url('login') ?>" class="cta-button">Accéder au dashboard</a>
        </section>
    </main>

    <!-- Scripts JS -->
    <script src="<?= base_url('assets/js/main.js') ?>"></script>
    <script src="<?= base_url('assets/js/index.js') ?>"></script>

    <script>
        // Gestion du menu mobile
        document.querySelector('.mobile-menu-icon').addEventListener('click', function() {
            document.querySelector('.nav-links').classList.toggle('active');
        });
    </script>
</body>
</html>
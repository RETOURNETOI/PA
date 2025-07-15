<?php
session_start();
session_unset();
session_destroy();

// Redirection vers la route logique, pas vers un fichier physique
header("Location: /auth/login");
exit;

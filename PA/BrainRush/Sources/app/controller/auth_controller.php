<?php
function showLogin() {
    require_once __DIR__ . '/../view/auth/login.php';
}

function showRegister() {
    require_once __DIR__ . '/../view/auth/register.php';
}

function logout() {
    session_start();
    session_unset();
    session_destroy();
    header("Location: /login");
    exit;
}

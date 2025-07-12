<?php
require_once __DIR__.'/../../traitement/Controllers/AdminController.php';
session_start();
echo AdminController::getLiveVisitors();
?>
<?php
require 'AdminController.php';
session_start();
echo AdminController::getLiveVisitors();

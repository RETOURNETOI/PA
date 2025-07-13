<?php
class Database {
    private static $instance = null;
    private $connection;

    // Configurer avec TES identifiants
    private const DB_HOST = 'localhost';
    private const DB_NAME = 'brainrush';
    private const DB_USER = 'root';
    private const DB_PASS = '';

    private function __construct() {
        try {
            $this->connection = new PDO(
                'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME . ';charset=utf8',
                self::DB_USER,
                self::DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            die('Erreur DB : ' . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->connection;
    }
}

// Test connexion (à commenter en production)
// $db = Database::getInstance();
?>
<?php
class Connexion {
    private static $instance = null;
    private $pdo;
    
    private function __construct() {
        $host = 'localhost';
        $dbname = 'brainrush_db';
        $user = 'root';
        $pass = '';
        
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}
?>
<?php

class User {
    private $id;
    private $username;
    private $email;
    private $password;

    // Constructeur
    public function __construct($username = null, $email = null, $password = null) {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    // Setters
    public function setUsername($username) {
        $this->username = $username;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    // Sauvegarder un utilisateur en BDD
    public function save(PDO $pdo) {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([
            $this->username,
            $this->email,
            password_hash($this->password, PASSWORD_DEFAULT)
        ]);
        $this->id = $pdo->lastInsertId();
    }

    // Récupérer un utilisateur par ID
    public static function getById(PDO $pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $user = new User($data['username'], $data['email'], $data['password']);
            $user->id = $data['id'];
            return $user;
        }
        return null;
    }

    // Mettre à jour l'utilisateur
    public function update(PDO $pdo) {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
        $stmt->execute([
            $this->username,
            $this->email,
            password_hash($this->password, PASSWORD_DEFAULT),
            $this->id
        ]);
    }

    // Supprimer l'utilisateur
    public function delete(PDO $pdo) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$this->id]);
    }
}
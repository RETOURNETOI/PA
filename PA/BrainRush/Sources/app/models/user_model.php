<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Récupère un utilisateur par son email
     * @param string $email
     * @return object
     */
    function getUserByEmail(PDO $pdo, string $email): ?array {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    /**
     * Récupère un utilisateur par son ID
     * @param int $user_id
     * @return object
     */
    public function get_user_by_id($user_id) {
        return $this->db->get_where('users', array('id' => $user_id))->row();
    }

    /**
     * Crée un nouvel utilisateur
     * @param array $data
     * @return int
     */
    function createUser(PDO $pdo, string $username, string $email, string $passwordHash): bool {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $email, $passwordHash]);
    }

    /**
     * Met à jour un utilisateur
     * @param int $user_id
     * @param array $data
     * @return bool
     */
    public function update_user($user_id, $data) {
        $this->db->where('id', $user_id);
        return $this->db->update('users', $data);
    }

    /**
     * Supprime un utilisateur
     * @param int $user_id
     * @return bool
     */
    public function delete_user($user_id) {
        $this->db->where('id', $user_id);
        return $this->db->delete('users');
    }

    /**
     * Vérifie si un email existe déjà
     * @param string $email
     * @param int $exclude_user_id
     * @return bool
     */
    public function email_exists($email, $exclude_user_id = null) {
        $this->db->where('email', $email);
        if ($exclude_user_id) {
            $this->db->where('id !=', $exclude_user_id);
        }
        return $this->db->get('users')->num_rows() > 0;
    }

    /**
     * Récupère tous les utilisateurs
     * @return array
     */
    public function get_all_users() {
        return $this->db->get('users')->result();
    }

    /**
     * Vérifie les identifiants de connexion
     * @param string $email
     * @param string $password
     * @return object|bool
     */
    public function check_credentials($email, $password) {
        $user = $this->get_user_by_email($email);
        
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        
        return false;
    }
}
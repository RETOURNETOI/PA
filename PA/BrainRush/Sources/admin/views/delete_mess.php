<?php

require_once __DIR__ . '/../core/database.php'; // Connexion::getInstance()
require_once __DIR__ . '/../models/user_model.php';

class AdminController
{
    public static function requireAdmin(): void
    {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: /login");
            exit;
        }
    }

    public static function listUsers(): array
    {
        $bdd = Connexion::getInstance();
        $req = $bdd->prepare("SELECT id, pseudo, email, role, banned_until FROM utilisateurs");
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function createUser(array $data): void
    {
        $bdd = Connexion::getInstance();
        $req = $bdd->prepare("INSERT INTO utilisateurs (pseudo, email, mdp, role) VALUES (?, ?, ?, ?)");

        $hashedPassword = password_hash($data['mdp'], PASSWORD_DEFAULT);
        $role = in_array($data['role'], ['admin', 'user']) ? $data['role'] : 'user';

        $req->execute([
            $data['pseudo'],
            $data['email'],
            $hashedPassword,
            $role
        ]);

        self::logAction("Création de l'utilisateur " . $data['email']);
    }

    public static function updateUser(int $id, array $data): void
    {
        $bdd = Connexion::getInstance();
        $req = $bdd->prepare("UPDATE utilisateurs SET pseudo = ?, email = ?, role = ? WHERE id = ?");

        $role = in_array($data['role'], ['admin', 'user']) ? $data['role'] : 'user';

        $req->execute([
            $data['pseudo'],
            $data['email'],
            $role,
            $id
        ]);

        self::logAction("Modification de l'utilisateur ID " . $id);
    }

    public static function deleteUser(int $id): void
    {
        $bdd = Connexion::getInstance();
        if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $id) {
            return; // Empêche la suppression de soi-même
        }

        $req = $bdd->prepare("DELETE FROM utilisateurs WHERE id = ?");
        $req->execute([$id]);

        self::logAction("Suppression de l'utilisateur ID " . $id);
    }

    public static function banUser(int $id, ?string $duration = null): void
    {
        $bdd = Connexion::getInstance();
        $until = $duration ? date('Y-m-d H:i:s', strtotime($duration)) : null;

        $req = $bdd->prepare("UPDATE utilisateurs SET banned_until = ? WHERE id = ?");
        $req->execute([$until, $id]);

        self::logAction("Bannissement de l'utilisateur ID " . $id . ($duration ? " pour $duration" : " définitivement"));
    }

    public static function unbanUser(int $id): void
    {
        $bdd = Connexion::getInstance();
        $req = $bdd->prepare("UPDATE utilisateurs SET banned_until = NULL WHERE id = ?");
        $req->execute([$id]);

        self::logAction("Débannissement de l'utilisateur ID " . $id);
    }

    public static function getAdminCount(): int
    {
        $bdd = Connexion::getInstance();
        $req = $bdd->query("SELECT COUNT(*) FROM utilisateurs WHERE role = 'admin'");
        return (int)$req->fetchColumn();
    }

    public static function getUserCount(): int
    {
        $bdd = Connexion::getInstance();
        $req = $bdd->query("SELECT COUNT(*) FROM utilisateurs");
        return (int)$req->fetchColumn();
    }

    public static function getLiveVisitors(): int
    {
        $bdd = Connexion::getInstance();
        $timeout = date('Y-m-d H:i:s', strtotime('-5 minutes'));

        $req = $bdd->prepare("SELECT COUNT(*) FROM utilisateurs WHERE last_activity > ?");
        $req->execute([$timeout]);

        return (int)$req->fetchColumn();
    }

    public static function getFlaggedPosts(): array
    {
        $bdd = Connexion::getInstance();
        $forbiddenWords = require __DIR__ . '/../forbidden_words.php';

        if (!is_array($forbiddenWords) || empty($forbiddenWords)) {
            return [];
        }

        $escapedWords = array_map(function ($word) {
            return preg_quote($word, '/');
        }, $forbiddenWords);

        $wordsPattern = implode('|', $escapedWords);

        $query = "
            SELECT p.id, p.contenu, p.date_creation, u.pseudo 
            FROM publications p 
            JOIN utilisateurs u ON p.id_utilisateur = u.id 
            WHERE p.contenu REGEXP ?
            ORDER BY p.date_creation DESC
        ";

        $req = $bdd->prepare($query);
        $req->execute([$wordsPattern]);

        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function logAction(string $action): void
    {
        if (!isset($_SESSION['user'])) return;

        $bdd = Connexion::getInstance();
        $req = $bdd->prepare("INSERT INTO admin_logs (admin_id, action) VALUES (?, ?)");
        $req->execute([$_SESSION['user']['id'], $action]);
    }
    public static function getPendingPhotos(): array {
    $bdd = Connexion::getInstance();
    $req = $bdd->query("SELECT id, username, photo FROM utilisateurs WHERE photo_status = 'pending'");
    return $req->fetchAll(PDO::FETCH_ASSOC);
}

public static function approvePhoto(int $id): void {
    $bdd = Connexion::getInstance();
    $req = $bdd->prepare("UPDATE utilisateurs SET photo_status = 'approved' WHERE id = ?");
    $req->execute([$id]);
    self::logAction("Photo approuvée pour l'utilisateur ID $id");
}

public static function rejectPhoto(int $id): void {
    $bdd = Connexion::getInstance();
    $req = $bdd->prepare("UPDATE utilisateurs SET photo_status = 'rejected', photo = NULL WHERE id = ?");
    $req->execute([$id]);
    self::logAction("Photo rejetée pour l'utilisateur ID $id");
}

}
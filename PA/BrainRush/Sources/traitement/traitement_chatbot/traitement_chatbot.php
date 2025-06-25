<?php
header('Content-Type: text/plain; charset=utf-8');
session_start();

try {
    $pdo = new PDO('mysql:host=localhost;dbname=brainrush;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "❌ Erreur de connexion à la base : " . $e->getMessage();
    exit;
}

if (!isset($_SESSION['utilisateur_id'])) {
    echo "🔒 Vous devez être connecté pour utiliser le chatbot.";
    exit;
}

$userId = $_SESSION['utilisateur_id'];
$message = strtolower(trim($_POST['message'] ?? ''));

if (empty($message)) {
    echo "❗ Veuillez entrer un message.";
    exit;
}

if (preg_match('/ajouter\s+([a-z0-9_]+)\s+comme\s+ami/', $message, $matches)) {
    $pseudo = htmlspecialchars($matches[1]);

    $stmt = $pdo->prepare("SELECT id FROM Utilisateur WHERE pseudo = ?");
    $stmt->execute([$pseudo]);
    $ami = $stmt->fetch();

    if (!$ami) {
        echo "❌ Aucun utilisateur trouvé avec le pseudo \"$pseudo\".";
    } elseif ($ami['id'] == $userId) {
        echo "🙃 Tu ne peux pas t'ajouter toi-même comme ami.";
    } else {
        $check = $pdo->prepare("SELECT * FROM Ami WHERE utilisateur_id = ? AND ami_id = ?");
        $check->execute([$userId, $ami['id']]);
        if ($check->fetch()) {
            echo "ℹ️ \"$pseudo\" est déjà ton ami ou une demande est en cours.";
        } else {
            $insert = $pdo->prepare("INSERT INTO Ami (utilisateur_id, ami_id) VALUES (?, ?)");
            $insert->execute([$userId, $ami['id']]);
            echo "✅ Demande d'ami envoyée à \"$pseudo\".";
        }
    }
    exit;
}

if (preg_match('/envoyer un message à\s+([a-z0-9_]+)\s+(.+)/', $message, $matches)) {
    $pseudo = htmlspecialchars($matches[1]);
    $contenu = htmlspecialchars($matches[2]);

    $stmt = $pdo->prepare("SELECT id FROM Utilisateur WHERE pseudo = ?");
    $stmt->execute([$pseudo]);
    $dest = $stmt->fetch();

    if (!$dest) {
        echo "❌ Impossible de trouver \"$pseudo\".";
    } else {
        $insert = $pdo->prepare("INSERT INTO MessagePrive (expediteur_id, destinataire_id, contenu) VALUES (?, ?, ?)");
        $insert->execute([$userId, $dest['id'], $contenu]);
        echo "📨 Message bien envoyé à \"$pseudo\".";
    }
    exit;
}

if (str_contains($message, 'bonjour')) {
    echo "👋 Hello ! Besoin d'aide ? Tape \"aide\".";
} elseif (str_contains($message, 'aide')) {
    echo "📘 Voici ce que tu peux me demander :\n";
    echo "- \"ajouter pseudo comme ami\"\n";
    echo "- \"envoyer un message à pseudo contenu\"\n";
    echo "- \"bonjour\" ou \"aide\"";
} else {
    echo "🤔 Je n'ai pas compris. Tape \"aide\" pour voir ce que je peux faire.";
}

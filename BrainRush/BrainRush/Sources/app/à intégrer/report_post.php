<?php
$db = new PDO("mysql:host=localhost;dbname=forum;charset=utf8", "root", "");

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['post_id'])) {
    $stmt = $db->prepare("INSERT INTO reports (post_id, reason) VALUES (?, ?)");
    $stmt->execute([$data['post_id'], $data['reason'] ?? null]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

<?php
$db = new PDO("mysql:host=localhost;dbname=forum;charset=utf8", "root", "");

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['title'], $data['content'])) {
    $stmt = $db->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
    $stmt->execute([$data['title'], $data['content']]);
    echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
} else {
    echo json_encode(['success' => false]);
}

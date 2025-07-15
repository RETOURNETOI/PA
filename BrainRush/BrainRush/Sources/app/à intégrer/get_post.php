<?php
$db = new PDO("mysql:host=localhost;dbname=forum;charset=utf8", "root", "");

$posts = $db->query("SELECT * FROM posts ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

foreach ($posts as &$post) {
    $stmt = $db->prepare("SELECT * FROM replies WHERE post_id = ?");
    $stmt->execute([$post['id']]);
    $post['replies'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

echo json_encode($posts);

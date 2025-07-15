<?php
header("Content-Type: application/json");
$theme = isset($_GET['theme']) ? $_GET['theme'] : 'math';
$host = "localhost";
$dbname = "quiz";
$user = "root";
$password = "";   
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $sql = "SELECT question, option1, option2, option3, option4, answer FROM questions WHERE quiz_theme = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$theme]);
    $questions = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $questions[] = [
            'question' => $row['question'],
            'options' => [
                $row['option1'],
                $row['option2'],
                $row['option3'],
                $row['option4']
            ],
            'answer' => $row['answer']
        ];
    }
    echo json_encode($questions);
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur de connexion à la base de données"]);
}
?>


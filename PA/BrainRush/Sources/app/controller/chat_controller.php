<?php
// Sources/app/controller/chat_controller.php
require_once __DIR__.'/../models/chat_model.php';

class ChatController {
    private $model;

    public function __construct() {
        $this->model = new ChatModel();
    }

    public function getNewMessages() {
        session_start();
        $lastId = $_GET['last_id'] ?? 0;
        $messages = $this->model->getMessages($_SESSION['user_id'], $lastId);
        
        header('Content-Type: application/json');
        echo json_encode($messages);
    }

    public function sendMessage() {
        session_start();
        $data = json_decode(file_get_contents('php://input'), true);
        
        $messageId = $this->model->sendMessage(
            $_SESSION['user_id'],
            $data['receiver_id'] ?? null,
            $data['message']
        );
        
        echo json_encode(['status' => 'success', 'message_id' => $messageId]);
    }
}
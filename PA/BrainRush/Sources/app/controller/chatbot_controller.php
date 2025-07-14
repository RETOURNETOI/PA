<?php
// Sources/app/controller/chatbot_controller.php
require_once __DIR__.'/../models/chat_model.php';

class ChatbotController {
    private $chatModel;

    public function __construct() {
        $this->chatModel = new ChatModel();
    }

    public function handleMessage($userId, $message) {
        // Enregistrer le message
        $this->chatModel->saveMessage($userId, $message);
        
        // Réponse automatique basique
        $response = $this->generateResponse($message);
        
        header('Content-Type: application/json');
        echo json_encode(['response' => $response]);
    }

    private function generateResponse($message) {
        $message = strtolower($message);
        
        if (strpos($message, 'bonjour') !== false) {
            return "Bonjour ! Comment puis-je vous aider ?";
        }
        // Ajouter d'autres réponses automatiques
        
        return "Je n'ai pas compris votre demande. Un administrateur vous répondra bientôt.";
    }
}
?>
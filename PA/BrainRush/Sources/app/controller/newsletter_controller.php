<?php
// Sources/app/controller/newsletter_controller.php
require_once __DIR__.'/../models/newsletter_model.php';

class NewsletterController {
    private $model;

    public function __construct() {
        $this->model = new NewsletterModel();
    }

    public function subscribe() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            
            if ($email) {
                $success = $this->model->subscribeEmail($email);
                
                if ($success) {
                    echo json_encode(['status' => 'success', 'message' => 'Inscription réussie']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Email déjà inscrit']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Email invalide']);
            }
        }
    }

    public function unsubscribe() {
        if (isset($_GET['email'])) {
            $email = filter_var($_GET['email'], FILTER_VALIDATE_EMAIL);
            $this->model->unsubscribeEmail($email);
            
            echo "Vous avez été désinscrit de notre newsletter";
        }
    }
}
?>
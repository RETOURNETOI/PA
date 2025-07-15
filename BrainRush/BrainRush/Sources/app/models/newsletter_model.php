<?php
// Sources/app/models/newsletter_model.php
require_once __DIR__.'/../core/database.php';

class NewsletterModel extends Database {
    public function subscribeEmail($email) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?)");
            $stmt->execute([$email]);
            return true;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                return false;
            }
            throw $e;
        }
    }

    public function unsubscribeEmail($email) {
        $stmt = $this->pdo->prepare("UPDATE newsletter_subscribers SET is_active = FALSE WHERE email = ?");
        $stmt->execute([$email]);
    }

    public function getAllSubscribers() {
        $stmt = $this->pdo->query("SELECT email FROM newsletter_subscribers WHERE is_active = TRUE");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
?>
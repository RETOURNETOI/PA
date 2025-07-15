<?php
// Sources/app/controller/auth_controller.php
require_once __DIR__.'/../models/user_model.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function showLogin() {
        require_once __DIR__.'/../../app/view/auth/login.php';
    }

    public function showRegister() {
        require_once __DIR__.'/../../app/view/auth/register.php';
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: /login");
        exit();
    }

    public function forgotPassword() {
        session_start();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            
            if ($this->userModel->emailExists($email)) {
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                $this->userModel->createPasswordReset($email, $token, $expires);
                
                // Envoi d'email (simplifié)
                $resetLink = "https://votresite.com/auth/reset-password?token=$token";
                $subject = "Réinitialisation de mot de passe";
                $message = "Cliquez sur ce lien pour réinitialiser votre mot de passe : $resetLink";
                $headers = "From: no-reply@brainrush.com";
                
                mail($email, $subject, $message, $headers);
                
                $_SESSION['message'] = "Un email de réinitialisation a été envoyé";
            } else {
                $_SESSION['error'] = "Aucun compte associé à cet email";
            }
            
            header('Location: /auth/forgot-password');
            exit();
        }
        
        require_once __DIR__.'/../../app/view/auth/forgot_password.php';
    }

    public function resetPassword() {
        session_start();
        $token = $_GET['token'] ?? '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];
            
            if ($password !== $confirmPassword) {
                $_SESSION['error'] = "Les mots de passe ne correspondent pas";
                header("Location: /auth/reset-password?token=$token");
                exit();
            }
            
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $success = $this->userModel->updatePasswordWithToken($token, $hashedPassword);
            
            if ($success) {
                $_SESSION['message'] = "Mot de passe mis à jour avec succès";
                header('Location: /auth/login');
            } else {
                $_SESSION['error'] = "Lien invalide ou expiré";
                header('Location: /auth/forgot-password');
            }
            exit();
        }
        
        $validToken = $this->userModel->validateResetToken($token);
        if (!$validToken) {
            $_SESSION['error'] = "Lien invalide ou expiré";
            header('Location: /auth/forgot-password');
            exit();
        }
        
        require_once __DIR__.'/../../app/view/auth/reset_password.php';
    }
}
?>
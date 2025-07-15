<?php
require_once __DIR__.'/../models/user_model.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function showLogin() {
        require_once __DIR__.'/../../view/auth/connexion.php';
    }

    public function showRegister() {
        require_once __DIR__.'/../../view/auth/inscription.php';
    }

    public function login() {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $_SESSION['error'] = "Veuillez remplir tous les champs";
                header("Location: /BrainRush/BrainRush/auth/login");
                exit;
            }

            $user = $this->userModel->getUserByEmail($email);

            if ($user && password_verify($password, $user['mdp'])) {
                if ($this->userModel->isUserBanned($user['id'])) {
                    $_SESSION['error'] = "Votre compte a été suspendu.";
                    header("Location: /BrainRush/BrainRush/auth/login");
                    exit;
                }

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['pseudo'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['logged_in'] = true;

                $this->userModel->updateUserActivity($user['id']);

                $_SESSION['message'] = "Bienvenue " . htmlspecialchars($user['pseudo']) . " 👋";
                header("Location: /BrainRush/BrainRush/");
                exit;
            } else {
                $_SESSION['error'] = "Email ou mot de passe incorrect";
                header("Location: /BrainRush/BrainRush/auth/login");
                exit;
            }
        } else {
            $this->showLogin();
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: /BrainRush/BrainRush/auth/login");
        exit;
    }

    public function register() {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $username = trim($_POST['username'] ?? '');

            if (empty($email) || empty($password) || empty($username)) {
                $_SESSION['error'] = "Veuillez remplir tous les champs";
                header("Location: /BrainRush/BrainRush/auth/register");
                exit;
            }

            if ($password !== $confirmPassword) {
                $_SESSION['error'] = "Les mots de passe ne correspondent pas";
                header("Location: /BrainRush/BrainRush/auth/register");
                exit;
            }

            if (strlen($password) < 6) {
                $_SESSION['error'] = "Le mot de passe doit contenir au moins 6 caractères";
                header("Location: /BrainRush/BrainRush/auth/register");
                exit;
            }

            if ($this->userModel->emailExists($email)) {
                $_SESSION['error'] = "Un compte avec cet email existe déjà";
                header("Location: /BrainRush/BrainRush/auth/register");
                exit;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            if ($this->userModel->createUser($email, $hashedPassword, $username)) {
                $_SESSION['message'] = "Inscription réussie. Vous pouvez maintenant vous connecter.";
                header("Location: /BrainRush/BrainRush/auth/login");
            } else {
                $_SESSION['error'] = "Erreur lors de l'inscription";
                header("Location: /BrainRush/BrainRush/auth/register");
            }
            exit;
        } else {
            $this->showRegister();
        }
    }

    public function forgotPassword() {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');

            if (empty($email)) {
                $_SESSION['error'] = "Veuillez entrer votre email";
                header('Location: /BrainRush/BrainRush/auth/forgot-password');
                exit;
            }

            if ($this->userModel->emailExists($email)) {
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

                $this->userModel->createPasswordReset($email, $token, $expires);

                $resetLink = "http://localhost/BrainRush/BrainRush/auth/reset-password?token=$token";
                $subject = "Réinitialisation de mot de passe - BrainRush";
                $message = "Bonjour,\n\nCliquez sur ce lien pour réinitialiser votre mot de passe :\n$resetLink\n\nCe lien expire dans 1 heure.\n\nCordialement,\nL'équipe BrainRush";
                $headers = "From: no-reply@brainrush.com";

                if (mail($email, $subject, $message, $headers)) {
                    $_SESSION['message'] = "Un email de réinitialisation a été envoyé à votre adresse";
                } else {
                    $_SESSION['message'] = "Email envoyé (simulation)";
                }
            } else {
                $_SESSION['message'] = "Si cet email existe, un lien de réinitialisation a été envoyé";
            }

            header('Location: /BrainRush/BrainRush/auth/forgot-password');
            exit;
        }

        require_once __DIR__.'/../../view/auth/forgot_password.php';
    }

    public function resetPassword() {
        session_start();
        $token = $_GET['token'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($password) || empty($confirmPassword)) {
                $_SESSION['error'] = "Veuillez remplir tous les champs";
                header("Location: /BrainRush/BrainRush/auth/reset-password?token=$token");
                exit;
            }

            if ($password !== $confirmPassword) {
                $_SESSION['error'] = "Les mots de passe ne correspondent pas";
                header("Location: /BrainRush/BrainRush/auth/reset-password?token=$token");
                exit;
            }

            if (strlen($password) < 6) {
                $_SESSION['error'] = "Le mot de passe doit contenir au moins 6 caractères";
                header("Location: /BrainRush/BrainRush/auth/reset-password?token=$token");
                exit;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $success = $this->userModel->updatePasswordWithToken($token, $hashedPassword);

            if ($success) {
                $_SESSION['message'] = "Mot de passe mis à jour avec succès";
                header('Location: /BrainRush/BrainRush/auth/login');
            } else {
                $_SESSION['error'] = "Lien invalide ou expiré";
                header('Location: /BrainRush/BrainRush/auth/forgot-password');
            }
            exit;
        }

        $validToken = $this->userModel->validateResetToken($token);
        if (!$validToken) {
            $_SESSION['error'] = "Lien invalide ou expiré";
            header('Location: /BrainRush/BrainRush/auth/forgot-password');
            exit;
        }

        require_once __DIR__.'/../../view/auth/reset_password.php';
    }
}
?>
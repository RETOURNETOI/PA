<?php
// Sources/app/controller/admin_controller.php
require_once __DIR__.'/../models/user_model.php';
require_once __DIR__.'/../models/visit_model.php';
require_once __DIR__.'/../models/report_model.php';

class AdminController {
    private $userModel;
    private $visitModel;
    private $reportModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->visitModel = new VisitModel();
        $this->reportModel = new ReportModel();
    }

    public function dashboard() {
        session_start();
        if (!isset($_SESSION['admin'])) {
            header('Location: /auth/login');
            exit();
        }

        $data = [
            'total_users' => $this->userModel->countAllUsers(),
            'active_users' => $this->userModel->countActiveUsers(),
            'today_visits' => $this->visitModel->getTodayVisits(),
            'reports' => $this->reportModel->getPendingReports()
        ];

        require_once __DIR__.'/../../admin/views/dashboard.php';
    }

    public function manageUsers() {
        session_start();
        if (!isset($_SESSION['admin'])) {
            header('Location: /auth/login');
            exit();
        }

        $users = $this->userModel->getAllUsers();
        require_once __DIR__.'/../../admin/views/user.php';
    }

    public function banUser($userId) {
        session_start();
        if (!isset($_SESSION['admin'])) {
            header('Location: /auth/login');
            exit();
        }

        $this->userModel->banUser($userId);
        header('Location: /admin/users');
    }

    public function handleReport($reportId, $action) {
        session_start();
        if (!isset($_SESSION['admin'])) {
            header('Location: /auth/login');
            exit();
        }

        $this->reportModel->resolveReport($reportId, $action);
        header('Location: /admin/dashboard');
    }
}
?>
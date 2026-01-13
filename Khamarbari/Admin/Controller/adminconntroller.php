<?php
require_once __DIR__ . "/../Model/db.php";
require_once __DIR__ . "/../Model/UserModel.php";
require_once __DIR__ . "/../Model/AdminModel.php";

class AdminController {
    private $conn;
    private $userModel;
    private $adminModel;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
        $this->userModel = new UserModel($this->conn);
        $this->adminModel = new AdminModel($this->conn);
    }

    public function handleActions() {
        // --- User search (GET) ---
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'search_user') {
            // Don't exit here, just return - let loadSection handle the display
            return;
        }
          
        // --- Add admin (POST) ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_admin') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $success = $this->adminModel->addAdmin($name, $email, $password);

            if ($success) {
                header("Location: admindashboard.php?section=admins&success=1");
            } else {
                header("Location: admindashboard.php?section=admins&error=email_exists");
            }
            exit;
        }

        // --- Delete Admin ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_admin') {
            $adminId = $_POST['admin_id'];
            $superPass = $_POST['super_pass'];
            $result = $this->adminModel->deleteAdmin($adminId, $superPass);

            if ($result === true) {
                header("Location: admindashboard.php?section=admins&success=delete");
            } elseif ($result === "wrong_super_pass") {
                header("Location: admindashboard.php?section=admins&error=wrong_super_pass");
            } elseif ($result === "last_admin") {
                header("Location: admindashboard.php?section=admins&error=last_admin");
            } else {
                header("Location: admindashboard.php?section=admins&error=delete_fail");
            }
            exit;
        }

        // --- Delete user ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_user') {
            $this->userModel->deleteUser($_POST['user_id']);
            header("Location: admindashboard.php?section=users&success=deleted");
            exit;
        }

        // --- Update user ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_user') {
            $id = $_POST['user_id'];
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $nid = $_POST['nid'] ?? null;
            
            $this->userModel->updateUser($id, $name, null, $phone, $address, $nid);
            header("Location: admindashboard.php?section=users&success=updated");
            exit;
        }
    }

    public function loadSection($section) {
        switch($section) {
            case 'users':
                // Handle search parameters
                if (isset($_GET['action']) && $_GET['action'] === 'search_user') {
                    $type = $_GET['user_type'] ?? 'All';
                    $keyword = $_GET['keyword'] ?? '';
                    $users = $this->userModel->searchUsers($type, $keyword);
                } else {
                    $userType = $_GET['user_type'] ?? 'All';
                    $users = $this->userModel->getUsersByType($userType);
                }
                include __DIR__ . "/../View/sections/users.php";
                break;
            
            case 'admins':
                $admins = $this->adminModel->getAllAdmins();
                include __DIR__ . "/../View/sections/admins.php";
                break;

            default:
                echo "<p>Invalid Section</p>";
        }
    }
}
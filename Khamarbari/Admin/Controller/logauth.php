<?php
session_start();
require_once __DIR__ ."/../Model/UserModel.php";
require_once __DIR__ ."/../Model/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId   = trim($_POST['userId'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    // Server-side validation
    $errors = [];
    
    // Validate User ID
    if (empty($userId)) {
        $errors[] = "User ID is required";
    } elseif (strlen($userId) < 3) {
        $errors[] = "User ID must be at least 3 characters";
    } elseif (strlen($userId) > 50) {
        $errors[] = "User ID must not exceed 50 characters";
    } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $userId)) {
        $errors[] = "User ID contains invalid characters";
    }
    
    // Validate Password
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    } elseif (strlen($password) > 100) {
        $errors[] = "Password is too long";
    }
    
    // If validation errors exist, redirect back
    if (!empty($errors)) {
        $_SESSION['login-error_message'] = implode(". ", $errors);
        header("Location: ../View/login.php");
        exit;
    }
    
    $model = new UserModel($conn);

    // --- Admin login ---
    $admin = $model->findAdminByCredentials($userId, $password);
    if ($admin) {
        $_SESSION['user_id']    = $admin['admin_id'];
        $_SESSION['user_type']  = "Admin";
        $_SESSION['user_name']  = $admin['name'] ?? "Administrator";
        $_SESSION['login_time'] = time();

        setcookie("logged_in", "1", time() + 3600, "/");
        header("Location: ../View/admindashboard.php");
        exit;
    }

    // --- Invalid credentials ---
    $_SESSION['login-error_message'] = "Invalid Log In, Please Enter Valid Credential";
    header("Location: ../View/login.php");
    exit;
}
?>
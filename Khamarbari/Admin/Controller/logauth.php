<?php
session_start();
 require_once __DIR__ ."/../Model/UserModel.php";
require_once __DIR__ ."/../Model/db.php";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId   = trim($_POST['userId'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $model    = new UserModel($conn);

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
    header("Location:login.php");
    exit;
}

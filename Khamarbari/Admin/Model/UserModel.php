<?php
class UserModel {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function findAdminByCredentials($adminId, $passwordPlain) {
        $sql = "SELECT * FROM Admin WHERE admin_id=? AND password=?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $adminId, $passwordPlain);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result) ?: null;
    }


  public function findUserById($userId) {
        $sql = "SELECT * FROM Users WHERE user_id=?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result) ?: null;
    }


    public function createUser($userId, $name, $email, $phone, $passwordPlain, $userType, $address, $nidNullable) {
        $sql = "INSERT INTO Users (user_id, name, email, phone, password, user_type, address, nid)
                VALUES (?,?,?,?,?,?,?,?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssss", $userId, $name, $email, $phone, $passwordPlain, $userType, $address, $nidNullable);
        return mysqli_stmt_execute($stmt);
    }

}
?>

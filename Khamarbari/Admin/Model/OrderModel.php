<?php
class OrderModel {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getOrdersByUser($userId) {
        $sql = "SELECT * FROM Orders WHERE user_id=? ORDER BY order_date DESC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $orders = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }
        return $orders;
    }

    public function getOrderItems($orderId) {
        $sql = "SELECT oi.*, p.name AS product_name
                FROM Order_Items oi
                JOIN Products p ON oi.product_id = p.product_id
                WHERE oi.order_id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $orderId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
        return $items;
    }

    public function updateOrderStatus($orderId, $status) {
        $sql = "UPDATE Orders SET status=? WHERE order_id=?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $status, $orderId);
        return mysqli_stmt_execute($stmt);
    }

    public function getAllOrders() {
        $sql = "SELECT o.*, u.name AS customer_name 
                FROM Orders o 
                JOIN Users u ON o.user_id = u.user_id 
                ORDER BY o.order_date DESC";
        $result = mysqli_query($this->conn, $sql);
        $rows = [];
        while($r = mysqli_fetch_assoc($result)) $rows[] = $r;
        return $rows;
    }

    // Search orders 
    public function searchOrderById($orderId) {
        $sql = "SELECT o.*, u.name AS customer_name 
                FROM Orders o 
                JOIN Users u ON o.user_id = u.user_id
                WHERE o.order_id = ?
                ORDER BY o.order_date DESC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $orderId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $orders = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }
        return $orders;
    }
}
?>
<?php
class PaymentModel {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllPayments() {
        $sql = "SELECT p.*, o.user_id AS customer_id, u.name AS customer_name
                FROM Payments p
                JOIN Orders o ON p.order_id = o.order_id
                JOIN Users u ON o.user_id = u.user_id
                ORDER BY p.payment_date DESC";
        $result = mysqli_query($this->conn, $sql);
        $rows = [];
        while($r = mysqli_fetch_assoc($result)) $rows[] = $r;
        return $rows;
    }

    public function searchPaymentsByMethod($method) {
    $sql = "SELECT p.*, o.user_id AS customer_id, u.name AS customer_name
            FROM Payments p
            JOIN Orders o ON p.order_id = o.order_id
            JOIN Users u ON o.user_id = u.user_id
            WHERE p.method = ?
            ORDER BY p.payment_date DESC";

    $stmt = mysqli_prepare($this->conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $method);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $rows = [];
    while($r = mysqli_fetch_assoc($result)) $rows[] = $r;
    return $rows;
}


}
?>

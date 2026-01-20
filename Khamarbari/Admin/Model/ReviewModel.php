<?php
class ReviewModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
   
    public function getAllReviews() {
        $sql = "SELECT r.*, p.name AS product_name, u.name AS customer_name
                FROM Reviews r
                JOIN Products p ON r.product_id = p.product_id
                JOIN Users u ON r.user_id = u.user_id
                ORDER BY r.created_at DESC";
        $result = mysqli_query($this->conn, $sql);
        $rows = [];
        while($r = mysqli_fetch_assoc($result)) $rows[] = $r;
        return $rows;
    }

    public function deleteReview($reviewId) {
        $sql = "DELETE FROM Reviews WHERE review_id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $reviewId);
        return mysqli_stmt_execute($stmt);
    }

}
?>

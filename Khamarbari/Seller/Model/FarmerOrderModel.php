<?php
class FarmerOrderModel {
    private $conn;
    public function __construct($conn){ $this->conn = $conn; }

    public function getOrdersForFarmerView(string $farmerId): array {
        $sql = "
            SELECT o.order_id, o.status, o.order_date,
                   u.name AS customer_name, u.phone AS customer_phone
            FROM orders o
            JOIN order_items oi ON oi.order_id = o.order_id
            JOIN products p ON p.product_id = oi.product_id
            LEFT JOIN users u ON u.user_id = o.user_id
            WHERE p.farmer_id = ?
            GROUP BY o.order_id
            ORDER BY o.order_date DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $farmerId);
        $stmt->execute();

        $res = $stmt->get_result();
        $orders = [];

        while ($row = $res->fetch_assoc()) {
            $orderId = $row['order_id'];

            $itSql = "
                SELECT p.name, oi.quantity
                FROM order_items oi
                JOIN products p ON p.product_id = oi.product_id
                WHERE oi.order_id = ? AND p.farmer_id = ?
            ";
            $it = $this->conn->prepare($itSql);
            $it->bind_param("ss", $orderId, $farmerId);
            $it->execute();
            $itRes = $it->get_result();

            $items = [];
            while ($x = $itRes->fetch_assoc()) {
                $items[] = ["name"=>$x["name"], "qty"=>(int)$x["quantity"]];
            }

            $orders[] = [
                "id"=>$orderId,
                "customer_name"=>$row["customer_name"] ?? "Unknown",
                "customer_phone"=>$row["customer_phone"] ?? "N/A",
                "order_date"=>$row["order_date"] ?? null,
                "status"=>$row["status"] ?? "pending",
                "items"=>$items
            ];
        }

        return $orders;
    }

    public function updateOrderStatusForFarmer(string $orderId,string $farmerId,string $newStatus): bool {
        $allowed=["confirmed","cancelled","pending"];
        if(!in_array($newStatus,$allowed,true)) $newStatus="pending";

        $sql = "
            UPDATE orders o
            JOIN order_items oi ON oi.order_id = o.order_id
            JOIN products p ON p.product_id = oi.product_id
            SET o.status = ?
            WHERE o.order_id = ? AND p.farmer_id = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $newStatus, $orderId, $farmerId);
        return $stmt->execute();
    }
}

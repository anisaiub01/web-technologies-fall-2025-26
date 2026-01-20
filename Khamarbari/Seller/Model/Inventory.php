<?php
class Inventory {

    private static function db() {
        $pdo = new PDO("mysql:host=localhost;dbname=khamarbaridb", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

   
    public static function addProduct($farmerId, $name, $image, $price, $stock) {
        $db = self::db();

        $productId = "PRD" . time() . rand(10,99);

        $category = "grocery"; 
        $description = null;

        $stmt = $db->prepare(
            "INSERT INTO products (product_id, farmer_id, name, description, price, stock, image, category)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        return $stmt->execute([
            $productId, $farmerId, $name, $description, $price, $stock, $image, $category
        ]);
    }

    public static function getFarmerProducts($farmerId) {
        $db = self::db();
        $stmt = $db->prepare("SELECT * FROM products WHERE farmer_id=? ORDER BY created_at DESC");
        $stmt->execute([$farmerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateProduct($farmerId, $productId, $price, $stock) {
        $db = self::db();
        $stmt = $db->prepare(
            "UPDATE products SET price=?, stock=? WHERE product_id=? AND farmer_id=?"
        );
        return $stmt->execute([$price, $stock, $productId, $farmerId]);
    }

    public static function deleteProduct($farmerId, $productId) {
        $db = self::db();
        $stmt = $db->prepare("DELETE FROM products WHERE product_id=? AND farmer_id=?");
        return $stmt->execute([$productId, $farmerId]);
    }

    

    public static function getSellerOrdersForView($farmerId) {
        $db = self::db();

    
        $sql = "
            SELECT o.order_id, o.user_id, o.status, o.order_date
            FROM orders o
            JOIN order_items oi ON oi.order_id = o.order_id
            JOIN products p ON p.product_id = oi.product_id
            WHERE p.farmer_id = ?
            GROUP BY o.order_id
            ORDER BY o.order_date DESC
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$farmerId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $orders = [];

        foreach ($rows as $r) {
            $orderId = $r['order_id'];

           
            $customerName = "Unknown";
            if (!empty($r['user_id'])) {
                $u = $db->prepare("SELECT name FROM users WHERE user_id=?");
                $u->execute([$r['user_id']]);
                $customerName = $u->fetchColumn() ?: "Unknown";
            }

          
            $itemsSql = "
                SELECT p.name, oi.quantity
                FROM order_items oi
                JOIN products p ON p.product_id = oi.product_id
                WHERE oi.order_id = ? AND p.farmer_id = ?
            ";
            $it = $db->prepare($itemsSql);
            $it->execute([$orderId, $farmerId]);
            $itemsRows = $it->fetchAll(PDO::FETCH_ASSOC);

            $items = [];
            foreach ($itemsRows as $x) {
                $items[] = [
                    "name" => $x['name'],
                    "qty"  => (int)$x['quantity']
                ];
            }

            $orders[] = [
                "id"            => $orderId,
                "customer_name" => $customerName,
                "status"        => $r['status'] ?? "pending",
                "items"         => $items
            ];
        }

        return $orders;
    }

  
    public static function updateOrderStatusForSeller($orderId, $farmerId, $newStatus) {
        $db = self::db();

      
        $sql = "
            UPDATE orders o
            JOIN order_items oi ON oi.order_id = o.order_id
            JOIN products p ON p.product_id = oi.product_id
            SET o.status = ?
            WHERE o.order_id = ? AND p.farmer_id = ?
        ";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$newStatus, $orderId, $farmerId]);
    }

}

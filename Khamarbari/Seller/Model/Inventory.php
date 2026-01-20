<?php
class Inventory {

  
    private static function db() {
        $pdo = new PDO("mysql:host=localhost;dbname=khamarbaridb;charset=utf8mb4", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
    public static function getUserNameById($userId): string {
    $db = self::db();
    $stmt = $db->prepare("SELECT name FROM users WHERE user_id=? LIMIT 1");
    $stmt->execute([$userId]);
    return $stmt->fetchColumn() ?: "User";
}


    
    public static function dbPublic() {
        return self::db();
    }

   
    public static function getDefaultFarmerId(): ?string {
        $db = self::db();
        $stmt = $db->prepare("SELECT user_id FROM users WHERE user_type='Farmer' ORDER BY created_at ASC LIMIT 1");
        $stmt->execute();
        $id = $stmt->fetchColumn();
        return $id ? (string)$id : null;
    }

    
    public static function createDemoFarmer(): string {
        $db = self::db();
        $id = "FARMER001";

       
        $chk = $db->prepare("SELECT user_id FROM users WHERE user_id=? LIMIT 1");
        $chk->execute([$id]);
        if ($chk->fetchColumn()) return $id;

        $name = "Demo Farmer";
        $email = "farmer001@example.com";
        $phone = "01700000000";
        $password = password_hash("1234", PASSWORD_BCRYPT);
        $user_type = "Farmer";
        $address = "Demo Address";

        $stmt = $db->prepare("INSERT INTO users (user_id,name,email,phone,password,user_type,address) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$id,$name,$email,$phone,$password,$user_type,$address]);

        return $id;
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
            SELECT 
                o.order_id,
                o.user_id,
                o.status,
                o.order_date,
                u.name  AS customer_name,
                u.phone AS customer_phone
            FROM orders o
            JOIN order_items oi ON oi.order_id = o.order_id
            JOIN products p ON p.product_id = oi.product_id
            LEFT JOIN users u ON u.user_id = o.user_id
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
                "id"             => $orderId,
                "customer_name"  => $r['customer_name'] ?? "Unknown",
                "customer_phone" => $r['customer_phone'] ?? "N/A",
                "order_date"     => $r['order_date'] ?? null,
                "status"         => $r['status'] ?? "pending",
                "items"          => $items
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

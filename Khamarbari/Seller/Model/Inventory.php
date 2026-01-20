<?php
class Inventory {

    private static function db() {
        $pdo = new PDO("mysql:host=localhost;dbname=khamarbaridb;charset=utf8mb4", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public static function dbPublic() {
        return self::db();
    }

    public static function getUserNameById($userId): string {
        $db = self::db();
        $stmt = $db->prepare("SELECT name FROM users WHERE user_id=? LIMIT 1");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn() ?: "User";
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
        $stmt = $db->prepare("UPDATE products SET price=?, stock=? WHERE product_id=? AND farmer_id=?");
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

    /* ===================== PAYMENTS ===================== */

    public static function generatePaymentId(): string {
        $db = self::db();
        $stmt = $db->query("
            SELECT CONCAT(
                'PM',
                LPAD(
                    IFNULL(MAX(CAST(SUBSTRING(payment_id, 3) AS UNSIGNED)), 0) + 1,
                3, '0')
            ) AS next_id
            FROM payments
        ");
        return $stmt->fetchColumn() ?: "PM001";
    }

    // ✅ Create payment only if it doesn't already exist for this order
    public static function createPaymentIfNotExists($orderId, $amount, $method = 'cash', $status = 'pending'): bool {
        $db = self::db();

        $chk = $db->prepare("SELECT payment_id FROM payments WHERE order_id=? LIMIT 1");
        $chk->execute([$orderId]);
        if ($chk->fetchColumn()) {
            return true; // already exists
        }

        $paymentId = self::generatePaymentId();

        $stmt = $db->prepare("
            INSERT INTO payments (payment_id, order_id, amount, method, status, payment_date)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        return $stmt->execute([$paymentId, $orderId, $amount, $method, $status]);
    }

    // ✅ When delivery is accepted: mark delivered + create payment record
    public static function markOrderDeliveredAndPaid($orderId, $farmerId) {
        $db = self::db();

        // 1) mark delivered (only if this seller owns items in that order)
        $stmt = $db->prepare("
            UPDATE orders o
            JOIN order_items oi ON oi.order_id = o.order_id
            JOIN products p ON p.product_id = oi.product_id
            SET o.status = 'delivered'
            WHERE o.order_id = ? AND p.farmer_id = ?
        ");
        $stmt->execute([$orderId, $farmerId]);

        // 2) calculate seller amount for that order
        $totalStmt = $db->prepare("
            SELECT IFNULL(SUM(oi.quantity * p.price),0)
            FROM order_items oi
            JOIN products p ON p.product_id = oi.product_id
            WHERE oi.order_id = ? AND p.farmer_id = ?
        ");
        $totalStmt->execute([$orderId, $farmerId]);
        $amount = (float)($totalStmt->fetchColumn() ?? 0);

        // 3) create payment record (with payment_id + method + status)
        self::createPaymentIfNotExists($orderId, $amount, 'cash', 'pending');
    }

    public static function getSellerPayments($farmerId) {
        $db = self::db();

        $sql = "
            SELECT 
                p.payment_id,
                p.order_id,
                p.method,
                p.amount,
                p.status,
                p.payment_date
            FROM payments p
            JOIN order_items oi ON oi.order_id = p.order_id
            JOIN products pr ON pr.product_id = oi.product_id
            WHERE pr.farmer_id = ?
            GROUP BY p.payment_id, p.order_id, p.method, p.amount, p.status, p.payment_date
            ORDER BY p.payment_date DESC
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute([$farmerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updatePaymentStatus($paymentId, $status) {
        $db = self::db();

        $allowed = ['pending', 'accepted', 'rejected'];
        if (!in_array($status, $allowed, true)) {
            return false;
        }

        $stmt = $db->prepare("UPDATE payments SET status = ? WHERE payment_id = ?");
        return $stmt->execute([$status, $paymentId]);
    }
}

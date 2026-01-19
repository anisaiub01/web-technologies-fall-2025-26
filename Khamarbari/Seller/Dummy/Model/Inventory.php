<?php

class Inventory {

    private static function db() {
        $pdo = new PDO("mysql:host=localhost;dbname=khamarbaridb", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public static function add($data) {
        $db = self::db();

        $productId = "P" . time();

        $stmt = $db->prepare(
            "INSERT INTO products (product_id, farmer_id, name, description, price, stock, image, category)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        // We auto-fill description + category so UI stays simple
        return $stmt->execute([
            $productId,
            $data['farmer_id'],
            $data['name'],
            '',              // description auto
            $data['price'],
            $data['stock'],
            $data['image'],
            'grocery'        // category auto (must match ENUM)
        ]);
    }

    public static function getByFarmer($farmerId) {
        $db = self::db();
        $stmt = $db->prepare("SELECT * FROM products WHERE farmer_id = ? ORDER BY created_at DESC");
        $stmt->execute([$farmerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

<?php
class Inventory {

    public static function init() {
        if (!isset($_SESSION['inventory'])) {
            $_SESSION['inventory'] = [];
        }
    }

    public static function add($product)
{
    foreach ($_SESSION['inventory'] as $index => $item) {
        if (
            $item['name'] === $product['name'] &&
            $item['price'] === $product['price']
        ) {
            $_SESSION['inventory'][$index]['qty'] += $product['qty'];
            return;
        }
    }

    $_SESSION['inventory'][] = $product;
}




    public static function all() {
        return $_SESSION['inventory'];
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

public function updateProductAction() {
    Inventory::updateProduct(
        $_SESSION['user_id'],
        $_POST['product_id'],
        $_POST['price'],
        $_POST['stock']
    );
    header("Location: main.php?page=shop");
    exit;
}
public function deleteProductAction() {
    Inventory::deleteProduct(
        $_SESSION['user_id'],
        $_POST['product_id']
    );
    header("Location: main.php?page=shop");
    exit;
}


}

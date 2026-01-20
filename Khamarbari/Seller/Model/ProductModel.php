<?php
class ProductModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

   
    public function generateProductId() {
        $sql = "SELECT product_id FROM products ORDER BY product_id DESC LIMIT 1";
        $result = mysqli_query($this->conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            
            $lastId = intval(substr($row['product_id'], 1));
            $newId = $lastId + 1;
        } else {
            $newId = 1;
        }

        return "P" . str_pad($newId, 3, "0", STR_PAD_LEFT);
    }

    public function createProduct($productId, $farmerId, $name, $description, $price, $stock, $image, $category) {
        $sql = "INSERT INTO products (product_id, farmer_id, name, description, price, stock, image, category)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);

        
        mysqli_stmt_bind_param($stmt, "ssssdiss",
            $productId,
            $farmerId,
            $name,
            $description,
            $price,
            $stock,
            $image,
            $category
        );

        return mysqli_stmt_execute($stmt);
    }

    
    public function updatePriceStock($productId, $farmerId, $price, $stock) {
        $sql = "UPDATE products SET price = ?, stock = ? WHERE product_id = ? AND farmer_id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "diss", $price, $stock, $productId, $farmerId);
        return mysqli_stmt_execute($stmt);
    }

  
    public function deleteProduct($productId, $farmerId) {
        $sql = "DELETE FROM products WHERE product_id=? AND farmer_id=?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $productId, $farmerId);
        return mysqli_stmt_execute($stmt);
    }

    public function getProductsByFarmer($farmerId) {
        $sql = "SELECT * FROM products WHERE farmer_id=? ORDER BY created_at DESC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $farmerId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $products = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        return $products;
    }
}
?>

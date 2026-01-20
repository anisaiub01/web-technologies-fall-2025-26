<?php
class FarmerProductModel {
    private $conn;
    public function __construct($conn){ $this->conn = $conn; }

    public function generateProductId(): string {
        return "PRD" . time() . rand(10,99);
    }

    public function getAnyFarmerId(): ?string {
        $sql = "SELECT user_id FROM users WHERE user_type='Farmer' ORDER BY created_at ASC LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res['user_id'] ?? null;
    }

    public function createDemoFarmer(): string {
        $id = "FARMER001";
        $chk = $this->conn->prepare("SELECT user_id FROM users WHERE user_id=? LIMIT 1");
        $chk->bind_param("s", $id);
        $chk->execute();
        if ($chk->get_result()->fetch_assoc()) return $id;

        $name="Demo Farmer"; $email="farmer001@example.com"; $phone="01700000000";
        $password = password_hash("1234", PASSWORD_BCRYPT);
        $user_type="Farmer"; $address="Demo Address";

        $stmt = $this->conn->prepare("INSERT INTO users (user_id,name,email,phone,password,user_type,address) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param("sssssss", $id,$name,$email,$phone,$password,$user_type,$address);
        $stmt->execute();

        return $id;
    }

    public function createProduct($productId,$farmerId,$name,$description,$price,$stock,$imagePath,$category): bool {
        $sql="INSERT INTO products (product_id, farmer_id, name, description, price, stock, image, category)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt=$this->conn->prepare($sql);
        $stmt->bind_param("sssssdss", $productId,$farmerId,$name,$description,$price,$stock,$imagePath,$category);
        return $stmt->execute();
    }

    public function searchProductsForFarmer(string $farmerId,string $category,string $keyword): array {
        $sql="SELECT * FROM products WHERE farmer_id=?";
        $types="s"; $params=[$farmerId];

        if ($category!==""){ $sql.=" AND category=?"; $types.="s"; $params[]=$category; }
        if ($keyword!==""){ $sql.=" AND name LIKE ?"; $types.="s"; $params[]="%$keyword%"; }

        $sql.=" ORDER BY created_at DESC";
        $stmt=$this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        $res=$stmt->get_result(); $rows=[];
        while($r=$res->fetch_assoc()) $rows[]=$r;
        return $rows;
    }

    public function updateProductForFarmer(string $farmerId,string $productId,float $price,int $stock): bool {
        $sql="UPDATE products SET price=?, stock=? WHERE product_id=? AND farmer_id=?";
        $stmt=$this->conn->prepare($sql);
        $stmt->bind_param("diss", $price,$stock,$productId,$farmerId);
        return $stmt->execute();
    }

    public function deleteProductForFarmer(string $farmerId,string $productId): bool {
        $sql="DELETE FROM products WHERE product_id=? AND farmer_id=?";
        $stmt=$this->conn->prepare($sql);
        $stmt->bind_param("ss", $productId,$farmerId);
        return $stmt->execute();
    }
}

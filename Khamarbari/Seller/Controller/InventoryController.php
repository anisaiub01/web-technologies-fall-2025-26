<?php
require_once __DIR__ . '/../Model/Inventory.php';

class InventoryController {

    
    private function getFarmerId(): string {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $sessionId = $_SESSION['user_id'] ?? null;

        
        if (!empty($sessionId)) {
            $db = Inventory::dbPublic();
            $stmt = $db->prepare("SELECT user_id FROM users WHERE user_id=? AND user_type='Farmer' LIMIT 1");
            $stmt->execute([$sessionId]);
            if ($stmt->fetchColumn()) {
                return (string)$sessionId;
            }
        }

      
        $farmerId = Inventory::getDefaultFarmerId();
        if (!$farmerId) {
            $farmerId = Inventory::createDemoFarmer();
        }

        $_SESSION['user_id'] = $farmerId;
        return $farmerId;
    }

    public function handleRequest() {

        $catalog = [
            ["name"=>"Fresh Beef","image"=>"images/beef.jpg"],
            ["name"=>"Organic Mango","image"=>"images/mango.jpg"],
            ["name"=>"Dragon Fruit","image"=>"images/dragon.jpg"],
            ["name"=>"Jaggery (Gur)","image"=>"images/gur.jpg"]
        ];

        $farmerId = $this->getFarmerId();

        if (isset($_POST['add'])) {
            Inventory::addProduct(
                $farmerId,
                $_POST['name'],
                $_POST['image'],
                $_POST['price'],
                $_POST['qty']
            );

            header("Location: main.php?page=shop");
            exit;
        }

        $inventory = Inventory::getFarmerProducts($farmerId);

        return [
            "catalog"   => $catalog,
            "inventory" => $inventory
        ];
    }

    public function orderAction() {
        $farmerId = $this->getFarmerId();

        $orderId = $_POST['order_id'] ?? null;
        $action  = $_POST['action'] ?? null;

        if ($orderId && $action === 'accept') {
            Inventory::updateOrderStatusForSeller($orderId, $farmerId, "confirmed");
        } elseif ($orderId && $action === 'reject') {
            Inventory::updateOrderStatusForSeller($orderId, $farmerId, "cancelled");
        }

        header("Location: main.php?page=order");
        exit;
    }

    public function updateProductAction() {
        $farmerId = $this->getFarmerId();

        Inventory::updateProduct(
            $farmerId,
            $_POST['product_id'],
            $_POST['price'],
            $_POST['stock']
        );
        header("Location: main.php?page=shop");
        exit;
    }

    public function deleteProductAction() {
        $farmerId = $this->getFarmerId();

        Inventory::deleteProduct(
            $farmerId,
            $_POST['product_id']
        );
        header("Location: main.php?page=shop");
        exit;
    }

    public function orders() {
        $farmerId = $this->getFarmerId();
        $orders = Inventory::getSellerOrdersForView($farmerId);
        return ["orders" => $orders];
    }
}

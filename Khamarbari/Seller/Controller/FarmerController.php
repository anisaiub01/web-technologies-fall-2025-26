<?php
require_once __DIR__ . "/../Model/db.php";
require_once __DIR__ . "/../Model/FarmerProductModel.php";
require_once __DIR__ . "/../Model/FarmerOrderModel.php";


class FarmerController {
    private $conn;
    private $productModel;
    private $orderModel;

    public function __construct() {
        global $conn;
        $this->conn = $conn;

        if (session_status() === PHP_SESSION_NONE) session_start();

        $this->productModel = new FarmerProductModel($this->conn);
        $this->orderModel   = new FarmerOrderModel($this->conn);

       
        if (empty($_SESSION['user_id'])) {
            $farmerId = $this->productModel->getAnyFarmerId();
            if (!$farmerId) {
                $farmerId = $this->productModel->createDemoFarmer();
            }
            $_SESSION['user_id'] = $farmerId;
        }
    }

    private function farmerId(): string {
        return (string)$_SESSION['user_id'];
    }

    public function handleActions() {

        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_product') {
            $farmerId = $this->farmerId();

            $name = trim($_POST['name'] ?? '');
            $category = trim($_POST['category'] ?? 'grocery');
            $price = floatval($_POST['price'] ?? 0);
            $stock = intval($_POST['stock'] ?? 0);
            $description = trim($_POST['description'] ?? '');

            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $allowed = ['jpg','jpeg','png','gif','webp'];
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

                if (in_array($ext, $allowed, true)) {
                    $newFilename = uniqid('product_', true) . '.' . $ext;
                    $uploadDir = __DIR__ . '/../uploads/products/';
                    if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $newFilename)) {
                        $imagePath = 'uploads/products/' . $newFilename;
                    }
                }
            }

            $productId = $this->productModel->generateProductId();
            $ok = $this->productModel->createProduct($productId, $farmerId, $name, $description, $price, $stock, $imagePath, $category);

            header("Location: main.php?page=farmer_products&" . ($ok ? "success=1" : "error=1"));
            exit;
        }

       
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_product') {
            $farmerId = $this->farmerId();
            $productId = $_POST['product_id'] ?? '';
            $price = floatval($_POST['price'] ?? 0);
            $stock = intval($_POST['stock'] ?? 0);

            $this->productModel->updateProductForFarmer($farmerId, $productId, $price, $stock);
            header("Location: main.php?page=farmer_products&success=updated");
            exit;
        }

        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_product') {
            $farmerId = $this->farmerId();
            $productId = $_POST['product_id'] ?? '';

            $this->productModel->deleteProductForFarmer($farmerId, $productId);
            header("Location: main.php?page=farmer_products&success=deleted");
            exit;
        }

        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_order_status') {
            $farmerId = $this->farmerId();
            $orderId  = $_POST['order_id'] ?? '';
            $status   = $_POST['status'] ?? 'pending';

            $this->orderModel->updateOrderStatusForFarmer($orderId, $farmerId, $status);
            header("Location: main.php?page=farmer_orders&success=1");
            exit;
        }
    }

    public function loadSection(string $section) {
        $farmerId = $this->farmerId();

        if ($section === 'products') {
            $category = $_GET['category'] ?? '';
            $keyword  = $_GET['keyword'] ?? '';
            $products = $this->productModel->searchProductsForFarmer($farmerId, $category, $keyword);
            include __DIR__ . "/../View/farmer_sections/products.php";
            return;
        }

        if ($section === 'orders') {
            $orders = $this->orderModel->getOrdersForFarmerView($farmerId);
            include __DIR__ . "/../View/farmer_sections/orders.php";
            return;
        }

        echo "Invalid section";
    }
}

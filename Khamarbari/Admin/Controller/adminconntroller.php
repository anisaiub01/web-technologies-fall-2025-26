<?php

require_once __DIR__ . "/../Model/db.php";
require_once __DIR__ . "/../Model/UserModel.php";
require_once __DIR__ . "/../Model/AdminModel.php";
require_once __DIR__ . "/../Model/ProductModel.php";
require_once __DIR__ . "/../Model/OrderModel.php";
require_once __DIR__ . "/../Model/PaymentModel.php";
require_once __DIR__ . "/../Model/ReviewModel.php";



class AdminController {
    private $conn;
    private $userModel;
    private $adminModel;
    private $productModel;
    private $orderModel;
    private $paymentModel;
    private $reviewModel;
 

    public function __construct() {
        global $conn;
        $this->conn = $conn;
        $this->userModel = new UserModel($this->conn);
        $this->adminModel = new AdminModel($this->conn);
        $this->productModel = new ProductModel($this->conn);
        $this->orderModel = new OrderModel($this->conn);
        $this->paymentModel = new PaymentModel($this->conn);
        $this->reviewModel = new ReviewModel($this->conn);
   
    }

    public function handleActions() {
        // --- Add admin (POST) ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_admin') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $success = $this->adminModel->addAdmin($name, $email, $password);

            if ($success) {
                header("Location: admindashboard.php?section=admins&success=1");
            } else {
                header("Location: admindashboard.php?section=admins&error=email_exists");
            }
            exit;
        }

        // --- Delete Admin ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_admin') {
            $adminId = $_POST['admin_id'];

            $result = $this->adminModel->deleteAdmin($adminId);

            if ($result === true) {
                header("Location: admindashboard.php?section=admins&success=delete");
            } elseif ($result === "last_admin") {
                header("Location: admindashboard.php?section=admins&error=last_admin");
            } elseif ($result === "cannot_delete_self") {
                header("Location: admindashboard.php?section=admins&error=self_delete");
            } else {
                header("Location: admindashboard.php?section=admins&error=delete_fail");
            }
            exit;
        }

        // --- Update Admin ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_admin') {
            $adminId = $_POST['admin_id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = !empty($_POST['password']) ? $_POST['password'] : null;

            $result = $this->adminModel->updateAdmin($adminId, $name, $email, $password);

            if ($result === true) {
                header("Location: admindashboard.php?section=admins&success=update");
            } elseif ($result === "email_exists") {
                header("Location: admindashboard.php?section=admins&error=email_exists_update");
            } else {
                header("Location: admindashboard.php?section=admins&error=update_fail");
            }
            exit;
        }

        // --- Delete user ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_user') {
            $this->userModel->deleteUser($_POST['user_id']);
            header("Location: admindashboard.php?section=users&success=deleted");
            exit;
        }

        // --- Update user ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_user') {
            $id = $_POST['user_id'];
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $nid = $_POST['nid'] ?? null;
            
            $this->userModel->updateUser($id, $name, null, $phone, $address, $nid);
            header("Location: admindashboard.php?section=users&success=updated");
            exit;
        }

        // --- Product search (GET) ---
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'search_product') {
            $category = $_GET['category'] ?? '';
            $keyword = $_GET['keyword'] ?? '';
            $products = $this->productModel->searchProductsAdmin($category, $keyword);
            include __DIR__ . "/../View/sections/products.php";
            exit;
        }

        // --- Add Product (POST) ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_product') {
            $name = trim($_POST['name']);
            $category = $_POST['category'];
            $farmerId = $_POST['farmer_id'];
            $price = floatval($_POST['price']);
            $stock = intval($_POST['stock']);
            $description = trim($_POST['description'] ?? '');
            
            // Handle image upload
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $filename = $_FILES['image']['name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                if (in_array($ext, $allowed)) {
                    $newFilename = uniqid('product_', true) . '.' . $ext;
                    $uploadDir = __DIR__ . '/../uploads/products/';
                    
                    // Create directory if it doesn't exist
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $newFilename)) {
                        $imagePath = 'uploads/products/' . $newFilename;
                    }
                }
            }
            
            // Generate product ID
            $productId = $this->productModel->generateProductId();
            
            // Create product
            $success = $this->productModel->createProduct(
                $productId,
                $farmerId,
                $name,
                $description,
                $price,
                $stock,
                $imagePath,
                $category
            );
            
            if ($success) {
                header("Location: admindashboard.php?section=products&success=added");
            } else {
                header("Location: admindashboard.php?section=products&error=add_failed");
            }
            exit;
        }

        // --- Delete Product ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_product') {
            $this->productModel->deleteProduct($_POST['product_id']);
            header("Location: admindashboard.php?section=products&success=deleted");
            exit;
        }

        // --- Update Stock ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_stock') {
            $productId = $_POST['product_id'];
            $stock = intval($_POST['stock']);
            $this->productModel->overrideStock($productId, $stock);
            header("Location: admindashboard.php?section=products&success=stock_updated");
            exit;
        }

        // --- Orders management ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_order_status') {
            $orderId = $_POST['order_id'];
            $status = $_POST['status'];
            $this->orderModel->updateOrderStatus($orderId, $status);
            header("Location: admindashboard.php?section=orders");
            exit;
        }

            // --- Payments ---
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'search_payment') {
            $method = $_GET['method'] ?? '';
            $payments = $this->paymentModel->searchPaymentsByMethod($method);
            include __DIR__ . "/../View/sections/payments.php";
            exit;
        }
          // --- Reviews ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_review') {
            $this->reviewModel->deleteReview($_POST['review_id']);
            header("Location: admindashboard.php?section=reviews");
            exit;
        }
     
        
    }

    public function loadSection($section) {
        switch($section) {
            case 'users':
                // Handle search parameters
                if (isset($_GET['action']) && $_GET['action'] === 'search_user') {
                    $type = $_GET['user_type'] ?? 'All';
                    $keyword = $_GET['keyword'] ?? '';
                    $users = $this->userModel->searchUsers($type, $keyword);
                } else {
                    $userType = $_GET['user_type'] ?? 'All';
                    $users = $this->userModel->getUsersByType($userType);
                }
                include __DIR__ . "/../View/sections/users.php";
                break;
            
            case 'admins':
                $admins = $this->adminModel->getAllAdmins();
                include __DIR__ . "/../View/sections/admins.php";
                break;

           // Update the 'orders' case in the loadSection() method:

            case 'orders':
            // order ID
           if (isset($_GET['action']) && $_GET['action'] === 'search_order' && isset($_GET['order_id']) && !empty($_GET['order_id'])) {
            $orderId = $_GET['order_id'];
             $orders = $this->orderModel->searchOrderById($orderId);
             } else {
            // Show all orders
             $orders = $this->orderModel->getAllOrders();
             }
    
             include __DIR__ . "/../View/sections/orders.php";
              break;
                

            case 'products':
                $category = $_GET['category'] ?? '';
                $keyword = $_GET['keyword'] ?? '';
                $products = $this->productModel->searchProductsAdmin($category, $keyword);
                $farmers = $this->userModel->getAllFarmers();
                include __DIR__ . "/../View/sections/products.php";
                break;

               case 'payments':
                $payments = $this->paymentModel->getAllPayments();
                include __DIR__ . "/../View/sections/payments.php";
                break;
                 case 'reviews':
                $reviews = $this->reviewModel->getAllReviews();
                include __DIR__ . "/../View/sections/reviews.php";
                break;
   
            default:
                echo "<p>Invalid Section</p>";
        }
    }
}
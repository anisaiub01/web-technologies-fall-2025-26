<?php
require_once 'Model/Inventory.php';

class InventoryController {

    public function handleRequest() {

        Inventory::init();

        $catalog = [
            ["name"=>"Fresh Beef","image"=>"images/beef.jpg"],
            ["name"=>"Organic Mango","image"=>"images/mango.jpg"],
            ["name"=>"Dragon Fruit","image"=>"images/dragon.jpg"],
            ["name"=>"Jaggery (Gur)","image"=>"images/gur.jpg"]
        ];

        if (isset($_POST['add'])) {
            Inventory::add([
                "name"  => $_POST['name'],
                "price" => $_POST['price'],
                "qty"   => $_POST['qty'],
                "image" => $_POST['image']
            ]);
        }

        return [
            "catalog"   => $catalog,
            "inventory" => Inventory::all()
        ];
    }
    public function order()
{
   
    $orders = [
        [
            'id' => 1,
            'customer_name' => 'Rahim',
            'status' => 'Pending',
            'items' => [
                ['name' => 'Rice', 'qty' => 2],
                ['name' => 'Potato', 'qty' => 5],
            ]
        ]
    ];

    $data = ['orders' => $orders];
    require_once "View/order.php";
}

public function orderAction()
{
    $orderId = $_POST['order_id'] ?? null;
    $action  = $_POST['action'] ?? null;

    if ($orderId && $action === 'accept') {
        
    } elseif ($orderId && $action === 'reject') {
       
    }

    header("Location: seller/order");
    exit;
}

// ===== PRODUCT MANAGEMENT ACTIONS =====

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
public function orders() {

    // TEMP data to match View/order.php keys exactly
    $orders = [
        [
            "order_id" => "ORD001",
            "customer_name" => "Buyer One",
            "status" => "Pending",
            "items" => [
                ["product" => "Fresh Beef", "qty" => 2, "price" => 600],
                ["product" => "Mango", "qty" => 1, "price" => 200]
            ]
        ],
        [
            "order_id" => "ORD002",
            "customer_name" => "Buyer Two",
            "status" => "Confirmed",
            "items" => [
                ["product" => "Gur", "qty" => 3, "price" => 150]
            ]
        ]
    ];

    return ["orders" => $orders];
}



}

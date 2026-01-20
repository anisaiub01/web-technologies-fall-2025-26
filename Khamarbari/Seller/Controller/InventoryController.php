<?php
require_once __DIR__ . '/../Model/Inventory.php';


class InventoryController {

    public function handleRequest() {

    $catalog = [
        ["name"=>"Fresh Beef","image"=>"images/beef.jpg"],
        ["name"=>"Organic Mango","image"=>"images/mango.jpg"],
        ["name"=>"Dragon Fruit","image"=>"images/dragon.jpg"],
        ["name"=>"Jaggery (Gur)","image"=>"images/gur.jpg"]
    ];

 
    $farmerId = $_SESSION['user_id'] ?? null;


if (!$farmerId) {
    $_SESSION['user_id'] = "FARMER001";
    $farmerId = $_SESSION['user_id'];
}


   
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

    $sellerId = $_SESSION['user_id'] ?? "FARMER001";

    if ($orderId && $action === 'accept') {
        Inventory::updateOrderStatusForSeller($orderId, $sellerId, "confirmed");
    } elseif ($orderId && $action === 'reject') {
        Inventory::updateOrderStatusForSeller($orderId, $sellerId, "cancelled");
    }

    header("Location: main.php?page=order");
    exit;
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
public function orders() {

   
    $sellerId = $_SESSION['user_id'] ?? null;
    if (!$sellerId) {
        $_SESSION['user_id'] = "FARMER001";
        $sellerId = $_SESSION['user_id'];
    }

    $orders = Inventory::getSellerOrdersForView($sellerId);

    return ["orders" => $orders];
}




}

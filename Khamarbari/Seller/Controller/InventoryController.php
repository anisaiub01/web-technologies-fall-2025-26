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

}

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
}

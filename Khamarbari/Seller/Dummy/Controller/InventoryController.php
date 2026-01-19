<?php
require_once __DIR__ . "/../Model/Inventory.php";
session_start();

class InventoryController {

    public function shop() {
        // TEMP: set a farmer id if you don't have login yet
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['user_id'] = "ADM002";  // change to your farmer id
        }

        $products = Inventory::getByFarmer($_SESSION['user_id']);
        require_once __DIR__ . "/../View/shop.php";
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: main.php?action=shop");
            exit;
        }

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['user_id'] = "ADM002"; // change to your farmer id
        }

        Inventory::add([
            'farmer_id' => $_SESSION['user_id'],
            'name'      => $_POST['name'] ?? '',
            'price'     => $_POST['price'] ?? 0,
            'stock'     => $_POST['quantity'] ?? 0,
            'image'     => $_POST['image'] ?? ''
        ]);

        header("Location: main.php?action=shop");
        exit;
    }
}

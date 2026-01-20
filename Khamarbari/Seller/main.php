<?php
define('BASE_URL', '/web-technologies-fall-2025-26/Khamarbari/Seller');
session_start();

require_once __DIR__ . '/Controller/InventoryController.php';

$controller = new InventoryController();
$page = $_GET['page'] ?? 'shop';


if ($page === 'updateProduct') {
    $controller->updateProductAction();
    exit;
}

if ($page === 'deleteProduct') {
    $controller->deleteProductAction();
    exit;
}

if ($page === 'orderAction') {
    $controller->orderAction();
    exit;
}


if ($page === 'order') {
    $data = $controller->orders();             
    require __DIR__ . '/View/order.php';
    exit;
}


$data = $controller->handleRequest();
require __DIR__ . '/View/shop.php';

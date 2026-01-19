<?php
define('BASE_URL', '/web-technologies-fall-2025-26/Khamarbari/Seller');

session_start();
require_once __DIR__ . '/Controller/InventoryController.php';

$controller = new InventoryController();
$page = $_GET['page'] ?? 'shop';

/* ===== ACTION ROUTES (run BEFORE loading $data) ===== */
if ($page === 'updateProduct') {
    $controller->updateProductAction();
    exit;
}

if ($page === 'deleteProduct') {
    $controller->deleteProductAction();
    exit;
}

if ($page === 'order') {
    // IMPORTANT: call now so $data['orders'] exists
    $data = $controller->orders();   // <-- you must create this method (next step)
    require __DIR__ . '/View/order.php';
    exit;
}

/* ===== DEFAULT SHOP PAGE ===== */
$data = $controller->handleRequest();
require __DIR__ . '/View/shop.php';

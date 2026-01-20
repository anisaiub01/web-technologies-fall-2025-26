<?php
define('BASE_URL', '/web-technologies-fall-2025-26/Khamarbari/Seller');
session_start();

require_once __DIR__ . '/Controller/InventoryController.php';
require_once __DIR__ . '/Controller/FarmerController.php';

// ✅ define $page BEFORE using it
$page = $_GET['page'] ?? 'shop';

/* ---------- FARMER DASHBOARD ROUTES ---------- */
if ($page === 'farmer_products' || $page === 'farmer_orders') {
    $fc = new FarmerController();
    $fc->handleActions();

    if ($page === 'farmer_products') {
        $fc->loadSection('products');
    } else {
        $fc->loadSection('orders');
    }
    exit;
}

/* ---------- EXISTING SELLER ROUTES ---------- */
$controller = new InventoryController();

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
if ($page === 'payment') {
    $data = $controller->payments();
    require __DIR__ . '/View/payment.php';
    exit;
}
if ($page === 'updatePaymentStatus') {
    $controller->updatePaymentStatusAction();
    exit;
}


$data = $controller->handleRequest();
require __DIR__ . '/View/shop.php';

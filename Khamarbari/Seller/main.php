
<?php

define('BASE_URL', '/web-technologies-fall-2025-26/Khamarbari/Seller');

session_start();

require_once 'Controller/InventoryController.php';

$controller = new InventoryController();
$data = $controller->handleRequest();

$page = $_GET['page'] ?? 'shop';

if ($page === 'order') {
    require 'View/order.php';
} else {
    require 'View/shop.php';
}


?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/Controller/InventoryController.php";

$controller = new InventoryController();
$action = $_GET['action'] ?? 'shop';

if ($action === 'add') {
    $controller->add();
} else {
    $controller->shop();
}

<?php
session_start();

require_once 'controllers/InventoryController.php';

$controller = new InventoryController();
$data = $controller->handleRequest();

require 'views/shop.php';

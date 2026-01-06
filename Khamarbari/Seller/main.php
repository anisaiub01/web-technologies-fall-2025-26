<?php
session_start();

require_once 'Controller/InventoryController.php';

$controller = new InventoryController();
$data = $controller->handleRequest();

require 'View/shop.php';

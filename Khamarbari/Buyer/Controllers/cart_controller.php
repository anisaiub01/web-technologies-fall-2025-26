<?php
session_start();
require_once '../Models/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_cart'])) {
    if (!empty($_POST['selected_products'])) {
        foreach ($_POST['selected_products'] as $productId) {
            $qty = $_POST['qty_' . $productId];
            if ($qty > 0) { 
                addToCart($_SESSION['user_id'], $productId, $qty); 
            }
        }
        echo "<script>alert('Products added to your cart!'); window.location.href='../views/customer_dashboard.php';</script>";
        exit();
    }
}
?>
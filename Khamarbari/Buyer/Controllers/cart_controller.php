<?php
require_once '../Models/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_cart'])) {
    if (isset($_POST['selected_products']) && !empty($_POST['selected_products'])) {
        foreach ($_POST['selected_products'] as $pName) {
            $key = str_replace(' ', '_', $pName);
            $qty = $_POST['qty_' . $key];
            $prc = $_POST['price_' . $key];

            if (is_numeric($qty) && $qty > 0) {
                addToCart(1, $pName, $qty, $prc); 
            }
        }
        // Properly terminated with semicolon to avoid parse errors
        echo "<script>
                alert('your products are added in the cart!!');
                window.location.href = '../views/customer_dashboard.php';
              </script>";
        exit();
    }
}
?>
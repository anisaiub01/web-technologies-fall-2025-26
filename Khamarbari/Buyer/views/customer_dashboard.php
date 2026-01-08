
<!DOCTYPE html>
<html>
<head>
    <title>KHAMARBARI - Customer</title>
    <link rel="stylesheet" type="text/css" href="../public/style.css">
    <script src="../Controllers/js/search_ajax.js"></script>
</head>
<body>
    <div class="container">
        <h1>Welcome customer ! Choose your desire Products...</h1>

        <div class="search-section">
            <input type="text" id="searchBox" onkeyup="searchProduct()" placeholder="Search products...">
            <div id="searchFeedback"></div>
        </div>

        <form action="../Controllers/cart_controller.php" method="POST" onsubmit="return validateCart()">
            <div class="product-list">
                <?= $productListHTML ?> 
            </div>
            <button type="submit" name="confirm_cart" class="btn">Confirm Cart</button>
        </form>
    </div>
</body>
</html>
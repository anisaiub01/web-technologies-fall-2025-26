<!DOCTYPE html>
<html>
<head>
    <title>Seller Inventory</title>
    <link rel="stylesheet" href="/web-technologies-fall-2025-26/Khamarbari/Seller/Dummy/public/styles.css">
</head>
<body>

<div class="upper_db">
    <h2>Seller Dashboard</h2>
</div>

<div class="topbar">
    <h3>Your Products</h3>
</div>

<form method="POST" action="main.php?action=add">
    <input type="text" name="name" placeholder="Product name" required>
    <input type="number" name="price" placeholder="Price" required>
    <input type="number" name="quantity" placeholder="Quantity" required>
    <input type="text" name="image" placeholder="Image path (optional)">
    <button id="Btn" type="submit">Add Product</button>
</form>

<hr>

<div class="shop">
<?php if (empty($products)) { ?>
    <p>No products yet.</p>
<?php } else { ?>
    <?php foreach ($products as $p) { ?>
        <div class="inv-item">
            <img src="<?php echo htmlspecialchars($p['image'] ?? ''); ?>" alt="">
            <h3><?php echo htmlspecialchars($p['name']); ?></h3>
            <p>৳<?php echo htmlspecialchars($p['price']); ?></p>
            <p>Qty: <?php echo htmlspecialchars($p['stock']); ?></p>
        </div>
    <?php } ?>
<?php } ?>
</div>

</body>
</html>

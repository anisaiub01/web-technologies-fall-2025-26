<!DOCTYPE html>
<html>
<head>
    <title>Owner Inventory</title>
    
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/style.css">


</head>
<body>

<div class="topbar">
    <h2 class="upper_db">Owner – Dashboard</h2>
    <a class="orders-btn" href="<?php echo BASE_URL; ?>/main.php?page=order">Orders</a>

</div>


<div class="shop">
<?php foreach ($data['catalog'] as $p): ?>
    <div class="card">
        <img src="<?php echo $p['image']; ?>">
        <h3><?php echo $p['name']; ?></h3>

        <form method="post">
            <input type="hidden" name="name" value="<?php echo $p['name']; ?>">
            <input type="hidden" name="image" value="<?php echo $p['image']; ?>">

            <input type="number" name="price" placeholder="Price" required>
            <input type="number" name="qty" placeholder="Quantity" required>

            <button id="Btn" name="add">Add to Inventory</button>
        </form>
    </div>
<?php endforeach; ?>
</div>

<hr>

<h3>Current Inventory</h3>

<div class="inventory">
<?php if (empty($data['inventory'])): ?>
    <p>No products added</p>
<?php else: ?>
    <?php foreach ($data['inventory'] as $i): ?>
        <div class="inv-item">
    <img src="<?php echo $i['image']; ?>">
    <p><?php echo $i['name']; ?></p>

    <!-- UPDATE -->
    <form method="post" action="<?php echo BASE_URL; ?>/main.php?page=updateProduct">
        <input type="hidden" name="product_id" value="<?php echo $i['product_id']; ?>">

        <input type="number" name="price" value="<?php echo $i['price']; ?>" required>
        <input type="number" name="stock" value="<?php echo $i['stock']; ?>" required>

        <button type="submit">Update</button>
    </form>

    <!-- DELETE -->
    <form method="post"
          action="<?php echo BASE_URL; ?>/main.php?page=deleteProduct"
          onsubmit="return confirm('Delete this product?');">
        <input type="hidden" name="product_id" value="<?php echo $i['product_id']; ?>">
        <button type="submit">Delete</button>
    </form>
</div>

    <?php endforeach; ?>
<?php endif; ?>
</div>

</body>
</html>

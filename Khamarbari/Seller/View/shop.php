<!DOCTYPE html>
<html>
<head>
    <title>Owner Inventory</title>
    
    <link rel="stylesheet" href="/public/style.css">
</head>
<body>

<div class="topbar">
    <h2 class="upper_db">Owner – Dashboard</h2>
    <a class="orders-btn" href="seller/order">Orders</a>
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
            <p>৳ <?php echo $i['price']; ?></p>
            <p>Qty: <?php echo $i['qty']; ?></p>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
</div>

</body>
</html>

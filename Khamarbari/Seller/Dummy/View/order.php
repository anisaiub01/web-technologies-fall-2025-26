<!DOCTYPE html>
<html>
<head>
    <title>Orders</title>
    <link rel="stylesheet" href="/web-technologies-fall-2025-26/Khamarbari/Seller/Dummy/public/styles.css">

</head>
<body>


<h2>Orders</h2>

<?php foreach ($orders as $o): ?>
<div class="order-card">
    <p>Order ID: <?= $o['id'] ?></p>
    <p>Total: ৳<?= $o['total_price'] ?></p>
    <p>Status: <?= $o['status'] ?></p>

    <form method="POST" action="main.php?action=updateOrder">
        <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
        <select name="status">
            <option value="accepted">Accept</option>
            <option value="shipped">Shipped</option>
            <option value="delivered">Delivered</option>
        </select>
        <button>Update</button>
    </form>
</div>
<?php endforeach; ?>
</body>
</html>

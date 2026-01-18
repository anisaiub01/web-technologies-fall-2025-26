<!DOCTYPE html>
<html>
<head>
    <title>Orders</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/style.css">


</head>
<body>

<div class="topbar">
    <h2 class="upper_db">Orders</h2>
    <a class="orders-btn" href="<?php echo BASE_URL; ?>/main.php?page=shop">Back</a>

</div>

<?php
$orders = $data['orders'] ?? [];
?>

<?php if (empty($orders)): ?>
    <p>No orders found</p>
<?php else: ?>
    <?php foreach ($orders as $o): ?>
        <div class="order-card">
            <p><b>Customer:</b> <?php echo $o['customer_name']; ?></p>
            <p><b>Status:</b> <?php echo $o['status']; ?></p>

            <?php foreach ($o['items'] as $it): ?>
                <p>
                    <?php echo $it['name']; ?> —
                    Qty: <?php echo $it['qty']; ?>
                </p>
            <?php endforeach; ?>

            <?php if ($o['status'] === 'Pending'): ?>
                <form method="post" action="seller/orderAction">
                    <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">

                    <button name="action" value="accept">Accept</button>
                    <button name="action" value="reject">Reject</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>

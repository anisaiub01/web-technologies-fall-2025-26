<!DOCTYPE html>
<html>
<head>
    <title>Orders</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/style.css">
</head>
<body>

<div class="topbar">
    <div class="topbar-left">
        <span class="welcome-text">
            Welcome, <?php echo htmlspecialchars($data['userName'] ?? $_SESSION['user_id']); ?>
        </span>
    </div>

    <h2 class="upper_db">Owner – Dashboard</h2>

    <div class="topbar-right">
        <a class="orders-btn" href="<?php echo BASE_URL; ?>/main.php?page=order">Orders</a>
        <a class="orders-btn logout-btn" href="<?php echo BASE_URL; ?>/logout.php">Logout</a>
    </div>
</div>


<?php $orders = $data['orders'] ?? []; ?>

<?php if (empty($orders)): ?>
    <p>No orders found</p>
<?php else: ?>

<div class="table-wrap">
  <table class="inv-table" border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse:collapse;">
    <thead>
      <tr>
        <th>Order ID</th>
        <th>Customer</th>
        <th>Phone No</th>
        <th>Items</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach ($orders as $o): ?>
        <tr>
          <td><?php echo htmlspecialchars($o['id']); ?></td>

          <td>
            <?php echo htmlspecialchars($o['customer_name'] ?? 'Unknown'); ?><br>
            <?php if (!empty($o['order_date'])): ?>
              <small><?php echo htmlspecialchars($o['order_date']); ?></small>
            <?php endif; ?>
          </td>

          <td><?php echo htmlspecialchars($o['customer_phone'] ?? 'N/A'); ?></td>

          <td>
            <?php foreach (($o['items'] ?? []) as $it): ?>
              <div>
                <?php echo htmlspecialchars($it['name']); ?>
                — Qty: <?php echo (int)($it['qty']); ?>
              </div>
            <?php endforeach; ?>
          </td>

          <td><?php echo htmlspecialchars($o['status'] ?? 'pending'); ?></td>

          <td>
            <?php if (strtolower($o['status'] ?? '') === 'pending'): ?>
              <form method="post" action="<?php echo BASE_URL; ?>/main.php?page=orderAction"
                    style="display:flex; gap:8px; align-items:center;">
                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($o['id']); ?>">
                <button name="action" value="accept" type="submit">Accept</button>
                <button name="action" value="reject" type="submit">Reject</button>
              </form>
            <?php else: ?>
              <span>—</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php endif; ?>

</body>
</html>

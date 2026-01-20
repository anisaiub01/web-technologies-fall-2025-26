<?php
$payments = $data['payments'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments</title>

    <!-- ✅ CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/style.css">
</head>
<body>

<div class="topbar">
    <div class="topbar-left">
        <strong class="welcome-text">Payments</strong>
    </div>

    <div class="topbar-right">
        <a class="orders-btn" href="<?php echo BASE_URL; ?>/main.php?page=shop">Back</a>
    </div>
</div>

<div class="table-wrap">
    <table class="inv-table">
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Order ID</th>
                <th>Method</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Payment Date</th>
            </tr>
        </thead>

        <tbody>
        <?php if (empty($payments)): ?>
            <tr>
                <td colspan="6">No payments found</td>
            </tr>
        <?php else: ?>
            <?php foreach ($payments as $p): ?>
            <tr>
                <td><?php echo htmlspecialchars($p['payment_id']); ?></td>
                <td><?php echo htmlspecialchars($p['order_id']); ?></td>
                <td><?php echo htmlspecialchars($p['method']); ?></td>
                <td><?php echo htmlspecialchars($p['amount']); ?></td>

                <td>
                    <form method="post" action="<?php echo BASE_URL; ?>/main.php?page=updatePaymentStatus">
                        <input type="hidden" name="payment_id" value="<?php echo htmlspecialchars($p['payment_id']); ?>">

                        <select name="status">
                            <option value="pending"  <?php if (($p['status'] ?? '') === 'pending')  echo 'selected'; ?>>Pending</option>
                            <option value="accepted" <?php if (($p['status'] ?? '') === 'accepted') echo 'selected'; ?>>Accepted</option>
                            <option value="rejected" <?php if (($p['status'] ?? '') === 'rejected') echo 'selected'; ?>>Rejected</option>
                        </select>

                        <button type="submit">Update</button>
                    </form>
                </td>

                <td><?php echo htmlspecialchars($p['payment_date']); ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>

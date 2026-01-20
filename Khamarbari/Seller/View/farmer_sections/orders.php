<?php
// expects: $orders
?>

<h2>My Orders</h2>

<?php if (isset($_GET['success'])): ?>
    <p style="color:green;">✅ Order status updated</p>
<?php endif; ?>

<div class="table-wrap">
<table class="inv-table" border="1" cellpadding="10" cellspacing="0"
       style="width:100%; border-collapse:collapse;">
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
    <?php if (empty($orders)): ?>
        <tr>
            <td colspan="6">No orders found</td>
        </tr>
    <?php else: ?>
        <?php foreach ($orders as $o): ?>
        <tr>
            <td><?php echo htmlspecialchars($o['id']); ?></td>

            <td>
                <?php echo htmlspecialchars($o['customer_name']); ?><br>
                <?php if (!empty($o['order_date'])): ?>
                    <small><?php echo htmlspecialchars($o['order_date']); ?></small>
                <?php endif; ?>
            </td>

            <td><?php echo htmlspecialchars($o['customer_phone']); ?></td>

            <td>
                <?php foreach (($o['items'] ?? []) as $it): ?>
                    <div>
                        <?php echo htmlspecialchars($it['name']); ?>
                        — Qty: <?php echo (int)$it['qty']; ?>
                    </div>
                <?php endforeach; ?>
            </td>

            <td><?php echo htmlspecialchars($o['status']); ?></td>

            <td>
                <?php if (strtolower($o['status']) === 'pending'): ?>
                    <form method="post" style="display:flex; gap:8px;">
                        <input type="hidden" name="action" value="update_order_status">
                        <input type="hidden" name="order_id"
                               value="<?php echo htmlspecialchars($o['id']); ?>">
                        <button type="submit" name="status" value="confirmed">
                            ✅ Accept
                        </button>
                        <button type="submit" name="status" value="cancelled">
                            ❌ Reject
                        </button>
                    </form>
                <?php else: ?>
                    —
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
</div>

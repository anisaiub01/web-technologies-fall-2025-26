<h2 class="section-title">Orders</h2>

<div class="controls">
  <form method="GET" action="admindashboard.php" class="search-form">
    <input type="hidden" name="section" value="orders">
    <input type="text" name="order_id" placeholder="Enter Order ID to search" value="<?php echo htmlspecialchars($_GET['order_id'] ?? ''); ?>">
    <input type="hidden" name="action" value="search_order">
    <button type="submit">Search Order</button>
  </form>
</div>

<?php if(!empty($orders)): ?>
<h3>Order History<?php if(isset($_GET['order_id']) && !empty($_GET['order_id'])): ?> - Search Result for Order ID: <?php echo htmlspecialchars($_GET['order_id']); ?><?php endif; ?></h3>
<div class="table-container">
  <table class="order-table">
    <thead>
      <tr>
        <th>Order ID</th>
        <th>User</th>
        <th>Total</th>
        <th>Status</th>
        <th>Date</th>
        <th>Items</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($orders as $o): ?>
      <tr>
        <td><?php echo $o['order_id']; ?></td>
        <td><?php echo htmlspecialchars($o['customer_name']); ?></td>
        <td><?php echo $o['total_amount']; ?></td>
        <td>
          <form method="POST" action="admindashboard.php?section=orders">
            <input type="hidden" name="action" value="update_order_status">
            <input type="hidden" name="order_id" value="<?php echo $o['order_id']; ?>">
            <select name="status">
              <?php 
              $statuses = ['pending','confirmed','shipped','delivered','cancelled']; 
              foreach($statuses as $s){
                  $sel = ($s==$o['status'])?'selected':''; 
                  echo "<option value='$s' $sel>$s</option>";
              }
              ?>
            </select>
        </td>
        <td><?php echo $o['order_date']; ?></td>
        <td>
          <ul>
            <?php $items = $this->orderModel->getOrderItems($o['order_id']); 
            foreach($items as $it){
                echo '<li>'.htmlspecialchars($it['product_name']).' (Qty: '.$it['quantity'].')</li>';
            } ?>
          </ul>
        </td>
        <td><button type="submit" class="btn-search">Update</button></td>
          </form>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php else: ?>
<p>No orders found<?php if(isset($_GET['order_id']) && !empty($_GET['order_id'])): ?> for Order ID: <?php echo htmlspecialchars($_GET['order_id']); ?><?php endif; ?>.</p>
<?php endif; ?>
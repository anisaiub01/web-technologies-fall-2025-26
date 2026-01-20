<!DOCTYPE html>
<html>
<head>
    <title>Owner Inventory</title>
    
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

<div class="table-wrap">
  <?php if (empty($data['inventory'])): ?>
      <p>No products added</p>
  <?php else: ?>
    <table class="inv-table" border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse:collapse;">
      <thead>
        <tr>
          <th>Image</th>
          <th>Product</th>
          <th>Price</th>
          <th>Stock</th>
          <th>Update</th>
          <th>Delete</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($data['inventory'] as $i): ?>
          <tr>
            <td style="width:80px;">
              <img src="<?php echo htmlspecialchars($i['image']); ?>"
                   alt="Product"
                   style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
            </td>

            <td><?php echo htmlspecialchars($i['name']); ?></td>

            
            <td>
              <form method="post" action="<?php echo BASE_URL; ?>/main.php?page=updateProduct" style="display:flex; gap:8px; align-items:center;">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($i['product_id']); ?>">

                <input type="number"
                       name="price"
                       value="<?php echo htmlspecialchars($i['price']); ?>"
                       required
                       style="width:100px; padding:6px;">
            </td>

            <td>
                <input type="number"
                       name="stock"
                       value="<?php echo htmlspecialchars($i['stock']); ?>"
                       required
                       style="width:100px; padding:6px;">
            </td>

            <td>
                <button type="submit" style="padding:6px 12px;">Save</button>
              </form>
            </td>

           
            <td>
              <form method="post"
                    action="<?php echo BASE_URL; ?>/main.php?page=deleteProduct"
                    onsubmit="return confirm('Delete this product?');">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($i['product_id']); ?>">
                <button type="submit" style="padding:6px 12px;">Delete</button>
              </form>
            </td>

          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>


</body>
</html>

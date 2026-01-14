<h2 class="section-title">Product Management</h2>

<?php
// Display success/error messages
if (isset($_GET['success'])) {
    $message = '';
    switch($_GET['success']) {
        case 'added': $message = 'Product added successfully!'; break;
        case 'deleted': $message = 'Product deleted successfully!'; break;
        case 'stock_updated': $message = 'Stock updated successfully!'; break;
    }
    if ($message) {
        echo "<div class='alert alert-success'>{$message}</div>";
    }
}

if (isset($_GET['error'])) {
    $message = '';
    switch($_GET['error']) {
        case 'add_failed': $message = 'Failed to add product. Please try again.'; break;
        case 'no_farmer': $message = 'Please select a farmer.'; break;
        case 'invalid_farmer': $message = 'Invalid farmer ID. Farmer does not exist.'; break;
    }
    if ($message) {
        echo "<div class='alert alert-error'>{$message}</div>";
    }
}
?>

<div class="controls">
  <form method="GET" action="admindashboard.php" class="search-form">
    <input type="hidden" name="section" value="products">
    <select name="category">
      <option value="">All</option>
      <option value="vegetable" <?php if(($category ?? '')=='vegetable') echo 'selected'; ?>>Vegetable</option>
      <option value="fruit" <?php if(($category ?? '')=='fruit') echo 'selected'; ?>>Fruit</option>
      <option value="grain" <?php if(($category ?? '')=='grain') echo 'selected'; ?>>Grain</option>
      <option value="dairy" <?php if(($category ?? '')=='dairy') echo 'selected'; ?>>Dairy</option>
      <option value="meat" <?php if(($category ?? '')=='meat') echo 'selected'; ?>>Meat</option>
      <option value="fish" <?php if(($category ?? '')=='fish') echo 'selected'; ?>>Fish</option>
      <option value="grocery" <?php if(($category ?? '')=='grocery') echo 'selected'; ?>>Grocery</option>
    </select>
    <input type="hidden" name="action" value="search_product">
    <input type="text" name="keyword" placeholder="Search products..." value="<?php echo htmlspecialchars($keyword ?? ''); ?>">
    <button type="submit">Search</button>
  </form>
  
  <button class="btn-add" onclick="document.getElementById('add-product-modal').style.display='flex'">Add New Product</button>
</div>

<!-- Add Product Modal -->
<div id="add-product-modal" class="modal" style="display:none;">
  <div class="modal-content">
    <span class="close-modal" onclick="document.getElementById('add-product-modal').style.display='none'">&times;</span>
    <h3>Add New Product</h3>
    <form method="POST" action="admindashboard.php?section=products" enctype="multipart/form-data">
      <input type="hidden" name="action" value="add_product">
      
      <label>Product Name *</label>
      <input type="text" name="name" required>
      
      <label>Category *</label>
      <select name="category" required>
        <option value="">Select Category</option>
        <option value="vegetable">Vegetable</option>
        <option value="fruit">Fruit</option>
        <option value="grain">Grain</option>
        <option value="dairy">Dairy</option>
        <option value="meat">Meat</option>
        <option value="fish">Fish</option>
        <option value="grocery">Grocery</option>
      </select>
      
      <label>Farmer *</label>
      <select name="farmer_id" required>
        <option value="">Select Farmer</option>
        <?php if (!empty($farmers)): ?>
          <?php foreach ($farmers as $farmer): ?>
            <option value="<?php echo htmlspecialchars($farmer['user_id']); ?>">
              <?php echo htmlspecialchars($farmer['name']) . ' (' . htmlspecialchars($farmer['user_id']) . ')'; ?>
            </option>
          <?php endforeach; ?>
        <?php else: ?>
          <option value="" disabled>No farmers available</option>
        <?php endif; ?>
      </select>
      
      <label>Price (৳) *</label>
      <input type="number" step="0.01" name="price" required min="0">
      
      <label>Stock Quantity *</label>
      <input type="number" name="stock" required min="0">
      
      <label>Description</label>
      <textarea name="description" rows="3" placeholder="Optional product description"></textarea>
      
      <label>Product Image</label>
      <input type="file" name="image" accept="image/*">
      
      <div class="modal-buttons">
        <button type="submit" class="btn-add">Add Product</button>
        <button type="button" class="btn-cancel" onclick="document.getElementById('add-product-modal').style.display='none'">Cancel</button>
      </div>
    </form>
  </div>
</div>

<div class="table-container">
  <table class="product-table">
    <thead>
      <tr>
        <th>ID</th><th>Name</th><th>Category</th><th>Farmer</th><th>Price</th><th>Stock</th><th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($products)): foreach($products as $p): ?>
      <tr>
        <td><?php echo htmlspecialchars($p['product_id']); ?></td>
        <td><?php echo htmlspecialchars($p['name']); ?></td>
        <td><?php echo ucfirst($p['category']); ?></td>
        <td><?php echo htmlspecialchars($p['farmer_name']); ?></td>
        <td>৳<?php echo number_format($p['price'], 2); ?></td>
        <td><?php echo $p['stock']; ?></td>
        <td>
          <form method="POST" action="admindashboard.php?section=products" style="display:inline-block;">
            <input type="hidden" name="action" value="delete_product">
            <input type="hidden" name="product_id" value="<?php echo $p['product_id']; ?>">
            <button type="submit" class="btn-delete" onclick="return confirm('Are you sure you want to delete this product?')">Remove</button>
          </form>

          <button class="btn-edit" onclick="document.getElementById('stock-<?php echo $p['product_id']; ?>').style.display='flex'">Update Stock</button>

          <div id="stock-<?php echo $p['product_id']; ?>" class="modal" style="display:none;">
            <div class="modal-content">
              <form method="POST" action="admindashboard.php?section=products">
                <input type="hidden" name="action" value="update_stock">
                <input type="hidden" name="product_id" value="<?php echo $p['product_id']; ?>">
                <label>Stock Quantity</label>
                <input type="number" name="stock" value="<?php echo $p['stock']; ?>" min="0" required>
                <div class="modal-buttons">
                  <button type="submit" class="btn-add">Save</button>
                  <button type="button" class="btn-cancel" onclick="document.getElementById('stock-<?php echo $p['product_id']; ?>').style.display='none'">Cancel</button>
                </div>
              </form>
            </div>
          </div>

        </td>
      </tr>
      <?php endforeach; else: ?>
      <tr><td colspan="7" class="no-data">No products found</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>


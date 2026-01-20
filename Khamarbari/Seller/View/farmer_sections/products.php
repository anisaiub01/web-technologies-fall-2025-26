<?php
// expects: $products
?>

<h2>My Products</h2>

<?php if (isset($_GET['success'])): ?>
    <p style="color:green;">✅ Action completed successfully</p>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <p style="color:red;">❌ Something went wrong</p>
<?php endif; ?>


<form method="post" enctype="multipart/form-data"
      style="margin:20px 0; display:grid; gap:10px; max-width:500px;">
    
    <input type="hidden" name="action" value="add_product">

    <input type="text" name="name" placeholder="Product Name" required>
    <input type="text" name="category" placeholder="Category (e.g. grocery)" required>
    <input type="number" step="0.01" name="price" placeholder="Price" required>
    <input type="number" name="stock" placeholder="Stock Quantity" required>

    <textarea name="description" placeholder="Description (optional)"></textarea>

    <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif,.webp">

    <button type="submit">➕ Add Product</button>
</form>

<hr>

<div class="table-wrap">
<table class="inv-table" border="1" cellpadding="10" cellspacing="0"
       style="width:100%; border-collapse:collapse;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Update</th>
            <th>Delete</th>
        </tr>
    </thead>

    <tbody>
    <?php if (empty($products)): ?>
        <tr>
            <td colspan="8">No products found</td>
        </tr>
    <?php else: ?>
        <?php foreach ($products as $p): ?>
        <tr>
            <td><?php echo htmlspecialchars($p['product_id']); ?></td>

            <td style="width:80px;">
                <?php if (!empty($p['image'])): ?>
                    <img src="<?php echo htmlspecialchars($p['image']); ?>"
                         style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
                <?php else: ?>
                    —
                <?php endif; ?>
            </td>

            <td><?php echo htmlspecialchars($p['name']); ?></td>
            <td><?php echo htmlspecialchars($p['category']); ?></td>

            <!-- Update -->
            <td>
                <form method="post" style="display:flex; gap:8px; align-items:center;">
                    <input type="hidden" name="action" value="update_product">
                    <input type="hidden" name="product_id"
                           value="<?php echo htmlspecialchars($p['product_id']); ?>">

                    <input type="number" step="0.01" name="price"
                           value="<?php echo htmlspecialchars($p['price']); ?>"
                           required style="width:90px;">
            </td>

            <td>
                    <input type="number" name="stock"
                           value="<?php echo htmlspecialchars($p['stock']); ?>"
                           required style="width:90px;">
            </td>

            <td>
                    <button type="submit">💾 Save</button>
                </form>
            </td>

            <!-- Delete -->
            <td>
                <form method="post"
                      onsubmit="return confirm('Delete this product?');">
                    <input type="hidden" name="action" value="delete_product">
                    <input type="hidden" name="product_id"
                           value="<?php echo htmlspecialchars($p['product_id']); ?>">
                    <button type="submit">🗑 Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
</div>

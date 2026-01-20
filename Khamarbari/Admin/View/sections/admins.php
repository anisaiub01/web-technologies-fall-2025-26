<h2 class="Apage-title">Admins</h2>

<!-- Messages -->
<?php if(isset($_GET['error'])): ?>
    <?php if($_GET['error'] === 'self_delete'): ?>
        <p class="Amsg Aerror">You cannot delete your own admin account!</p>
    <?php elseif($_GET['error'] === 'last_admin'): ?>
        <p class="Amsg Aerror">Cannot delete the last admin!</p>
    <?php elseif($_GET['error'] === 'delete_fail'): ?>
        <p class="Amsg Aerror">Failed to delete admin.</p>
    <?php elseif($_GET['error'] === 'email_exists'): ?>
        <p class="Amsg Aerror">Email already exists!</p>
    <?php elseif($_GET['error'] === 'email_exists_update'): ?>
        <p class="Amsg Aerror">Email already exists for another admin!</p>
    <?php elseif($_GET['error'] === 'update_fail'): ?>
        <p class="Amsg Aerror">Failed to update admin.</p>
    <?php elseif($_GET['error'] === 'unauthorized'): ?>
        <p class="Amsg Aerror">Unauthorized action!</p>
    <?php endif; ?>
<?php endif; ?>

<?php if(isset($_GET['success'])): ?>
    <?php if($_GET['success'] === 'delete'): ?>
        <p class="Amsg Asuccess">Admin deleted successfully.</p>
    <?php elseif($_GET['success'] === 'update'): ?>
        <p class="Amsg Asuccess">Admin updated successfully.</p>
    <?php elseif($_GET['success'] === '1'): ?>
        <p class="Amsg Asuccess">Admin added successfully.</p>
    <?php endif; ?>
<?php endif; ?>

<!-- Add Admin Form -->
<form method="POST" action="admindashboard.php" class="Aadmin-form">
    <input type="hidden" name="action" value="add_admin">
    <input type="text" name="name" placeholder="Admin Name" required class="Aform-input">
    <input type="email" name="email" placeholder="Admin Email" required class="Aform-input">
    <input type="text" name="password" placeholder="Password" required class="Aform-input">
    <button type="submit" class="Abtn Abtn-primary">Add Admin</button>
</form>

<!-- Admin List -->
<table class="Aadmin-table">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>
    <?php foreach($admins as $admin): ?>
    <tr>
        <td><?= htmlspecialchars($admin['admin_id']) ?></td>
        <td><?= htmlspecialchars($admin['name']) ?></td>
        <td><?= htmlspecialchars($admin['email']) ?></td>
        <td>
            <!-- Edit Button -->
            <button onclick="showEditForm('<?= htmlspecialchars($admin['admin_id']) ?>', '<?= htmlspecialchars($admin['name']) ?>', '<?= htmlspecialchars($admin['email']) ?>')" class="Abtn Abtn-edit">Edit</button>
            
            <!-- Delete Button -->
            <form method="POST"
                  action="admindashboard.php"
                  class="Adelete-form"
                  style="display:inline;"
                  onsubmit="return confirm('Are you sure you want to delete this admin?');">
                <input type="hidden" name="action" value="delete_admin">
                <input type="hidden" name="admin_id" value="<?= htmlspecialchars($admin['admin_id']) ?>">
                <button type="submit" class="Abtn Abtn-danger">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<!-- Edit Modal-->
<div id="editModal" class="Amodal" style="display:none;">
    <div class="Amodal-content">
        <span class="Aclose" onclick="closeEditForm()">&times;</span>
        <h3>Edit Admin</h3>
        <form method="POST" action="admindashboard.php" class="Aadmin-form">
            <input type="hidden" name="action" value="update_admin">
            <input type="hidden" name="admin_id" id="edit_admin_id">
            
            <input type="text" name="name" id="edit_name" placeholder="Admin Name" required class="Aform-input">
            <input type="email" name="email" id="edit_email" placeholder="Admin Email" required class="Aform-input">
            <input type="text" name="password" id="edit_password" placeholder="New Password (leave blank to keep current)" class="Aform-input">
            
            <button type="submit" class="Abtn Abtn-primary">Update Admin</button>
            <button type="button" onclick="closeEditForm()" class="Abtn Abtn-secondary">Cancel</button>
        </form>
    </div>
</div>

<script>
function showEditForm(adminId, name, email) {
    document.getElementById('edit_admin_id').value = adminId;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_password').value = '';
    document.getElementById('editModal').style.display = 'block';
}

function closeEditForm() {
    document.getElementById('editModal').style.display = 'none';
}

// Close modal 
window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>


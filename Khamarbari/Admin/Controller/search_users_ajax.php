<?php
session_start();
require_once __DIR__ . "/../Model/db.php";
require_once __DIR__ . "/../Model/UserModel.php";

// Check authentication
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Admin') {
    http_response_code(403);
    echo '<tr><td colspan="7" class="no-data">Unauthorized access</td></tr>';
    exit;
}

// Handle AJAX POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userType = trim($_POST['user_type'] ?? 'All');
    $keyword = trim($_POST['keyword'] ?? '');
    
    $userModel = new UserModel($conn);
    $users = $userModel->searchUsers($userType, $keyword);
    
    // Return table rows HTML
    if (!empty($users)) {
        foreach($users as $u) {
            ?>
            <tr>
                <td><?php echo htmlspecialchars($u['user_id']); ?></td>
                <td><?php echo htmlspecialchars($u['name']); ?></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
                <td><?php echo htmlspecialchars($u['user_type']); ?></td>  
                <td><?php echo htmlspecialchars($u['phone']); ?></td>
                <td><?php echo htmlspecialchars($u['address']); ?></td>
                <td>
                    <button class="btn-edit" onclick="document.getElementById('edit-<?php echo $u['user_id']; ?>').style.display='block'">Update</button>
                    <form method="POST" action="admindashboard.php?section=users" style="display:inline-block">
                        <input type="hidden" name="action" value="delete_user">
                        <input type="hidden" name="user_id" value="<?php echo $u['user_id']; ?>">
                        <button type="submit" class="btn-delete">Delete</button>
                    </form>

                    <!-- Edit modal -->
                    <div id="edit-<?php echo $u['user_id']; ?>" class="modal" style="display:none;">
                        <form method="POST" action="admindashboard.php?section=users" class="modal-form">
                            <input type="hidden" name="action" value="update_user">
                            <input type="hidden" name="user_id" value="<?php echo $u['user_id']; ?>">
                            <label>Name</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($u['name']); ?>">
                            <label>Phone</label>
                            <input type="text" name="phone" value="<?php echo htmlspecialchars($u['phone']); ?>">
                            <label>Address</label>
                            <textarea name="address"><?php echo htmlspecialchars($u['address']); ?></textarea>
                            <label>NID</label>
                            <input type="text" name="nid" value="<?php echo htmlspecialchars($u['nid'] ?? ''); ?>">
                            <button type="submit">Save</button>
                            <button type="button" onclick="document.getElementById('edit-<?php echo $u['user_id']; ?>').style.display='none'">Cancel</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php
        }
    } else {
        echo '<tr><td colspan="7" class="no-data">No users found</td></tr>';
    }
    exit;
}

http_response_code(405);
echo '<tr><td colspan="7" class="no-data">Method not allowed</td></tr>';
?>
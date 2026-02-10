<?php
session_start();
require_once "db_aadminconnect.php";

if (!isset($_SESSION['is_admin'])) {
    header("Location: AdminLogin.php");
    exit;
}

// Get admin_id from query string
$admin_id = intval($_GET['id'] ?? 0);

// Fetch admin data
$stmt = $conn->prepare("SELECT * FROM admin_users WHERE admin_id=? LIMIT 1");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();

if (!$admin) {
    die("Admin not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? $admin['role'];
    $password = $_POST['password'] ?? '';
    $permissions = $_POST['permissions'] ?? [];
    $permStr = implode(",", $permissions);

    if ($password !== '') {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE admin_users SET role=?, password=?, permissions=? WHERE admin_id=?");
        $stmt->bind_param("sssi", $role, $hashed, $permStr, $admin_id);
    } else {
        $stmt = $conn->prepare("UPDATE admin_users SET role=?, permissions=? WHERE admin_id=?");
        $stmt->bind_param("ssi", $role, $permStr, $admin_id);
    }

    if ($stmt->execute()) {
        header("Location: editRolePermission.php?id=" . $admin_id);
        exit;
    } else {
        $error = "Database update failed.";
    }
    $stmt->close();
}

// Current permissions array
$currentPerms = explode(",", $admin['permissions']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Role</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="editrolepermisiion.css">
</head>
<body>
  <div class="form-container">
    <h1>Edit Role for <?php echo htmlspecialchars($admin['name']); ?></h1>

    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="POST">
      <!-- Current Username -->
      <div class="form-group">
        <label>Current Username</label>
        <input type="text" value="@<?php echo htmlspecialchars($admin['username']); ?>" disabled>
      </div>

      <!-- Role -->
      <div class="form-group">
        <label>Role</label>
        <select name="role">
          <option value="super" <?php echo ($admin['role']==='super')?'selected':''; ?>>Super Admin</option>
          <option value="admin" <?php echo ($admin['role']==='admin')?'selected':''; ?>>Admin</option>
          <option value="moderator" <?php echo ($admin['role']==='moderator')?'selected':''; ?>>Moderator</option>
        </select>
      </div>

      <!-- Reset Password -->
      <div class="form-group">
        <label>Reset Password (Optional)</label>
        <p class="hint">Leave blank to keep current password. Enter a new password only if you want to reset it.</p>
        <input type="password" name="password" placeholder="Enter new password">
      </div>

      <!-- Permissions -->
      <div class="form-group">
        <label>Permissions</label>
        <div class="permissions">
          <label><input type="checkbox" name="permissions[]" value="all" <?php echo in_array('all',$currentPerms)?'checked':''; ?>> All Access</label>
          <label><input type="checkbox" name="permissions[]" value="users" <?php echo in_array('users',$currentPerms)?'checked':''; ?>> User Management</label>
          <label><input type="checkbox" name="permissions[]" value="settings" <?php echo in_array('settings',$currentPerms)?'checked':''; ?>> System Settings</label>
          <label><input type="checkbox" name="permissions[]" value="approval" <?php echo in_array('approval',$currentPerms)?'checked':''; ?>> Content Approval</label>
          <label><input type="checkbox" name="permissions[]" value="reports" <?php echo in_array('reports',$currentPerms)?'checked':''; ?>> Report Access</label>
        </div>
      </div>

      <!-- Actions -->
      <div class="form-actions">
        <a href="viewall.php?id=<?php echo $admin_id; ?>" class="btn cancel"><i class="fa-solid fa-xmark"></i> Cancel</a>
        <button type="submit" class="btn update"><i class="fa-solid fa-check"></i> Update Role</button>
      </div>
    </form>
  </div>
</body>
</html>
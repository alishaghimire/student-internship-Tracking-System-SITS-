<?php
session_start();
require_once "db_aadminconnect.php"; // admin DB connection

if (!isset($_SESSION['is_admin'])) {
    header("Location: adminLogin.php");
    exit;
}

$err = [];
function esc($v) { return htmlspecialchars($v ?? '', ENT_QUOTES); }

$name = $email = $username = $password = $role = $department = "";
$permissions = [];

/* Patterns */
$patternName = "/^[A-Z][A-Za-z\s.]*$/"; // must start with capital letter
$patternUsername = "/^[a-z0-9_]{5,20}$/"; // lowercase letters, numbers, underscore
$patternPassword = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/"; // min 8 chars, upper, lower, digit

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if ($name === '' || !preg_match($patternName, $name)) {
        $err['name'] = "<div class='error'>Name must start with a capital letter.</div>";
    }

    $email = trim($_POST['email'] ?? '');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err['email'] = "<div class='error'>Enter a valid email.</div>";
    }

    $username = trim($_POST['username'] ?? '');
    if (!preg_match($patternUsername, $username)) {
        $err['username'] = "<div class='error'>5–20 chars, lowercase letters, numbers, underscore.</div>";
    }

    $password = $_POST['password'] ?? '';
    if (!preg_match($patternPassword, $password)) {
        $err['password'] = "<div class='error'>Min 8 chars, include upper, lower, digit.</div>";
    }

    $role = $_POST['role'] ?? '';
    if (!in_array($role, ['super','admin','moderator'])) {
        $err['role'] = "<div class='error'>Select a valid role.</div>";
    }

    $department = $_POST['department'] ?? '';
    if ($department === '') {
        $err['department'] = "<div class='error'>Select a department.</div>";
    }

    $permissions = $_POST['permissions'] ?? [];
    if (count($permissions) === 0) {
        $err['permissions'] = "<div class='error'>Select at least one permission.</div>";
    }

    if (empty($err)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $permStr = implode(",", $permissions);

        $stmt = $conn->prepare("
            INSERT INTO admin_users (name, email, username, password, role, department, permissions)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sssssss", $name, $email, $username, $hashed, $role, $department, $permStr);
        if ($stmt->execute()) {
            header("Location: manageAdmin.php?success=1");
            exit;
        } else {
            $err['general'] = "<div class='error'>Database error. Try again.</div>";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Admin</title>
<style>
body { font-family: Segoe UI, sans-serif; background:#f5f7fb; padding:40px 0; }
.form-container { max-width:820px; margin:auto; background:#fff; padding:35px 40px; border-radius:14px; box-shadow:0 10px 30px rgba(0,0,0,.08); }
label { font-weight:600; margin-bottom:6px; display:block; }
input, select { width:100%; padding:11px; border-radius:8px; border:1px solid #cfd6e4; }
.form-row { display:grid; grid-template-columns:1fr 1fr; gap:18px; }
.form-group { margin-bottom:18px; }
.error { color:#dc2626; font-size:13px; margin-top:4px; }
.permissions { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
.form-actions { display:flex; justify-content:flex-end; gap:12px; }
.btn { padding:10px 18px; border-radius:8px; border:none; cursor:pointer; }
.submit { background:linear-gradient(135deg,#9333ea,#ec4899); color:#fff; }
.cancel { background:#e5e7eb; }
</style>
</head>
<body>
<div class="form-container">
<h1>Add Admin</h1>
<p class="subtitle">Create a new admin account with role and permissions.</p>

<?php echo $err['general'] ?? ''; ?>

<form method="POST">

<div class="form-group">
  <label>Name *</label>
  <input type="text" name="name" value="<?php echo esc($name); ?>">
  <?php echo $err['name'] ?? ''; ?>
</div>

<div class="form-group">
  <label>Email *</label>
  <input type="email" name="email" value="<?php echo esc($email); ?>">
  <?php echo $err['email'] ?? ''; ?>
</div>

<div class="form-group">
  <label>Username *</label>
  <input type="text" name="username" value="<?php echo esc($username); ?>">
  <?php echo $err['username'] ?? ''; ?>
</div>

<div class="form-group">
  <label>Password *</label>
  <input type="password" name="password">
  <?php echo $err['password'] ?? ''; ?>
</div>

<div class="form-row">
  <div class="form-group">
    <label>Role *</label>
    <select name="role">
      <option value="">Select Role</option>
      <option value="super" <?php echo ($role==='super')?'selected':''; ?>>Super Admin</option>
      <option value="admin" <?php echo ($role==='admin')?'selected':''; ?>>Admin</option>
      <option value="moderator" <?php echo ($role==='moderator')?'selected':''; ?>>Moderator</option>
    </select>
    <?php echo $err['role'] ?? ''; ?>
  </div>

  <div class="form-group">
    <label>Department *</label>
    <select name="department">
      <option value="">Select Department</option>
      <option value="Engineering" <?php echo ($department==='Engineering')?'selected':''; ?>>Engineering</option>
      <option value="Business" <?php echo ($department==='Business')?'selected':''; ?>>Business</option>
      <option value="Design" <?php echo ($department==='Design')?'selected':''; ?>>Design</option>
      <option value="Marketing" <?php echo ($department==='Marketing')?'selected':''; ?>>Marketing</option>
      <option value="Data Science" <?php echo ($department==='Data Science')?'selected':''; ?>>Data Science</option>
    </select>
    <?php echo $err['department'] ?? ''; ?>
  </div>
</div>

<div class="form-group">
  <label>Permissions *</label>
  <div class="permissions">
    <label><input type="checkbox" name="permissions[]" value="all" <?php echo in_array('all',$permissions)?'checked':''; ?>> All Access</label>
    <label><input type="checkbox" name="permissions[]" value="settings" <?php echo in_array('settings',$permissions)?'checked':''; ?>> System Settings</label>
    <label><input type="checkbox" name="permissions[]" value="reports" <?php echo in_array('reports',$permissions)?'checked':''; ?>> Report Access</label>
    <label><input type="checkbox" name="permissions[]" value="users" <?php echo in_array('users',$permissions)?'checked':''; ?>> User Management</label>
    <label><input type="checkbox" name="permissions[]" value="approval" <?php echo in_array('approval',$permissions)?'checked':''; ?>> Content Approval</label>
  </div>
  <?php echo $err['permissions'] ?? ''; ?>
</div>

<div class="form-actions">
  <button type="button" class="btn cancel" 
        onclick="window.location.href='/project/html/Admin/AdminDashboard.php#manageAdmin';">
  Cancel
</button>
  <button type="submit" class="btn submit">Add Admin</button>
</div>

</form>
</div>
</body>
</html>
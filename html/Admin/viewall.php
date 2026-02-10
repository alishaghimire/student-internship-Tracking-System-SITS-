<?php
session_start();
require_once "db_aadminconnect.php"; // adjust to your DB connection file

if (!isset($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit;
}

// Get admin_id from query string
$admin_id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM admin_users WHERE admin_id=? LIMIT 1");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();

if (!$admin) {
    die("Admin not found.");
}

// Avatar initials
$parts = explode(" ", $admin['name']);
$initials = "";
foreach ($parts as $p) { $initials .= strtoupper($p[0]); }

// Role label
$roleLabel = ($admin['role'] === 'super') ? 'Super Admin' : (($admin['role'] === 'admin') ? 'Admin' : 'Moderator');

// Status
$statusText = ucfirst($admin['status']);

// Permissions (comma-separated string → array)
$perms = explode(",", $admin['permissions']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Profile</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="viewall.css">
</head>
<body>
  <div class="profile-container">
    <!-- Header -->
    <div class="profile-header">
      <div class="avatar"><?php echo $initials; ?></div>
      <div class="info">
        <h2><?php echo htmlspecialchars($admin['name']); ?></h2>
        <p>@<?php echo htmlspecialchars($admin['username']); ?> • 
           <span class="status <?php echo $admin['status']; ?>">
             <?php echo $statusText; ?>
           </span>
        </p>
      </div>
    </div>

    <!-- Basic Information -->
    <section class="profile-section">
      <h3><i class="fa-solid fa-id-card"></i> Basic Information</h3>
      <ul>
        <li><strong>Email Address:</strong> <?php echo htmlspecialchars($admin['email']); ?></li>
        <li><strong>Username:</strong> @<?php echo htmlspecialchars($admin['username']); ?></li>
        <li><strong>Role:</strong> <?php echo $roleLabel; ?></li>
        <li><strong>Department:</strong> <?php echo htmlspecialchars($admin['department']); ?></li>
      </ul>
    </section>

    <!-- Activity Information -->
    <section class="profile-section">
      <h3><i class="fa-solid fa-clock"></i> Activity Information</h3>
      <ul>
        <li><strong>Joined Date:</strong> <?php echo date("M Y", strtotime($admin['created_at'])); ?></li>
        <li><strong>Last Active:</strong> 
          <?php echo $admin['last_active'] ? htmlspecialchars($admin['last_active']) : 'Never'; ?>
        </li>
      </ul>
    </section>

    <!-- Permissions -->
    <section class="profile-section">
      <h3><i class="fa-solid fa-key"></i> Permissions & Access</h3>
      <div class="permissions">
        <?php foreach ($perms as $perm): ?>
          <?php $perm = trim($perm); if ($perm === '') continue; ?>
          <span class="badge"><?php echo ucfirst($perm); ?></span>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- Actions -->
    <div class="profile-actions">
      <a href="editRolePermission.php?id=<?php echo $admin['admin_id']; ?>" class="btn edit">
        <i class="fa-solid fa-pen"></i> Edit Role & Permissions
      </a>
      <?php if ($admin['status'] === 'active'): ?>
        <a href="deactivatedadmin.php?id=<?php echo $admin['admin_id']; ?>" class="btn deactivate">
          <i class="fa-solid fa-ban"></i> Deactivate
        </a>
      <?php else: ?>
        <a href="reactivate.php?id=<?php echo $admin['admin_id']; ?>" class="btn reactivate">
          <i class="fa-solid fa-check"></i> Reactivate
        </a>
      <?php endif; ?>
   <a href="AdminDashboard.php#manageAdmin" class="btn back">
  <i class="fa-solid fa-arrow-left"></i> Back to Manage Admin
</a>
    </div>
  </div>
</body>
</html>
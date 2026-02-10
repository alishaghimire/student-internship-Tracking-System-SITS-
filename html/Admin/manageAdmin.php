<?php

require_once "db_aadminconnect.php";

if (!isset($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit;
}

// Only super admin can access Manage Admin
if ($_SESSION['role'] !== 'super') {
    die("Access denied. Only Super Admin can manage admins.");
}

$sql = "SELECT admin_id, name, email, username, role, department, last_active, status 
        FROM admin_users ORDER BY name ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Admin</title>
  <link rel="stylesheet" href="manageAdmin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
  <div class="manage-admin-container">
    <header class="header">
      <h1><i class="fa-solid fa-user-gear"></i> Manage Admin</h1>
      <a href="addAdmin.php" class="btn-add">
        <i class="fa-solid fa-plus"></i> Add Admin
      </a>
    </header>

    <table class="admin-table">
      <thead>
        <tr>
          <th>Admin</th>
          <th>Email</th>
          <th>Username</th>
          <th>Role</th>
          <th>Department</th>
          <th>Last Active</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <?php
              // Avatar initials
              $parts = explode(" ", $row['name']);
              $initials = "";
              foreach ($parts as $p) { $initials .= strtoupper($p[0]); }

              // Role badge class
              $roleClass = ($row['role'] === 'super') ? 'super' : (($row['role'] === 'admin') ? 'admin' : 'moderator');
            ?>
            <tr>
              <td><div class="avatar"><?php echo $initials; ?></div> <?php echo htmlspecialchars($row['name']); ?></td>
              <td><?php echo htmlspecialchars($row['email']); ?></td>
              <td>@<?php echo htmlspecialchars($row['username']); ?></td>
              <td><span class="badge <?php echo $roleClass; ?>">
                <?php echo ucfirst($row['role']); ?>
              </span></td>
              <td><?php echo htmlspecialchars($row['department']); ?></td>
              <td><?php echo $row['last_active'] ? htmlspecialchars($row['last_active']) : 'Never'; ?></td>
              <td><span class="status <?php echo $row['status']; ?>">
                <?php echo ucfirst($row['status']); ?>
              </span></td>
              <td>
                <a href="viewall.php?id=<?php echo $row['admin_id']; ?>" class="btn-view">View All</a>
                <?php if ($row['status'] === 'active'): ?>
                  <a href="deactivatedadmin.php?id=<?php echo $row['admin_id']; ?>" class="btn deactivate">
                    <i class="fa-solid fa-ban"></i> Deactivate
                  </a>
                <?php else: ?>
                  <a href="reactivate.php?id=<?php echo $row['admin_id']; ?>" class="btn reactivate">
                    <i class="fa-solid fa-check"></i> Reactivate
                  </a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="8">No admins found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
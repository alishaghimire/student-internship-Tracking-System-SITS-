<?php
session_start();
require_once 'db_aadminconnect.php'; // your DB connection file

$err = [];
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Hard-coded super admin credentials
$superAdminEmail = "alishayojana@gmail.com";
$superAdminPassword = "AliYoju123"; // you can hash this if you prefer

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($email)) {
        $err['email'] = "<p style='color:red;'>Email is required</p>";
    }
    if (empty($password)) {
        $err['password'] = "<p style='color:red;'>Password is required</p>";
    }

    if (!$err) {
        // First check if super admin login
        if ($email === $superAdminEmail && $password === $superAdminPassword) {
            $_SESSION['is_admin'] = true;
            $_SESSION['admin_email'] = $superAdminEmail;
            $_SESSION['role'] = 'super'; // mark role
            header("Location: AdminDashboard.php");
            exit;
        }

        // Otherwise check against database for normal admins
        $stmt = $conn->prepare("SELECT * FROM admin_users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            // Check if account is active
            if ($row['status'] !== 'active') {
                $err['email'] = "<p style='color:red;'>Your account is inactive. Please contact the administrator.</p>";
            } else {
                // Verify password stored in DB
                if (password_verify($password, $row['password'])) {
                    $_SESSION['is_admin'] = true;
                    $_SESSION['admin_email'] = $row['email'];
                    $_SESSION['admin_id'] = $row['admin_id'];
                    $_SESSION['role'] = $row['role']; // use actual role from DB

                    // Update last_active timestamp
                    $stmtUpdate = $conn->prepare("UPDATE admin_users SET last_active=NOW() WHERE admin_id=?");
                    $stmtUpdate->bind_param("i", $row['admin_id']);
                    $stmtUpdate->execute();
                    $stmtUpdate->close();

                    header("Location: AdminDashboard.php");
                    exit;
                } else {
                    $err['password'] = "<p style='color:red;'>Invalid password</p>";
                }
            }
        } else {
            $err['email'] = "<p style='color:red;'>Invalid email</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Internship Login</title>
  <link rel="stylesheet" href="/SITS/Css/Admin/adminloginpage.css" />
</head>
<body>
  <div class="login-container">
    <a href="/project/html/fristpage.php" class="back-link">← Back to home</a>
    <div class="login-box">
      <h1>Internship Tracking System</h1>
      <p class="subtitle">Sign in to access your dashboard</p>

      <form action="" method="post">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter your email"
               value="<?php echo htmlspecialchars($email); ?>" />
        <?php echo $err['email'] ?? ''; ?>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password"
               value="<?php echo htmlspecialchars($password); ?>" />
        <?php echo $err['password'] ?? ''; ?>

        <button type="submit">Sign in</button>
      </form>

      
    </div>
  </div>
</body>
</html>
<?php
session_start();
require_once "dbconnectcompany.php";

$err = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($email)) {
        $err['email'] = "Enter your email.<br>";
    }

    if (empty($password)) {
        $err['password'] = "Enter your password.<br>";
    }

    if (empty($err)) {

        // Correct table + correct columns
        $stmt = $conn->prepare("
            SELECT company_id AS company_id, company_name, email, password, status 
FROM company_registration
WHERE email = ?
        ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Email not found
        if ($result->num_rows === 0) {
            $err['email'] = "Email not found. Please register.<br>";
        } else {

            $row = $result->fetch_assoc();

            // Verify hashed password
            if (!password_verify($password, $row['password'])) {
                $err['password'] = "Incorrect password.<br>";
            } else {

                // STATUS CHECK
                if (strtolower($row['status']) !== 'approved') {
                    $_SESSION['company_id'] = $row['company_id'];
                    header("Location: statuscompany.php");
                    exit();
                }

                // APPROVED → Login anytime
                $_SESSION['company_id'] = $row['company_id'];
                $_SESSION['company_name'] = $row['company_name'];
                $_SESSION['company_email'] = $row['email'];

                header("Location: CDashboard.php");
                exit();
            }
        }

        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Internship Login</title>
  <link rel="stylesheet" href="/SITS/Css/company/companyloginpage.css" />
</head>
<body>
  <div class="login-container">
    <a href="/project/html/fristpage.php" class="back-link">← Back to home</a>
    <div class="login-box">
      <h1>Internship Tracking System</h1>
      <p class="subtitle">Sign in to access your dashboard</p>

      <form action="" method="post">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php  echo isset($email)?$email:'';?>" />
        <?php echo isset($err['email']) ? $err['email'] : ''; ?>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" value="<?php echo isset($password)?$password:'';?>" />
        <?php echo isset($err['password']) ? $err['password'] : ''; ?>

        <button type="submit">Sign in</button>

      
      </form>
      <p class="register-text">
        forget Password? <a href="forgetPassword.php">Reset</a>
      </p>

      <p class="register-text">
        Don't have an account? <a href="companyregistration.php">Register</a>
      </p>
    </div>
  </div>
</body>
</html>
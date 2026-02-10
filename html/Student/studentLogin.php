<?php
session_start();
require_once "studentdbconnection.php";

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

        // Fetch student by email
        $stmt = $conn->prepare("
            SELECT student_id, full_name, email, password, status 
            FROM students_registrtaion 
            WHERE email = ?
        ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $err['email'] = "Email not found. Please register.<br>";
        } else {

            $row = $result->fetch_assoc();

            // Verify hashed password
            if (!password_verify($password, $row['password'])) {
                $err['password'] = "Incorrect password.<br>";
            } 
            else if (strtolower($row['status']) !== 'approved') {
                $err['email'] = "Your account is not approved yet.<br>";
            } 
            else {
                //  THIS IS THE CORRECT PLACE 
                $_SESSION['student_id'] = $row['student_id'];
                $_SESSION['student_name'] = $row['full_name'];
                $_SESSION['student_email'] = $row['email'];

                header("Location:/project/html/Student/studentdashboard.php");
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Login - Internship Tracking System</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="/project/css/Student/studentLogin.css" />
</head>
<body>
  <div class="login-wrapper">
    <a href="/project/html/fristpage.php" class="back-link">← Back to home</a>
    <div class="login-box">
      <div class="login-icon">
        <i class="fas fa-graduation-cap"></i>
      </div>

      <h1>Internship Tracking System</h1>
      <p class="subtitle">Sign in to access your dashboard</p>

      <form action="" method="POST">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo isset($email) ? $email : ''; ?>" />
        <?php echo isset($err['email']) ? $err['email'] : ''; ?>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" />
        <?php echo isset($err['password']) ? $err['password'] : ''; ?>

        <button type="submit" class="login-btn">Sign in</button>
      </form>



      <p class="register-text">
          <a href="forgotPassword.php">Forgot your password?</a>
        Don't have an account? <a href="studentRegistration.php">Register</a>
      </p>
    </div>
  </div>
</body>
</html>

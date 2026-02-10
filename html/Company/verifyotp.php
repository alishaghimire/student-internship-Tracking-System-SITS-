<?php
session_start();
require_once "dbconnectcompany.php";

if (!isset($_SESSION['reset_email'])) {
    header("Location: resetPassword.php");
    exit();
}

$email = $_SESSION['reset_email'];
$err = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredOtp = implode("", $_POST['otp']);

    if ($enteredOtp == $_SESSION['reset_otp'] && time() < $_SESSION['otp_expiry']) {
        // Use already hashed password from session
        $hashed = $_SESSION['reset_password'];

        $stmt = $conn->prepare("UPDATE company_registration SET password=? WHERE email=?");
        $stmt->bind_param("ss", $hashed, $email);
        if ($stmt->execute()) {
            unset($_SESSION['reset_email'], $_SESSION['reset_password'], $_SESSION['reset_otp'], $_SESSION['otp_expiry']);
            header("Location:companylogin.php?reset=success");
            exit();
        } else {
            $err = "Error updating password.";
        }
    } else {
        $err = "Invalid or expired OTP. Try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Verify OTP</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: "Poppins", sans-serif;
      background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
      display: flex; justify-content: center; align-items: center;
      min-height: 100vh; margin: 0;
    }
    .otp-box {
      background: #fff; padding: 30px; border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15); width: 400px;
      text-align: center;
    }
    .otp-box h1 { margin-bottom: 10px; }
    .otp-inputs { display: flex; justify-content: space-between; margin: 20px 0; }
    .otp-inputs input {
      width: 45px; height: 50px; text-align: center;
      font-size: 20px; border: 1px solid #ccc; border-radius: 8px;
    }
    .login-btn {
      width: 100%; padding: 12px; background: #000; color: #fff;
      border: none; border-radius: 8px; cursor: pointer; font-size: 16px;
    }
    .login-btn:hover { background: #333; }
    .resend { margin-top: 10px; color: #2575fc; cursor: pointer; text-decoration:none; }
    .error { color: red; margin-top: 10px; }
  </style>
</head>
<body>
  <div class="otp-box">
    <a href="forgetPassword.php" style="float:left; text-decoration:none; color:#2575fc;">← Back</a>
    <h1>Verify OTP</h1>
    <p>We've sent a 6-digit code to <?= htmlspecialchars($email) ?></p>
    <form method="POST">
      <div class="otp-inputs">
        <?php for ($i=0; $i<6; $i++): ?>
          <input type="text" name="otp[]" maxlength="1" required>
        <?php endfor; ?>
      </div>
      <button type="submit" class="login-btn">Verify & Reset Password</button>
      <a href="resendotp.php" class="resend">Didn't receive the code? Resend OTP</a>
      <?php if (!empty($err)) echo "<div class='error'>$err</div>"; ?>
    </form>
  </div>
</body>
</html>
<?php
session_start();
require_once "studentdbconnection.php";
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm']);

    if ($password !== $confirm) {
        echo "Passwords do not match.";
        exit;
    }

    // Generate OTP
    $otp = rand(100000, 999999);

    // Store in session (or DB)
    $_SESSION['reset_email'] = $email;
    $_SESSION['reset_password'] = password_hash($password, PASSWORD_DEFAULT);
    $_SESSION['reset_otp'] = $otp;
    $_SESSION['otp_expiry'] = time() + 300; // 5 minutes

    // Send OTP via Mailtrap
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.mailtrap.io';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'bc2f045b2cd966';
        $mail->Password   = '1d3b17fce08cfa';
        $mail->Port       = 2525;

        $mail->setFrom('no-reply@yourdomain.com', 'Your App');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset OTP';
        $mail->Body    = "Your OTP code is: <b>$otp</b>";

        $mail->send();
        header("Location: verifyotp.php");
        exit;
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: "Poppins", sans-serif;
      background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
    }
    .reset-box {
      background: #fff;
      padding: 40px 30px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      width: 380px;
    }
    .reset-box h1 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
      font-size: 22px;
    }
    .back-link {
      display: block;
      margin-bottom: 15px;
      color: #2575fc;
      text-decoration: none;
      font-weight: 500;
    }
    .form-group {
      margin-bottom: 20px;
      position: relative;
    }
    .form-group i {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #666;
    }
    .form-group input {
      width: 80%;
      padding: 12px 40px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
      outline: none;
    }
    .form-group input:focus {
      border-color: #2575fc;
      box-shadow: 0 0 5px rgba(37,117,252,0.4);
    }
    .login-btn {
      width: 100%;
      padding: 12px;
      background: #000;
      color: #fff;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
      font-weight: 600;
      margin-top: 10px;
    }
    .login-btn:hover {
      background: #333;
    }
    .error { color: red; font-size: 13px; margin-top: 5px; }
    .success { color: green; font-size: 13px; margin-top: 5px; }
  </style>
</head>
<body>
  <div class="reset-box">
    <a href="studentLogin.php" class="back-link">← Back to Sign In</a>
    <h1>Reset Password</h1>
    <form method="POST">
      <div class="form-group">
        <i class="fas fa-envelope"></i>
        <input type="email" name="email" placeholder="Enter your email" value="alighimire6@gmail.com">
      </div>

      <div class="form-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" placeholder="Enter new password">
      </div>

      <div class="form-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="confirm" placeholder="Confirm new password">
      </div>

      <button type="submit" class="login-btn">Send OTP</button>
    </form>
  </div>
</body>
</html>
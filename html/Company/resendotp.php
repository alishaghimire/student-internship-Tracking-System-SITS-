<?php
session_start();
require_once "dbconnectcompany.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/vendor/autoload.php';

// Make sure we have the email stored from resetPassword step
if (!isset($_SESSION['reset_email'])) {
    header("Location: forgotPassword.php");
    exit();
}

$email = $_SESSION['reset_email'];

// Generate new OTP
$otp = rand(100000, 999999);
$_SESSION['reset_otp'] = $otp;
$_SESSION['otp_expiry'] = time() + 300; // 5 minutes validity

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
    $mail->Subject = 'Password Reset OTP (Resent)';
    $mail->Body    = "Your new OTP code is: <b>$otp</b>";

    $mail->send();

    // Redirect back to verify page with a flag
    header("Location: verifyotp.php?resent=1");
    exit();
} catch (Exception $e) {
    echo "Mailer Error: {$mail->ErrorInfo}";
}
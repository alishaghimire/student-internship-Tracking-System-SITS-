<?php
session_start();
require_once "studentdbconnection.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // make sure PHPMailer is installed via Composer

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm']);

    if ($password === $confirm && !empty($email)) {
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_password'] = $password;

        // Generate OTP
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;

        // Send OTP via Mailtrap
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = 'YOUR_MAILTRAP_USERNAME'; // from Mailtrap
            $mail->Password = 'YOUR_MAILTRAP_PASSWORD'; // from Mailtrap
            $mail->Port = 2525;

            $mail->setFrom('no-reply@example.com', 'Internship Portal');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code';
            $mail->Body    = "Your OTP code is <b>$otp</b>";

            $mail->send();
        } catch (Exception $e) {
            die("Could not send OTP. Error: {$mail->ErrorInfo}");
        }

        header("Location: verifyOtp.php");
        exit();
    }
}
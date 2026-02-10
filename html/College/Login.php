<?php
session_start();
require_once "collegedb_connection.php";

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
        // Fetch college by email
        $stmt = $conn->prepare("SELECT * FROM colleges WHERE authorized_email = ?");
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
                if ($row['status'] !== 'Approved') {
                    // Not approved → cannot login
                    $_SESSION['college_id'] = $row['college_id'];
                    header("Location: statuscolumn.php");
                    exit();
                }

                // APPROVED → Login anytime
                $_SESSION['college_id'] = $row['college_id'];
                $_SESSION['college_name'] = $row['college_name'];
                $_SESSION['college_email'] = $row['authorized_email']; // fixed

                header("Location: collage.php"); // keep as-is, confirm if typo
                exit();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>College Login</title>
    <link rel="stylesheet" href="login.css" />
    <style>
        .back{
    margin-left: 0px;
    margin-top:700px;
     color:white;
}
.back button{
    height:30px;
    border:1px solid blue;
    border-radius:10px;
   
    background-color:blue;
}
.back a {
    color: White;
    text-decoration: none; 
  }


    </style>
</head>
<body>
   
<div class="container">
    <div class="logo">
        <img src="collage.png" alt="College Logo" width="150">
    </div>

    <h2>College Dashboard</h2>
    <p class="subtitle">Student Internship Tracking System</p>

    <form action="" method="POST">
        <label>Email</label>
        <div class="input-box">
            <input type="email" name="email" placeholder="Enter your email" required />
            <?php if (!empty($err['email'])) echo "<p class='error'>{$err['email']}</p>"; ?>
        </div>

        <label>Password</label>
        <div class="input-box">
            <input type="password" name="password" placeholder="Enter your password" required />
            <?php if (!empty($err['password'])) echo "<p class='error'>{$err['password']}</p>"; ?>
        </div>

        <button type="submit" class="btn">Sign In</button>

        <p class="register">
            Don't have an account?
            <a href="/project/html/College/collegeregistration.php">Register here</a>
            <br>
            <a href="forgetpassword.php">Reset password</a>
        </p>
    </form>
</div>
<div class="back">
<button><a href="/project/html/fristpage.php">back</a></button>
</div>

<script src="login.js"></script>
</body>
</html>
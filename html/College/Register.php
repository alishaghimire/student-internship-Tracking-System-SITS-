<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - College Dashboard</title>
    <link rel="stylesheet" href="register.css">
</head>
<body class="auth-body">

    <div class="auth-container">
        <h2>Create Account</h2>
        <p class="auth-sub">Register to access the portal</p>

        <div class="auth-form">
            <input type="text" id="regName" placeholder="Full Name">
            <input type="email" id="regEmail" placeholder="Email">
            <input type="password" id="regPass" placeholder="Password">

            <button onclick="registerUser()">Register</button>

            <p class="auth-bottom-text">
                Already have an account?
                <a href="login.php">Login</a>
            </p>
        </div>
    </div>

    <script src="register.js"></script>
</body>
</html>

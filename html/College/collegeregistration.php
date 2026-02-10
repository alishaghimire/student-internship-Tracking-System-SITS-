<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once "collegedb_connection.php"; // Ensure this connects to your database
    $err = [];

    // Initialize variables
    $password_input = "";
    $confirm_password_input = "";

    // College Name
    if (!empty(trim($_POST['college_name']))) {
        $college_name = trim($_POST['college_name']);
        if (!preg_match("/^[A-Z][a-zA-Z0-9\s&.\-']*$/", $college_name)) {
            $err['college_name'] = "College name must start with a capital letter.<br>";
        }
    } else {
        $err['college_name'] = "Enter college name.<br>";
    }

    // College Type
    if (!empty($_POST['college_type'])) {
        $college_type = $_POST['college_type'];
    } else {
        $err['college_type'] = "Select college type.<br>";
    }

    // Affiliated University
    if (!empty(trim($_POST['affiliated_university']))) {
        $affiliated_university = trim($_POST['affiliated_university']);
        if (!preg_match("/^[A-Z][a-zA-Z0-9\s&.\-']*$/", $affiliated_university)) {
            $err['affiliated_university'] = "Must start with capital letter.<br>";
        }
    } else {
        $err['affiliated_university'] = "Enter affiliated university.<br>";
    }

    // Registration Number
    if (!empty(trim($_POST['registration_number']))) {
        $registration_number = trim($_POST['registration_number']);
        if (!preg_match("/^[A-Z0-9\-]{5,}$/", $registration_number)) {
            $err['registration_number'] = "Invalid registration number.<br>";
        }
    } else {
        $err['registration_number'] = "Enter registration number.<br>";
    }

    // Accreditation Level (optional)
    $accreditation_level = !empty(trim($_POST['accreditation_level'])) ? trim($_POST['accreditation_level']) : "";

    // Established Year
    if (!empty($_POST['established_year'])) {
        $established_year = (int)$_POST['established_year'];
        if ($established_year < 1900 || $established_year > date("Y")) {
            $err['established_year'] = "Invalid year.<br>";
        }
    } else {
        $err['established_year'] = "Enter established year.<br>";
    }

    // Address fields
    foreach (['province','district','city','full_address'] as $field) {
        if (!empty(trim($_POST[$field]))) {
            $$field = trim($_POST[$field]);
        } else {
            $err[$field] = "Required field.<br>";
        }
    }

    // Website (optional)
    $website = !empty(trim($_POST['website'])) ? trim($_POST['website']) : "";

    // Authorized Person
    if (!empty(trim($_POST['authorized_name']))) {
        $authorized_name = trim($_POST['authorized_name']);
        if (!preg_match("/^[A-Za-z\s]+$/", $authorized_name)) {
            $err['authorized_name'] = "Letters only.<br>";
        }
    } else {
        $err['authorized_name'] = "Enter name.<br>";
    }

    if (!empty($_POST['designation'])) {
        $designation = $_POST['designation'];
    } else {
        $err['designation'] = "Select designation.<br>";
    }

    if (!empty(trim($_POST['authorized_email'])) && filter_var($_POST['authorized_email'], FILTER_VALIDATE_EMAIL)) {
        $authorized_email = trim($_POST['authorized_email']);
    } else {
        $err['authorized_email'] = "Invalid email.<br>";
    }
    
    $authorized_phone_input = trim($_POST['authorized_phone']);
    if (!empty($authorized_phone_input) && preg_match("/^(98|97)[0-9]{8}$/", $authorized_phone_input)) {
        $authorized_phone = $authorized_phone_input;
    } else {
    $err['authorized_phone'] = "Invalid phone. Must start with 98 or 97.<br>";
}
$alternate_phone = !empty(trim($_POST['alternate_phone'])) ? trim($_POST['alternate_phone']) : "";

    // Username
    if (!empty(trim($_POST['username'])) && preg_match("/^[a-zA-Z0-9_]{5,}$/", $_POST['username'])) {
        $username = trim($_POST['username']);
    } else {
        $err['username'] = "Invalid username.<br>";
    }

    // Password validation
    if (!empty(trim($_POST['password']))) {
        $password_input = trim($_POST['password']);
        if (preg_match("/^(?=.*[\W_]).{8,}$/", $password_input)) {
            $password = password_hash($password_input, PASSWORD_DEFAULT);
        } else {
            $err['password'] = "Weak password. Must be at least 8 characters and include a special character.<br>";
        }
    } else {
        $err['password'] = "Password is required.<br>";
    }

    // Confirm password
    if (!empty(trim($_POST['confirm_password']))) {
        $confirm_password_input = trim($_POST['confirm_password']);
        if ($confirm_password_input !== $password_input) {
            $err['confirm_password'] = "Passwords do not match.<br>";
        }
    } else {
        $err['confirm_password'] = "Please confirm your password.<br>";
    }

    // Insert if no errors
    if (empty($err)) {
        $stmt = $conn->prepare("
            INSERT INTO colleges
            (college_name, college_type, affiliated_university, registration_number, accreditation_level,
             established_year, province, district, city, full_address, website,
             authorized_name, designation, authorized_email, authorized_phone, alternate_phone,
             username, password)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        ");

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param(
            "sssssisissssssssss",
            $college_name, $college_type, $affiliated_university, $registration_number, $accreditation_level,
            $established_year, $province, $district, $city, $full_address, $website,
            $authorized_name, $designation, $authorized_email, $authorized_phone, $alternate_phone,
            $username, $password
        );

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        } else {
           
            $inserted_college_id = $conn->insert_id;
            header("Location:statuscolumn.php?id=" . $inserted_college_id);
            exit;
        }
 session_start();
        if (isset($_SESSION['college_id'])) {
    header("Location: statuscolumn.php");
    exit();
}
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>College Registration</title>
<link rel="stylesheet" href="/project/css/College/collegeregist.css">
<style>
.error { color:red; font-size:0.9em; }
.form-grid { display:grid; grid-template-columns:1fr 1fr; gap:15px; }
.form-group { display:flex; flex-direction:column; margin-bottom:10px; }
.full-width { grid-column: span 2; }
.submit-btn { margin-top:20px; padding:10px 20px; font-size:1em; }
</style>
</head>
<body>
<div class="container">
    <a href="Login.php">Back</a>
<h2>College Registration Form</h2>

<form action="#" method="POST" enctype="multipart/form-data">

<h3>College Information</h3>
<div class="form-grid">
    <div class="form-group">
        <label for="college_name">College Name *</label>
        <input type="text" name="college_name" id="college_name" value="<?php echo isset($college_name) ? $college_name : ''; ?>">
        <span class="error"><?php echo isset($err['college_name']) ? $err['college_name'] : ''; ?></span>
    </div>
    <div class="form-group">
        <label for="college_type">College Type *</label>
        <select name="college_type" id="college_type">
            <option value="">Select Type</option>
            <option value="Public" <?php if(isset($college_type) && $college_type=='Public') echo 'selected'; ?>>Public</option>
            <option value="Private" <?php if(isset($college_type) && $college_type=='Private') echo 'selected'; ?>>Private</option>
            <option value="Autonomous" <?php if(isset($college_type) && $college_type=='Autonomous') echo 'selected'; ?>>Autonomous</option>
        </select>
        <span class="error"><?php echo isset($err['college_type']) ? $err['college_type'] : ''; ?></span>
    </div>
    <div class="form-group">
        <label for="affiliated_university">Affiliated University *</label>
        <input type="text" name="affiliated_university" id="affiliated_university" value="<?php echo isset($affiliated_university) ? $affiliated_university : ''; ?>">
        <span class="error"><?php echo isset($err['affiliated_university']) ? $err['affiliated_university'] : ''; ?></span>
    </div>
    <div class="form-group">
        <label for="registration_number">Registration Number *</label>
        <input type="text" name="registration_number" id="registration_number" value="<?php echo isset($registration_number) ? $registration_number : ''; ?>">
        <span class="error"><?php echo isset($err['registration_number']) ? $err['registration_number'] : ''; ?></span>
    </div>
    <div class="form-group">
        <label for="accreditation_level">Accreditation Level (NAAC Grade)</label>
        <input type="text" name="accreditation_level" id="accreditation_level" value="<?php echo isset($accreditation_level) ? $accreditation_level : ''; ?>">
        <span class="error"><?php echo isset($err['accreditation_level']) ? $err['accreditation_level'] : ''; ?></span>
    </div>
    <div class="form-group">
        <label for="established_year">Established Year *</label>
        <input type="number" name="established_year" id="established_year" min="1900" max="2099" value="<?php echo isset($established_year) ? $established_year : ''; ?>">
        <span class="error"><?php echo isset($err['established_year']) ? $err['established_year'] : ''; ?></span>
    </div>
</div>

<h3>Address & Location</h3>
<div class="form-grid">
    <div class="form-group">
        <label for="province">Province / State *</label>
        <input type="text" name="province" id="province" value="<?php echo isset($province) ? $province : ''; ?>">
        <span class="error"><?php echo isset($err['province']) ? $err['province'] : ''; ?></span>
    </div>
    <div class="form-group">
        <label for="district">District *</label>
        <input type="text" name="district" id="district" value="<?php echo isset($district) ? $district : ''; ?>">
        <span class="error"><?php echo isset($err['district']) ? $err['district'] : ''; ?></span>
    </div>
    <div class="form-group">
        <label for="city">City / Municipality *</label>
        <input type="text" name="city" id="city" value="<?php echo isset($city) ? $city : ''; ?>">
        <span class="error"><?php echo isset($err['city']) ? $err['city'] : ''; ?></span>
    </div>
    <div class="form-group full-width">
        <label for="full_address">Full Address *</label>
        <textarea name="full_address" id="full_address"><?php echo isset($full_address) ? $full_address : ''; ?></textarea>
        <span class="error"><?php echo isset($err['full_address']) ? $err['full_address'] : ''; ?></span>
    </div>
    <div class="form-group full-width">
        <label for="website">College Website (optional)</label>
        <input type="url" name="website" id="website" value="<?php echo isset($website) ? $website : ''; ?>">
    </div>
</div>

<h3>Authorized Person Details</h3>
<div class="form-grid">
    <div class="form-group">
        <label for="authorized_name">Full Name *</label>
        <input type="text" name="authorized_name" id="authorized_name" value="<?php echo isset($authorized_name) ? $authorized_name : ''; ?>">
        <span class="error"><?php echo isset($err['authorized_name']) ? $err['authorized_name'] : ''; ?></span>
    </div>
    <div class="form-group">
        <label for="designation">Designation *</label>
        <select name="designation" id="designation">
            <option value="">Select</option>
            <option value="Placement Officer" <?php if(isset($designation) && $designation=='Placement Officer') echo 'selected'; ?>>Placement Officer</option>
            <option value="HOD" <?php if(isset($designation) && $designation=='HOD') echo 'selected'; ?>>HOD</option>
            <option value="Principal" <?php if(isset($designation) && $designation=='Principal') echo 'selected'; ?>>Principal</option>
        </select>
        <span class="error"><?php echo isset($err['designation']) ? $err['designation'] : ''; ?></span>
    </div>
    <div class="form-group">
        <label for="authorized_email">Official Email *</label>
        <input type="email" name="authorized_email" id="authorized_email" value="<?php echo isset($authorized_email) ? $authorized_email : ''; ?>">
        <span class="error"><?php echo isset($err['authorized_email']) ? $err['authorized_email'] : ''; ?></span>
    </div>
    <div class="form-group">
        <label for="authorized_phone">Official Phone *</label>
        <input type="text" name="authorized_phone" id="authorized_phone" value="<?php echo isset($authorized_phone) ? $authorized_phone : ''; ?>">
        <span class="error"><?php echo isset($err['authorized_phone']) ? $err['authorized_phone'] : ''; ?></span>
    </div>
    <div class="form-group">
        <label for="alternate_phone">Alternate Contact (optional)</label>
        <input type="text" name="alternate_phone" id="alternate_phone" value="<?php echo isset($alternate_phone) ? $alternate_phone : ''; ?>">
    </div>
</div>

<h3>Login Credentials</h3>
<div class="form-grid">
    <div class="form-group">
        <label for="username">Username *</label>
        <input type="text" name="username" id="username" value="<?php echo isset($username) ? $username : ''; ?>">
        <span class="error"><?php echo isset($err['username']) ? $err['username'] : ''; ?></span>
    </div>
    <div class="form-group">
        <label for="password">Password *</label>
        <input type="password" name="password" id="password">
        <span class="error"><?php echo isset($err['password']) ? $err['password'] : ''; ?></span>
    </div>
    <div class="form-group">
        <label for="confirm_password">Confirm Password *</label>
        <input type="password" name="confirm_password" id="confirm_password">
        <span class="error"><?php echo isset($err['confirm_password']) ? $err['confirm_password'] : ''; ?></span>
    </div>
</div>

<button type="submit" class="submit-btn">Register College</button>
</form>
</div>
</body>
</html>

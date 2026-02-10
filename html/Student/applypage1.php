<?php
require_once "studentdbconnection.php";

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',   
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

/* ---------------------------------------------
   CHECK STUDENT LOGIN
--------------------------------------------- */
if (!isset($_SESSION['student_id'])) {
    die("You must be logged in as a student to apply.");
}

$student_id = $_SESSION['student_id'];

/* ---------------------------------------------
   GET post_id AND company_id FROM URL
--------------------------------------------- */
if (!isset($_GET['post_id']) || !isset($_GET['company_id'])) {
    die("Invalid internship request.");
}

$post_id = intval($_GET['post_id']);
$company_id = intval($_GET['company_id']);

/* ---------------------------------------------
   INITIALIZE VARIABLES
--------------------------------------------- */
$err = [];
$name = "";
$email = "";
$phone = "";
$dob = "";
$linkedin = "";

/* ---------------------------------------------
   VALIDATION ON FORM SUBMIT
--------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Full Name
    if (!empty($_POST['name']) && trim($_POST['name'])) {
        $name = trim($_POST['name']);
        if (!preg_match("/^[A-Z][a-zA-Z\s.'-]*$/", $name)) {
            $err['name'] = "Full name must start with a capital letter.<br />";
        }
    } else {
        $err['name'] = "Enter your full name.<br />";
    }

    // Email
    if (!empty($_POST['email']) && trim($_POST['email'])) {
        $email = trim($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $err['email'] = "Enter a valid email address.<br />";
        }
    } else {
        $err['email'] = "Enter your email.<br />";
    }

    // Phone
    if (!empty($_POST['phone']) && trim($_POST['phone'])) {
        $phone = trim($_POST['phone']);
        if (!preg_match("/^(98|97)[0-9]{8}$/", $phone)) {
            $err['phone'] = "Phone must be 10 digits and start with 98 or 97.<br />";
        }
    } else {
        $err['phone'] = "Enter your phone number.<br />";
    }

    // Date of Birth
    if (!empty($_POST['dob']) && trim($_POST['dob'])) {
        $dob = trim($_POST['dob']);
    } else {
        $err['dob'] = "Select your date of birth.<br />";
    }

    // LinkedIn (Optional)
    if (!empty($_POST['linkedin']) && trim($_POST['linkedin'])) {
        $linkedin = trim($_POST['linkedin']);
        if (!filter_var($linkedin, FILTER_VALIDATE_URL)) {
            $err['linkedin'] = "Enter a valid LinkedIn URL.<br />";
        }
    }

    /* ---------------------------------------------
       IF NO ERRORS → INSERT INTO DATABASE
    --------------------------------------------- */
    if (empty($err)) {

        // Insert into applications (parent table) WITHOUT specifying application_id
        $stmt = $conn->prepare("
            INSERT INTO applications (post_id, company_id, student_id)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("iii", $post_id, $company_id, $student_id);
        $stmt->execute();

        // Get new auto-incremented application_id
        $application_id = $stmt->insert_id;
        $_SESSION['application_id'] = $application_id;

        // Insert Step 1 data
        $stmt2 = $conn->prepare("
            INSERT INTO application_step1
            (application_id, full_name, email, phone, dob, linkedin)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt2->bind_param(
            "isssss",
            $application_id,
            $name,
            $email,
            $phone,
            $dob,
            $linkedin
        );
        $stmt2->execute();

        // Redirect to Step 2
        header("Location: applypage2.php?post_id=$post_id&company_id=$company_id");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Apply for Internship</title>
<link rel="stylesheet" href="/project/css/Student/applypage1.css">
</head>

<body>

<div class="container">
    <h1>Apply for Internship</h1>

    <form action="" method="POST">

        <!-- Hidden fields to keep IDs -->
        <input type="hidden" name="post_id" value="<?= $post_id ?>">
        <input type="hidden" name="company_id" value="<?= $company_id ?>">

        <div class="form-group">
            <label>Full Name *</label>
            <input type="text" name="name" value="<?= htmlspecialchars($name) ?>">
            <small class="error"><?= $err['name'] ?? '' ?></small>
        </div>

        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>">
            <small class="error"><?= $err['email'] ?? '' ?></small>
        </div>

        <div class="form-group">
            <label>Phone *</label>
            <input type="tel" name="phone" value="<?= htmlspecialchars($phone) ?>">
            <small class="error"><?= $err['phone'] ?? '' ?></small>
        </div>

        <div class="form-group">
            <label>Date of Birth *</label>
            <input type="date" name="dob" value="<?= htmlspecialchars($dob) ?>">
            <small class="error"><?= $err['dob'] ?? '' ?></small>
        </div>

        <div class="form-group">
            <label>LinkedIn Profile</label>
            <input type="url" name="linkedin" value="<?= htmlspecialchars($linkedin) ?>">
            <small class="error"><?= $err['linkedin'] ?? '' ?></small>
        </div>

        <div class="buttons">
            <button type="reset">Cancel</button>
            <button type="submit" class="btn-next">Next</button>
        </div>

    </form>
</div>

</body>
</html>

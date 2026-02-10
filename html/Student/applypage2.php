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

// Ensure Step 1 was completed
if (!isset($_SESSION['application_id'])) {
    die("Application session expired. Please start again.");
}

$application_id = $_SESSION['application_id'];

// ---------------------------------------------
// INITIALIZE VARIABLES
// ---------------------------------------------
$err = [];
$education = "";
$university = "";
$grad_year = "";
$experience = "";
$availability = "";
$cover_letter = "";

// ---------------------------------------------
// VALIDATION ON FORM SUBMIT
// ---------------------------------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Education Level
    if (!empty($_POST['education'])) {
        $education = $_POST['education'];
    } else {
        $err['education'] = "Select your education level.<br />";
    }

    // University
    if (!empty($_POST['university']) && trim($_POST['university'])) {
        $university = trim($_POST['university']);
        if (!preg_match("/^[A-Za-z0-9\s.'-]{3,}$/", $university)) {
            $err['university'] = "Enter a valid university/college name.<br />";
        }
    } else {
        $err['university'] = "Enter your university/college.<br />";
    }

    // Graduation Year
    if (!empty($_POST['grad_year']) && trim($_POST['grad_year'])) {
        $grad_year = trim($_POST['grad_year']);
        if (!preg_match("/^(19|20)[0-9]{2}$/", $grad_year)) {
            $err['grad_year'] = "Enter a valid graduation year (e.g., 2025).<br />";
        }
    } else {
        $err['grad_year'] = "Enter your graduation year.<br />";
    }

    // Experience Level
    if (!empty($_POST['experience'])) {
        $experience = $_POST['experience'];
    } else {
        $err['experience'] = "Select your experience level.<br />";
    }

    // Availability
    if (!empty($_POST['availability'])) {
        $availability = $_POST['availability'];
    } else {
        $err['availability'] = "Select your availability.<br />";
    }

    // Cover Letter
    if (!empty($_POST['cover_letter']) && trim($_POST['cover_letter'])) {
        $cover_letter = trim($_POST['cover_letter']);
        if (strlen($cover_letter) < 20) {
            $err['cover_letter'] = "Cover letter must be at least 20 characters.<br />";
        }
    } else {
        $err['cover_letter'] = "Enter your cover letter.<br />";
    }

    // ---------------------------------------------
    // IF NO ERRORS → INSERT INTO DATABASE
    // ---------------------------------------------
    if (empty($err)) {

        $stmt = $conn->prepare("
            INSERT INTO application_step2
            (application_id, education_level, university, graduation_year, experience_level, availability, cover_letter)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ississs",
            $application_id,
            $education,
            $university,
            $grad_year,
            $experience,
            $availability,
            $cover_letter
        );

        $stmt->execute();

        header("Location: applypage3.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Apply – Education & Experience</title>
<link rel="stylesheet" href="/project/css/Student/applypage2.css">
</head>

<body>

<div class="container">
    <h1>Education & Experience</h1>
    <p>Designer Internship – Fashion House (Kapan)</p>

    <form action="" method="POST">

        <div class="form-group">
            <label>Education Level *</label>
            <select name="education">
                <option value="">Select</option>
                <option <?= ($education=="High School")?"selected":"" ?>>High School</option>
                <option <?= ($education=="Diploma")?"selected":"" ?>>Diploma</option>
                <option <?= ($education=="Bachelor")?"selected":"" ?>>Bachelor</option>
                <option <?= ($education=="Master")?"selected":"" ?>>Master</option>
            </select>
            <small class="error"><?= $err['education'] ?? '' ?></small>
        </div>

        <div class="form-group">
            <label>University / College *</label>
            <input type="text" name="university" value="<?= htmlspecialchars($university) ?>">
            <small class="error"><?= $err['university'] ?? '' ?></small>
        </div>

        <div class="form-group">
            <label>Graduation Year *</label>
            <input type="number" name="grad_year" value="<?= htmlspecialchars($grad_year) ?>" placeholder="2025">
            <small class="error"><?= $err['grad_year'] ?? '' ?></small>
        </div>

        <div class="form-group">
            <label>Experience Level *</label>
            <select name="experience">
                <option value="">Select</option>
                <option <?= ($experience=="Beginner")?"selected":"" ?>>Beginner</option>
                <option <?= ($experience=="Intermediate")?"selected":"" ?>>Intermediate</option>
                <option <?= ($experience=="Advanced")?"selected":"" ?>>Advanced</option>
            </select>
            <small class="error"><?= $err['experience'] ?? '' ?></small>
        </div>

        <div class="form-group">
            <label>Availability *</label>
            <select name="availability">
                <option value="">Select</option>
                <option <?= ($availability=="Immediately")?"selected":"" ?>>Immediately</option>
                <option <?= ($availability=="Within 1 Week")?"selected":"" ?>>Within 1 Week</option>
                <option <?= ($availability=="Within 1 Month")?"selected":"" ?>>Within 1 Month</option>
            </select>
            <small class="error"><?= $err['availability'] ?? '' ?></small>
        </div>

        <div class="form-group">
            <label>Cover Letter *</label>
            <textarea name="cover_letter" rows="5"><?= htmlspecialchars($cover_letter) ?></textarea>
            <small class="error"><?= $err['cover_letter'] ?? '' ?></small>
        </div>

        <div class="buttons">
            <a href="applypage1.php" class="btn-next">Previous</a>
            <button type="submit" class="btn-next">Next</button>
        </div>

    </form>
</div>

</body>
</html>
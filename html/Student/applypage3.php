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

// Ensure Step 1 & 2 were completed
if (!isset($_SESSION['application_id'])) {
    die("Application session expired. Please start again.");
}

$application_id = $_SESSION['application_id'];

// ---------------------------------------------
// INITIALIZE VARIABLES
// ---------------------------------------------
$err = [];
$resume = "";
$portfolio = "";
$portfolio_url = "";
$confirm = "";

// ---------------------------------------------
// VALIDATION ON FORM SUBMIT
// ---------------------------------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Resume (Required)
    if (!empty($_FILES['resume']['name'])) {

        $resume = $_FILES['resume'];
        $allowed = ['pdf', 'doc', 'docx'];
        $ext = strtolower(pathinfo($resume['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $err['resume'] = "Resume must be a PDF, DOC, or DOCX file.<br />";
        }

        if ($resume['size'] > 5 * 1024 * 1024) {
            $err['resume'] = "Resume file must be less than 5MB.<br />";
        }

    } else {
        $err['resume'] = "Upload your resume.<br />";
    }

    // Portfolio (Optional)
    if (!empty($_FILES['portfolio']['name'])) {

        $portfolio = $_FILES['portfolio'];
        $allowed_portfolio = ['pdf', 'zip'];
        $ext2 = strtolower(pathinfo($portfolio['name'], PATHINFO_EXTENSION));

        if (!in_array($ext2, $allowed_portfolio)) {
            $err['portfolio'] = "Portfolio must be a PDF or ZIP file.<br />";
        }

        if ($portfolio['size'] > 10 * 1024 * 1024) {
            $err['portfolio'] = "Portfolio file must be less than 10MB.<br />";
        }
    }

    // Portfolio URL (Optional)
    if (!empty($_POST['portfolio_url']) && trim($_POST['portfolio_url'])) {
        $portfolio_url = trim($_POST['portfolio_url']);
        if (!filter_var($portfolio_url, FILTER_VALIDATE_URL)) {
            $err['portfolio_url'] = "Enter a valid portfolio URL.<br />";
        }
    }

    // Confirmation Checkbox
    if (!isset($_POST['confirm'])) {
        $err['confirm'] = "You must confirm the information.<br />";
    }

    // ---------------------------------------------
    // IF NO ERRORS → FINAL SUBMIT
    // ---------------------------------------------
    if (empty($err)) {

        // Save resume
        $resume_path = "uploads/resume_" . time() . "." . $ext;
        move_uploaded_file($resume['tmp_name'], $resume_path);

        // Save portfolio (optional)
        $portfolio_path = NULL;
        if (!empty($_FILES['portfolio']['name'])) {
            $portfolio_path = "uploads/portfolio_" . time() . "." . $ext2;
            move_uploaded_file($portfolio['tmp_name'], $portfolio_path);
        }

        // Insert into application_step3
        $stmt = $conn->prepare("
            INSERT INTO application_step3
            (application_id, resume_path, portfolio_path, portfolio_url)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "isss",
            $application_id,
            $resume_path,
            $portfolio_path,
            $portfolio_url
        );

        $stmt->execute();

        // Redirect to success page
        header("Location: BrowseInternship.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Apply – Documents & Submit</title>
<link rel="stylesheet" href="/project/css/Student/applypage3.css">
</head>

<body>

<div class="container">
    <h1>Documents & Submit</h1>
    <p>Designer Internship – Fashion House (Kapan)</p>

    <form action="" method="POST" enctype="multipart/form-data">

        <!-- Resume -->
        <div class="form-group">
            <label>Upload Resume/CV *</label>
            <input type="file" name="resume" accept=".pdf,.doc,.docx">
            <small class="error"><?= $err['resume'] ?? '' ?></small>
        </div>

        <!-- Portfolio -->
        <div class="form-group">
            <label>Portfolio (Optional)</label>
            <input type="file" name="portfolio" accept=".pdf,.zip">
            <small class="error"><?= $err['portfolio'] ?? '' ?></small>
        </div>

        <!-- Portfolio URL -->
        <div class="form-group">
            <label>Portfolio URL (Optional)</label>
            <input type="url" name="portfolio_url" value="<?= htmlspecialchars($portfolio_url) ?>" placeholder="https://yourportfolio.com">
            <small class="error"><?= $err['portfolio_url'] ?? '' ?></small>
        </div>

        <!-- Confirmation -->
        <div class="checkbox-group">
            <input type="checkbox" name="confirm" <?= isset($_POST['confirm']) ? 'checked' : '' ?>>
            <label>I confirm that the information provided is correct.</label>
            <small class="error"><?= $err['confirm'] ?? '' ?></small>
        </div>

        <!-- Buttons -->
        <div class="buttons">
            <a href="applypage2.php" class="btn-prev">Previous</a>
            <button type="submit" class="btn-submit">Submit</button>
        </div>

    </form>
</div>

</body>
</html>
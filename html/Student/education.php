<?php
session_start();
require_once "studentdbconnection.php";

if (!isset($_SESSION['student_id'])) {
    header("Location: studentLogin.php");
    exit;
}

$student_id = $_SESSION['student_id'];
$err = [];

/* FETCH EXISTING DATA */
$stmt = $conn->prepare("SELECT * FROM education_details WHERE student_id=?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

/* Pre-fill values */
$level       = $data['level']       ?? ($_POST['level'] ?? '');
$major       = $data['major']       ?? ($_POST['major'] ?? '');
$institution = $data['institution'] ?? ($_POST['institution'] ?? '');
$university  = $data['university']  ?? ($_POST['university'] ?? '');
$startYear   = $data['startYear']   ?? ($_POST['startYear'] ?? '');
$endYear     = $data['endYear']     ?? ($_POST['endYear'] ?? '');
$grade       = $data['grade']       ?? ($_POST['grade'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* VALIDATION */
    if ($level === '')       $err['level'] = "Select education level.";
    if ($major === '')       $err['major'] = "Enter major.";
    if ($institution === '') $err['institution'] = "Enter institution.";
    if ($university === '')  $err['university'] = "Enter university.";
    if ($startYear === '')   $err['startYear'] = "Enter start year.";
    if ($endYear === '')     $err['endYear'] = "Enter end year.";
    if ($grade === '')       $err['grade'] = "Enter grade.";

    if (empty($err)) {

        /* If data exists → UPDATE */
        if ($data) {

            $stmt = $conn->prepare("
                UPDATE education_details SET
                    level=?, major=?, institution=?, university=?, startYear=?, endYear=?, grade=?
                WHERE student_id=?
            ");

            $stmt->bind_param(
                "sssssssi",
                $level, $major, $institution, $university,
                $startYear, $endYear, $grade,
                $student_id
            );

            $stmt->execute();
        }

        /* If no data → INSERT */
        else {

            $stmt = $conn->prepare("
                INSERT INTO education_details
                (student_id, level, major, institution, university, startYear, endYear, grade)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "issssiis",
                $student_id, $level, $major, $institution,
                $university, $startYear, $endYear, $grade
            );

            $stmt->execute();
        }

        header("Location: preview.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Education Details</title>
<link rel="stylesheet" href="/project/css/Student/education.css">
</head>
<body>

<div class="form-container">
<h2 class="form-title">Education Details</h2>

<form id="educationForm" method="POST">

    <div class="form-row">
        <div class="form-group">
            <label for="level">Education Level *</label>
            <select id="level" name="level">
                <option value="">Select level</option>
                <option value="High School" <?php if($level=="High School") echo "selected"; ?>>High School</option>
                <option value="Diploma" <?php if($level=="Diploma") echo "selected"; ?>>Diploma</option>
                <option value="Bachelor's" <?php if($level=="Bachelor's") echo "selected"; ?>>Bachelor's</option>
                <option value="Master's" <?php if($level=="Master's") echo "selected"; ?>>Master's</option>
                <option value="PhD" <?php if($level=="PhD") echo "selected"; ?>>PhD</option>
            </select>
            <span class="error"><?php echo $err['level'] ?? ''; ?></span>
        </div>

        <div class="form-group">
            <label for="major">Major / Field of Study *</label>
            <input type="text" id="major" name="major" value="<?php echo htmlspecialchars($major); ?>">
            <span class="error"><?php echo $err['major'] ?? ''; ?></span>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="institution">Institution Name *</label>
            <input type="text" id="institution" name="institution" value="<?php echo htmlspecialchars($institution); ?>">
            <span class="error"><?php echo $err['institution'] ?? ''; ?></span>
        </div>

        <div class="form-group">
            <label for="university">University / Board *</label>
            <input type="text" id="university" name="university" value="<?php echo htmlspecialchars($university); ?>">
            <span class="error"><?php echo $err['university'] ?? ''; ?></span>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="startYear">Start Year *</label>
            <input type="number" id="startYear" name="startYear" value="<?php echo htmlspecialchars($startYear); ?>">
            <span class="error"><?php echo $err['startYear'] ?? ''; ?></span>
        </div>

        <div class="form-group">
            <label for="endYear">End Year *</label>
            <input type="number" id="endYear" name="endYear" value="<?php echo htmlspecialchars($endYear); ?>">
            <span class="error"><?php echo $err['endYear'] ?? ''; ?></span>
        </div>
    </div>

    <div class="form-group full-width">
        <label for="grade">Grade / Percentage *</label>
        <input type="text" id="grade" name="grade" value="<?php echo htmlspecialchars($grade); ?>">
        <span class="error"><?php echo $err['grade'] ?? ''; ?></span>
    </div>

    <button type="submit" class="submit-btn">Save & Next</button>

</form>
</div>

</body>
</html>
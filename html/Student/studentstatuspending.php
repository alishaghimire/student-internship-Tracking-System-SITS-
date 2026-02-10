<?php
session_start();
include "studentdbconnection.php";

// Make sure student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: studentlogin.php");
    exit;
}

$student_id = $_SESSION['student_id'];

// Fetch student registration info
$sql = "SELECT * FROM students_registrtaion WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    die("Student not found.");
}

$status = $student['status']; // pending, approved, rejected
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Registration Status</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="/project/css/Student/studentstaus.css">
</head>
<body>
<div class="container">

    <h2>Student Registration Status</h2>

    <!-- STATUS BOX -->
    <div class="status-box">
        <div class="status-badge 
            <?php echo $status == 'approved' ? 'approved' : ($status == 'rejected' ? 'rejected' : 'pending'); ?>">
            <?php echo strtoupper($status); ?>
        </div>

        <?php if ($status == 'pending') { ?>
            <p>Your registration is under review. Please wait for approval.</p>
        <?php } elseif ($status == 'approved') { ?>
            <p>Your student account has been approved! You can now log in.</p>
        <?php } else { ?>
            <p>Your registration was rejected. Please contact the admin.</p>
        <?php } ?>
    </div>

    <!-- PROGRESS BAR -->
    <div class="progress-container">
        <div class="progress-label">
            Verification Progress: 
            <?php echo $status == 'approved' ? '100%' : ($status == 'pending' ? '60%' : '0%'); ?>
        </div>
        <div class="progress-bar">
            <div class="progress-fill" 
                 style="width: <?php echo $status == 'approved' ? '100%' : ($status == 'pending' ? '60%' : '20%'); ?>;">
            </div>
        </div>
    </div>

    <!-- STEPS -->
    <div class="steps">

        <div class="step completed">
            <i class="fa-solid fa-check-circle"></i> Application Submitted
        </div>

        <div class="step <?php echo ($status == 'approved') ? 'completed' : ''; ?>">
            <i class="<?php echo ($status == 'approved') ? 'fa-solid fa-check-circle' : 'fa-regular fa-circle'; ?>"></i>
            Document Verification
        </div>

        <div class="step <?php echo ($status == 'approved') ? 'completed' : ''; ?>">
            <i class="<?php echo ($status == 'approved') ? 'fa-solid fa-check-circle' : 'fa-regular fa-circle'; ?>"></i>
            Final Review
        </div>

        <div class="step <?php echo ($status == 'approved') ? 'completed' : ''; ?>">
            <i class="<?php echo ($status == 'approved') ? 'fa-solid fa-check-circle' : 'fa-regular fa-circle'; ?>"></i>
            Approval
        </div>

    </div>

    <!-- SUBMITTED INFORMATION -->
    <div class="info-box">
        <h3>Submitted Information</h3>
        <div class="info-grid">
            <div class="info-item"><span>Full Name</span> <?php echo htmlspecialchars($student['full_name']); ?></div>
            <div class="info-item"><span>Student ID</span> <?php echo htmlspecialchars($student['student_code']); ?></div>
            <div class="info-item"><span>Email</span> <?php echo htmlspecialchars($student['email']); ?></div>
            <div class="info-item"><span>Phone</span> <?php echo htmlspecialchars($student['phone']); ?></div>
            <div class="info-item"><span>Major</span> <?php echo htmlspecialchars($student['major']); ?></div>
            <div class="info-item"><span>College</span> <?php echo htmlspecialchars($student['college_name']); ?></div>
        </div>
    </div>

    <!-- BUTTON LOGIC -->
   <!-- BUTTON LOGIC -->
<?php if ($status == 'Pending') { ?>
    <button type="button" class="button" onclick="location.reload()">Check Status Again</button>
<?php } elseif ($status == 'Approved') { ?>
    <button type="button" class="button" onclick="window.location.href='studentLogin.php'">Login Now</button>
<?php } else { ?>
    <button type="button" class="button" onclick="window.location.href='mailto:college@support.com'">Contact College</button>
<?php } ?>

</div>
</body>
</html>

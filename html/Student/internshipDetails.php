<?php
require_once "studentdbconnection.php";

// Ensure ID is provided
if (!isset($_GET['id'])) {
    die("Invalid internship ID");
}

$id = intval($_GET['id']);

// Fetch internship details
$query = $conn->prepare("
    SELECT 
        title,
        company_name,
        location,
        duration,
        stipend,
        description,
        department,
        type,
        deadline
    FROM postposition
    WHERE id = ? 
");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$job = $result->fetch_assoc();

if (!$job) {
    die("Internship not found or not approved.");
}

/* ---------------- RESPONSIBILITIES ---------------- */
$resQuery = $conn->prepare("
    SELECT responsibility 
    FROM position_responsibilities 
    WHERE position_id = ?
");
$resQuery->bind_param("i", $id);
$resQuery->execute();
$responsibilities = $resQuery->get_result();

/* ---------------- SKILLS ---------------- */
$skillQuery = $conn->prepare("
    SELECT skill 
    FROM position_skills 
    WHERE position_id = ?
");
$skillQuery->bind_param("i", $id);
$skillQuery->execute();
$skills = $skillQuery->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($job['title']) ?> - Internship Details</title>
<link rel="stylesheet" href="/project/css/Student/internshipdetails.css">
</head>
<body>

<div class="container">

    <!-- TITLE + COMPANY -->
    <h1 class="job-title"><?= htmlspecialchars($job['title']) ?></h1>
    <p class="company"><?= htmlspecialchars($job['company_name']) ?> — <?= htmlspecialchars($job['location']) ?></p>

    <!-- JOB INFO GRID -->
    <div class="job-info">
        <div><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></div>
        <div><strong>Duration:</strong> <?= htmlspecialchars($job['duration']) ?></div>
        <div><strong>Stipend:</strong> Rs. <?= number_format($job['stipend']) ?>/month</div>
        <div><strong>Type:</strong> <?= htmlspecialchars($job['type']) ?></div>
        <div><strong>Deadline:</strong> <?= htmlspecialchars($job['deadline']) ?></div>
        <div><strong>Department:</strong> <?= htmlspecialchars($job['department']) ?></div>
    </div>

    <!-- DESCRIPTION -->
    <section class="section">
        <h2>Job Description</h2>
        <p><?= nl2br(htmlspecialchars($job['description'])) ?></p>
    </section>

    <!-- RESPONSIBILITIES -->
    <section class="section">
        <h2>Key Responsibilities</h2>
        <ul>
            <?php while ($r = $responsibilities->fetch_assoc()): ?>
                <li><?= htmlspecialchars($r['responsibility']) ?></li>
            <?php endwhile; ?>
        </ul>
    </section>

    <!-- SKILLS -->
    <section class="section">
        <h2>Skills Required</h2>
        <div class="skills">
            <?php while ($s = $skills->fetch_assoc()): ?>
                <span><?= htmlspecialchars($s['skill']) ?></span>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- STATIC PERKS (until you show me the perks table) -->
    <section class="section">
        <h2>Perks & Benefits</h2>
        <ul>
            <li>Certificate</li>
            <li>Letter of recommendation</li>
            <li>Flexible work hours</li>
            <li>Free lunch</li>
        </ul>
    </section>


</div>

</body>
</html>
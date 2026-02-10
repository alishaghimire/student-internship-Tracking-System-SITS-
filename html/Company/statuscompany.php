<?php
include "dbconnectcompany.php";
session_start();

$company_id = $_SESSION['company_id'];

$sql = "SELECT * FROM company_registration WHERE company_id = '$company_id'";
$result = $conn->query($sql);
$company = $result->fetch_assoc();

$status = $company['status']; // pending, approved, rejected
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Company Registration Status</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="/project/css/Company/statuscompany.css">
</head>

<body>

<div class="container">

    <h2>Company Registration Status</h2>

    <!-- STATUS BOX -->
    <div class="status-box">
        <div class="status-badge 
            <?php echo $status == 'approved' ? 'approved' : ($status == 'rejected' ? 'rejected' : 'pending'); ?>">
            <?php echo strtoupper($status); ?>
        </div>

        <?php if ($status == 'pending') { ?>
            <p>Your registration is under review. This usually takes 1–2 business days.</p>
        <?php } elseif ($status == 'approved') { ?>
            <p>Your company has been approved! You can now log in.</p>
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

    <!-- Step 1: Always completed -->
    <div class="step completed">
        <i class="fa-solid fa-check-circle"></i> Application Submitted
    </div>

    <!-- Step 2: Completed if NOT pending -->
    <div class="step <?php echo ($status == 'approved') ? 'completed' : ''; ?>">
        <i class="<?php echo ($status == 'approved') ? 'fa-solid fa-check-circle' : 'fa-regular fa-circle'; ?>"></i>
        Document Verification
    </div>

    <!-- Step 3: Completed if approved -->
    <div class="step <?php echo ($status == 'approved') ? 'completed' : ''; ?>">
        <i class="<?php echo ($status == 'approved') ? 'fa-solid fa-check-circle' : 'fa-regular fa-circle'; ?>"></i>
        Final Review
    </div>

    <!-- Step 4: Completed if approved -->
    <div class="step <?php echo ($status == 'approved') ? 'completed' : ''; ?>">
        <i class="<?php echo ($status == 'approved') ? 'fa-solid fa-check-circle' : 'fa-regular fa-circle'; ?>"></i>
        Approval
    </div>

</div>

    <!-- SUBMITTED INFORMATION -->
    <div class="info-box">
        <h3>Submitted Information</h3>

        <div class="info-grid">
            <div class="info-item"><span>Company Name</span> <?php echo $company['company_name']; ?></div>
            <div class="info-item"><span>Industry</span> <?php echo $company['industry']; ?></div>
            <div class="info-item"><span>Email</span> <?php echo $company['email']; ?></div>
            <div class="info-item"><span>Phone</span> <?php echo $company['phone']; ?></div>
            <div class="info-item"><span>Location</span> <?php echo $company['city_state']; ?></div>
            <div class="info-item"><span>Submitted</span> <?php echo $company['submitted_at']; ?></div>
            <div class="info-item"><span>Registration Number</span> <?php echo $company['registration_number']; ?></div>
        </div>
    </div>

    <!-- BUTTON LOGIC -->
    <?php if ($status == 'pending') { ?>
        <button class="button" onclick="location.reload()">Check Status Again</button>

    <?php } elseif ($status == 'approved') { ?>
        <button class="button" onclick="window.location.href='companylogin.php'">
            Login Now
        </button>

    <?php } else { ?>
        <button class="button" onclick="window.location.href='mailto:admin@support.com'">
            Contact Admin
        </button>
    <?php } ?>

</div>

</body>
</html>
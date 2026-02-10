<?php
include 'collegedb_connection.php';

// --------------------------------------
// 1. GET COLLEGE ID FROM URL
// --------------------------------------
session_start();

$college_id = $_GET['id'] 
              ?? $_SESSION['college_id'] 
              ?? null;

if (!$college_id) {
    die("No registration found. Please register first.");
}
// --------------------------------------
// 2. FETCH COLLEGE DATA
// --------------------------------------
$sql = "SELECT * FROM colleges WHERE college_id = '$college_id'";
$result = mysqli_query($conn, $sql);
$college = mysqli_fetch_assoc($result);

if (!$college) {
    die("No college record found.");
}

// --------------------------------------
// 3. STATUS EXACTLY AS STORED IN DATABASE
// --------------------------------------
$status = $college['status']; 
// Values: Pending, Approved, Rejected
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>College Registration Status</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* (CSS unchanged — keeping your full design) */
body {
    font-family: "Poppins", sans-serif;
    background: #f4f4f4;
    margin: 0;
    padding: 30px;
}

.container {
    max-width: 850px;
    margin: auto;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

h2 {
    text-align: center;
    margin-bottom: 25px;
    font-size: 26px;
    font-weight: 600;
}

.status-box {
    text-align: center;
    margin-bottom: 30px;
}

.status-badge {
    display: inline-block;
    padding: 10px 22px;
    border-radius: 25px;
    font-weight: 600;
    color: #fff;
    font-size: 15px;
}

.status-badge.pending { background: #f0ad4e; }
.status-badge.approved { background: #28a745; }
.status-badge.rejected { background: #dc3545; }

.progress-container {
    margin-bottom: 30px;
}

.progress-label {
    margin-bottom: 8px;
    font-weight: 500;
    font-size: 15px;
}

.progress-bar {
    width: 100%;
    height: 20px;
    background: #e5e5e5;
    border-radius: 10px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: #28a745;
    text-align: right;
    padding-right: 8px;
    color: #fff;
    font-weight: 600;
    line-height: 20px;
    font-size: 13px;
}

.steps {
    display: flex;
    justify-content: space-between;
    margin-bottom: 35px;
}

.step {
    flex: 1;
    text-align: center;
    position: relative;
}

.step:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 14px;
    right: -50%;
    width: 100%;
    height: 3px;
    background: #ddd;
    z-index: -1;
}

.step i {
    display: block;
    font-size: 22px;
    margin-bottom: 6px;
    color: #ccc;
}

.step.completed i {
    color: #28a745;
}

.info-box {
    margin-bottom: 30px;
}

.info-box h3 {
    margin-bottom: 15px;
    font-size: 20px;
    font-weight: 600;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.info-item {
    background: #f9f9f9;
    padding: 12px;
    border-radius: 6px;
    font-size: 15px;
}

.info-item span {
    font-weight: 600;
}

.button {
    display: block;
    width: fit-content;
    margin: 20px auto 0;
    padding: 12px 25px;
    border: none;
    border-radius: 6px;
    background: #007bff;
    color: #fff;
    font-size: 15px;
    cursor: pointer;
    text-decoration: none;
}

.button:hover {
    background: #0056b3;
}
</style>
</head>

<body>

<div class="container">
    <h2>College Registration Status</h2>

    <div class="status-box">
        <div class="status-badge <?php echo strtolower($status); ?>">
            <?php echo strtoupper($status); ?>
        </div>

        <?php if ($status == 'Pending') { ?>
            <p>Your registration is under review. This usually takes 1–2 business days.</p>
        <?php } elseif ($status == 'Approved') { ?>
            <p>Your college has been approved! You can now log in.</p>
        <?php } else { ?>
            <p>Your registration was rejected. Please contact the admin.</p>
        <?php } ?>
    </div>

    <div class="progress-container">
        <div class="progress-label">
            Verification Progress:
            <?php 
                echo $status == 'Approved' ? '100%' : 
                     ($status == 'Pending' ? '60%' : '0%'); 
            ?>
        </div>

        <div class="progress-bar">
            <div class="progress-fill" style="width:
                <?php 
                    echo $status == 'Approved' ? '100%' : 
                         ($status == 'Pending' ? '60%' : '20%'); 
                ?>;">
            </div>
        </div>
    </div>

    <div class="steps">
        <div class="step completed">
            <i class="fa-solid fa-check-circle"></i>
            Application Submitted
        </div>

        <div class="step <?php echo ($status != 'Pending') ? 'completed' : ''; ?>">
            <i class="<?php echo ($status != 'Pending') ? 'fa-solid fa-check-circle' : 'fa-regular fa-circle'; ?>"></i>
            Document Verification
        </div>

        <div class="step <?php echo ($status == 'Approved') ? 'completed' : ''; ?>">
            <i class="<?php echo ($status == 'Approved') ? 'fa-solid fa-check-circle' : 'fa-regular fa-circle'; ?>"></i>
            Final Review
        </div>

        <div class="step <?php echo ($status == 'Approved') ? 'completed' : ''; ?>">
            <i class="<?php echo ($status == 'Approved') ? 'fa-solid fa-check-circle' : 'fa-regular fa-circle'; ?>"></i>
            Approval
        </div>
    </div>

    <div class="info-box">
        <h3>Submitted Information</h3>

        <div class="info-grid">
            <div class="info-item"><span>College Name:</span> <?php echo $college['college_name']; ?></div>
            <div class="info-item"><span>Type:</span> <?php echo $college['college_type']; ?></div>
            <div class="info-item"><span>Affiliated University:</span> <?php echo $college['affiliated_university']; ?></div>
            <div class="info-item"><span>Registration Number:</span> <?php echo $college['registration_number']; ?></div>
            <div class="info-item"><span>Accreditation Level:</span> <?php echo $college['accreditation_level']; ?></div>
            <div class="info-item"><span>Established Year:</span> <?php echo $college['established_year']; ?></div>
            <div class="info-item"><span>Province:</span> <?php echo $college['province']; ?></div>
            <div class="info-item"><span>District:</span> <?php echo $college['district']; ?></div>
            <div class="info-item"><span>City:</span> <?php echo $college['city']; ?></div>
            <div class="info-item"><span>Full Address:</span> <?php echo $college['full_address']; ?></div>
            <div class="info-item"><span>Authorized Name:</span> <?php echo $college['authorized_name']; ?></div>
            <div class="info-item"><span>Designation:</span> <?php echo $college['designation']; ?></div>
            <div class="info-item"><span>Email:</span> <?php echo $college['authorized_email']; ?></div>
            <div class="info-item"><span>Phone:</span> <?php echo $college['authorized_phone']; ?></div>
        </div>
    </div>

    <?php if ($status == 'Pending') { ?>
        <button class="button" onclick="location.reload()">Check Status Again</button>
    <?php } elseif ($status == 'Approved') { ?>
        <button class="button" onclick="window.location.href='Login.php'">Login Now</button>
    <?php } else { ?>
        <button class="button" onclick="window.location.href='mailto:admin@support.com'">Contact Admin</button>
    <?php } ?>

</div>

</body>
</html>
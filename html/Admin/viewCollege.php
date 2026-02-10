<?php
session_start();
require_once "db_aadminconnect.php"; // adjust to your DB connection file

// Get college_id from query string
$college_id = intval($_GET['id'] ?? 0);

if ($college_id <= 0) {
    die("Invalid college ID.");
}

// Fetch college details
$stmt = $conn->prepare("SELECT * FROM colleges WHERE college_id=? LIMIT 1");
$stmt->bind_param("i", $college_id);
$stmt->execute();
$result = $stmt->get_result();
$college = $result->fetch_assoc();
$stmt->close();

if (!$college) {
    die("College not found.");
}

// Avatar initials
$parts = explode(" ", $college['college_name']);
$initials = "";
foreach ($parts as $p) { $initials .= strtoupper($p[0]); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($college['college_name']); ?> - Details</title>
  <link rel="stylesheet" href="ViewCollege.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>

  <!-- Back Button -->
  <a href="AdminDashboard.php" class="btn-back">
    <i class="fa-solid fa-arrow-left"></i> Back
  </a>

  <div class="college-container">
    <div class="college-header">
      <div class="avatar"><?php echo $initials; ?></div>
      <div class="info">
        <h2><?php echo htmlspecialchars($college['college_name']); ?></h2>
        <p>Type: <?php echo htmlspecialchars($college['college_type']); ?></p>
        <p>Status: <span class="status <?php echo strtolower($college['status']); ?>">
          <?php echo ucfirst($college['status']); ?>
        </span></p>
      </div>
    </div>

    <section class="college-section">
      <h3><i class="fa-solid fa-id-card"></i> Registration Info</h3>
      <ul>
        <li><strong>Registration Number:</strong> <?php echo htmlspecialchars($college['registration_number']); ?></li>
        <li><strong>Affiliated University:</strong> <?php echo htmlspecialchars($college['affiliated_university']); ?></li>
        <li><strong>Accreditation Level:</strong> <?php echo htmlspecialchars($college['accreditation_level']); ?></li>
        <li><strong>Established Year:</strong> <?php echo htmlspecialchars($college['established_year']); ?></li>
      </ul>
    </section>

    <section class="college-section">
      <h3><i class="fa-solid fa-location-dot"></i> Location</h3>
      <ul>
        <li><strong>Province:</strong> <?php echo htmlspecialchars($college['province']); ?></li>
        <li><strong>District:</strong> <?php echo htmlspecialchars($college['district']); ?></li>
        <li><strong>City:</strong> <?php echo htmlspecialchars($college['city']); ?></li>
        <li><strong>Address:</strong> <?php echo htmlspecialchars($college['full_address']); ?></li>
      </ul>
    </section>

    <section class="college-section">
      <h3><i class="fa-solid fa-envelope"></i> Contact Info</h3>
      <ul>
        <li><strong>Email:</strong> <?php echo htmlspecialchars($college['authorized_email']); ?></li>
        <li><strong>Phone:</strong> <?php echo htmlspecialchars($college['authorized_phone']); ?></li>
        <li><strong>Alternate Phone:</strong> <?php echo htmlspecialchars($college['alternate_phone']); ?></li>
        <li><strong>Website:</strong> 
          <a href="<?php echo htmlspecialchars($college['website']); ?>" target="_blank">
            <?php echo htmlspecialchars($college['website']); ?>
          </a>
        </li>
      </ul>
    </section>

    <section class="college-section">
      <h3><i class="fa-solid fa-user-tie"></i> Authorized Person</h3>
      <ul>
        <li><strong>Name:</strong> <?php echo htmlspecialchars($college['authorized_name']); ?></li>
        <li><strong>Designation:</strong> <?php echo htmlspecialchars($college['designation']); ?></li>
      </ul>
    </section>

    <section class="college-section">
      <h3><i class="fa-solid fa-clock"></i> Timeline</h3>
      <ul>
        <li><strong>Created At:</strong> <?php echo $college['created_at']; ?></li>
      </ul>
    </section>
  </div>
</body>
</html>
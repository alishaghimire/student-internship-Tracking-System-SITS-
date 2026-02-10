<?php
session_start();
require_once "db_aadminconnect.php"; // adjust to your DB connection file

// Get company_id from query string
$company_id = intval($_GET['id'] ?? 0);

if ($company_id <= 0) {
    die("Invalid company ID.");
}

// Fetch company details
$stmt = $conn->prepare("SELECT * FROM company_registration WHERE company_id=? LIMIT 1");
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();
$company = $result->fetch_assoc();
$stmt->close();

if (!$company) {
    die("Company not found.");
}

// Avatar initials
$parts = explode(" ", $company['company_name']);
$initials = "";
foreach ($parts as $p) { $initials .= strtoupper($p[0]); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($company['company_name']); ?> - Details</title>
  <link rel="stylesheet" href="viewcompany.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>

  <!-- Back Button -->
  <a href="javascript:history.back()" class="btn-back">
    <i class="fa-solid fa-arrow-left"></i> Back
  </a>

  <div class="company-container">
    <div class="company-header">
      <div class="avatar"><?php echo $initials; ?></div>
      <div class="info">
        <h2><?php echo htmlspecialchars($company['company_name']); ?></h2>
        <p>Industry: <?php echo htmlspecialchars($company['industry']); ?></p>
        <p>Status: <span class="status <?php echo strtolower($company['status']); ?>">
          <?php echo ucfirst($company['status']); ?>
        </span></p>
      </div>
    </div>

    <section class="company-section">
      <h3><i class="fa-solid fa-id-card"></i> Registration Info</h3>
      <ul>
        <li><strong>Registration Number:</strong> <?php echo htmlspecialchars($company['registration_number']); ?></li>
        <li><strong>Tax ID:</strong> <?php echo htmlspecialchars($company['tax_id']); ?></li>
        <li><strong>Established Year:</strong> <?php echo htmlspecialchars($company['established_year']); ?></li>
        <li><strong>Employees:</strong> <?php echo htmlspecialchars($company['num_employees']); ?></li>
      </ul>
    </section>

    <section class="company-section">
      <h3><i class="fa-solid fa-envelope"></i> Contact Info</h3>
      <ul>
        <li><strong>Email:</strong> <?php echo htmlspecialchars($company['email']); ?></li>
        <li><strong>Phone:</strong> <?php echo htmlspecialchars($company['phone']); ?></li>
        <li><strong>Website:</strong> <a href="<?php echo htmlspecialchars($company['website']); ?>" target="_blank">
          <?php echo htmlspecialchars($company['website']); ?></a></li>
        <li><strong>Location:</strong> <?php echo htmlspecialchars($company['city_state']); ?></li>
        <li><strong>Address:</strong> <?php echo htmlspecialchars($company['full_address']); ?></li>
      </ul>
    </section>

    <section class="company-section">
      <h3><i class="fa-solid fa-file-lines"></i> Description</h3>
      <p><?php echo nl2br(htmlspecialchars($company['company_description'])); ?></p>
    </section>

    <section class="company-section">
      <h3><i class="fa-solid fa-clock"></i> Timeline</h3>
      <ul>
        <li><strong>Submitted At:</strong> <?php echo $company['submitted_at']; ?></li>
        <li><strong>Approved At:</strong> <?php echo $company['approved_at'] ?: 'Not yet approved'; ?></li>
      </ul>
    </section>
  </div>
</body>
</html>
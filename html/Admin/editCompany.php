<?php
session_start();
require_once "db_aadminconnect.php"; 

$company_id = intval($_GET['id'] ?? 0);
if ($company_id <= 0) { die("Invalid company ID."); }

$stmt = $conn->prepare("SELECT * FROM company_registration WHERE company_id=? LIMIT 1");
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();
$company = $result->fetch_assoc();
$stmt->close();

if (!$company) { die("Company not found."); }

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_name = $_POST['company_name'];
    $industry = $_POST['industry'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $city_state = $_POST['city_state'];
    $status = $_POST['status'];

    $update = $conn->prepare("UPDATE company_registration 
                              SET company_name=?, industry=?, email=?, phone=?, city_state=?, status=? 
                              WHERE company_id=?");
    $update->bind_param("ssssssi", $company_name, $industry, $email, $phone, $city_state, $status, $company_id);
    $update->execute();
    $update->close();

    echo "<p style='color:green'>Company updated successfully!</p>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Company</title>
  <link rel="stylesheet" href="editCompany.css">
</head>
<body>
  <a href="AdminDashboard.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Back</a>

  <div class="edit-container">
    <h2>Edit Company: <?php echo htmlspecialchars($company['company_name']); ?></h2>
    <form method="post">
      <label>Company Name</label>
      <input type="text" name="company_name" value="<?php echo htmlspecialchars($company['company_name']); ?>" required>

      <label>Industry</label>
      <input type="text" name="industry" value="<?php echo htmlspecialchars($company['industry']); ?>" required>

      <label>Email</label>
      <input type="email" name="email" value="<?php echo htmlspecialchars($company['email']); ?>" required>

      <label>Phone</label>
      <input type="text" name="phone" value="<?php echo htmlspecialchars($company['phone']); ?>" required>

      <label>City/State</label>
      <input type="text" name="city_state" value="<?php echo htmlspecialchars($company['city_state']); ?>" required>

      <label>Status</label>
      <select name="status">
        <option value="pending" <?php if($company['status']=="pending") echo "selected"; ?>>Pending</option>
        <option value="verified" <?php if($company['status']=="verified") echo "selected"; ?>>Verified</option>
        <option value="approved" <?php if($company['status']=="approved") echo "selected"; ?>>Approved</option>
        <option value="rejected" <?php if($company['status']=="rejected") echo "selected"; ?>>Rejected</option>
      </select>

      <button type="submit" class="btn-save">Save Changes</button>
    </form>
  </div>
</body>
</html>
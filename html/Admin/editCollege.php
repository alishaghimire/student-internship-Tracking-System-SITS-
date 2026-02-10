<?php
session_start();
require_once "db_aadminconnect.php"; 

$college_id = intval($_GET['id'] ?? 0);
if ($college_id <= 0) { die("Invalid college ID."); }

$stmt = $conn->prepare("SELECT * FROM colleges WHERE college_id=? LIMIT 1");
$stmt->bind_param("i", $college_id);
$stmt->execute();
$result = $stmt->get_result();
$college = $result->fetch_assoc();
$stmt->close();

if (!$college) { die("College not found."); }

$errors = [];
$success = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Trim inputs
    $college_name = trim($_POST['college_name']);
    $college_type = trim($_POST['college_type']);
    $affiliated_university = trim($_POST['affiliated_university']);
    $email = trim($_POST['authorized_email']);
    $phone = trim($_POST['authorized_phone']);
    $city = trim($_POST['city']);
    $district = trim($_POST['district']);
    $province = trim($_POST['province']);
    $status = trim($_POST['status']);

    // Validation
    if (empty($college_name)) $errors[] = "College name is required.";
    if (!in_array($college_type, ['Public','Private','Autonomous'])) $errors[] = "Invalid college type.";
    if (empty($affiliated_university)) $errors[] = "Affiliated university is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email address.";
    if (!preg_match("/^[0-9]{7,15}$/", $phone)) $errors[] = "Phone must be 7–15 digits.";
    if (empty($city)) $errors[] = "City is required.";
    if (empty($district)) $errors[] = "District is required.";
    if (empty($province)) $errors[] = "Province is required.";
    if (!in_array($status, ['Pending','Approved','Rejected'])) $errors[] = "Invalid status.";

    // If no errors, update
    if (empty($errors)) {
        $update = $conn->prepare("UPDATE colleges 
            SET college_name=?, college_type=?, affiliated_university=?, authorized_email=?, authorized_phone=?, city=?, district=?, province=?, status=? 
            WHERE college_id=?");
        $update->bind_param("sssssssssi", $college_name, $college_type, $affiliated_university, $email, $phone, $city, $district, $province, $status, $college_id);
        if ($update->execute()) {
            $success = "College updated successfully!";
        } else {
            $errors[] = "Database update failed.";
        }
        $update->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit College</title>
  <link rel="stylesheet" href="editCollege.css">
</head>
<body>
  <a href="AdminDashboard.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Back</a>

  <div class="edit-container">
    <h2>Edit College: <?php echo htmlspecialchars($college['college_name']); ?></h2>

    <!-- Show errors -->
    <?php if (!empty($errors)): ?>
      <div class="error-box">
        <ul>
          <?php foreach ($errors as $err): ?>
            <li><?php echo htmlspecialchars($err); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <!-- Show success -->
    <?php if ($success): ?>
      <div class="success-box"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="post">
      <label>College Name</label>
      <input type="text" name="college_name" value="<?php echo htmlspecialchars($college['college_name']); ?>" required>

      <label>College Type</label>
      <select name="college_type" required>
        <option value="Public" <?php if($college['college_type']=="Public") echo "selected"; ?>>Public</option>
        <option value="Private" <?php if($college['college_type']=="Private") echo "selected"; ?>>Private</option>
        <option value="Autonomous" <?php if($college['college_type']=="Autonomous") echo "selected"; ?>>Autonomous</option>
      </select>

      <label>Affiliated University</label>
      <input type="text" name="affiliated_university" value="<?php echo htmlspecialchars($college['affiliated_university']); ?>" required>

      <label>Authorized Email</label>
      <input type="email" name="authorized_email" value="<?php echo htmlspecialchars($college['authorized_email']); ?>" required>

      <label>Authorized Phone</label>
      <input type="text" name="authorized_phone" value="<?php echo htmlspecialchars($college['authorized_phone']); ?>" required>

      <label>City</label>
      <input type="text" name="city" value="<?php echo htmlspecialchars($college['city']); ?>" required>

      <label>District</label>
      <input type="text" name="district" value="<?php echo htmlspecialchars($college['district']); ?>" required>

      <label>Province</label>
      <input type="text" name="province" value="<?php echo htmlspecialchars($college['province']); ?>" required>

      <label>Status</label>
      <select name="status" required>
        <option value="Pending" <?php if($college['status']=="Pending") echo "selected"; ?>>Pending</option>
        <option value="Approved" <?php if($college['status']=="Approved") echo "selected"; ?>>Approved</option>
        <option value="Rejected" <?php if($college['status']=="Rejected") echo "selected"; ?>>Rejected</option>
      </select>

      <button type="submit" class="btn-save">Save Changes</button>
    </form>
  </div>
</body>
</html>
<?php
require_once "db_aadminconnect.php"; // adjust to your DB connection file

// Fetch colleges
$sql = "SELECT college_id, college_name, college_type, affiliated_university, registration_number,
               accreditation_level, established_year, province, district, city, full_address,
               website, authorized_name, designation, authorized_email, authorized_phone,
               alternate_phone, status, created_at
        FROM colleges
        ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Registered Colleges</title>
  <link rel="stylesheet" href="colleges.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>

<section class="colleges-page">

  <!-- Header -->
  <div class="header-row">
    <div>
      <h2>Registered Colleges</h2>
      <p>Manage college partnerships</p>
    </div>
  </div>

  <!-- Table -->
  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>College</th>
          <th>Type</th>
          <th>Affiliated University</th>
          <th>Established</th>
          <th>Location</th>
          <th>Authorized Person</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td>
                <strong><?php echo htmlspecialchars($row['college_name']); ?></strong><br>
                <span class="sub">Reg#: <?php echo htmlspecialchars($row['registration_number']); ?></span>
              </td>
              <td><?php echo htmlspecialchars($row['college_type']); ?></td>
              <td><?php echo htmlspecialchars($row['affiliated_university']); ?></td>
              <td><?php echo htmlspecialchars($row['established_year']); ?></td>
              <td><?php echo htmlspecialchars($row['city'] . ", " . $row['district'] . ", " . $row['province']); ?></td>
              <td>
                <?php echo htmlspecialchars($row['authorized_name']); ?><br>
                <span class="sub"><?php echo htmlspecialchars($row['designation']); ?> • <?php echo htmlspecialchars($row['authorized_email']); ?></span>
              </td>
              <td>
                <span class="status <?php echo strtolower($row['status']); ?>">
                  <?php echo ucfirst($row['status']); ?>
                </span>
              </td>
              <td class="actions">
                <a href="viewCollege.php?id=<?php echo $row['college_id']; ?>"><i class="fa-regular fa-eye"></i></a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="8">No colleges registered yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

</body>
</html>
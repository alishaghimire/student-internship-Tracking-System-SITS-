<?php
require_once "db_aadminconnect.php"; // adjust to your DB connection file

// Fetch registered companies
$sql = "SELECT company_id, company_name, industry, email, phone, website, city_state, status, submitted_at, approved_at, num_employees 
        FROM company_registration 
        ORDER BY submitted_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Companies</title>

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="/SITS/Css/Admin/companies.css">
</head>
<body>

<section class="companies-page">

    <!-- Header Section -->
    <div class="header-row">
        <div>
            <h2>Registered Companies</h2>
            <p>Manage company partnerships</p>
        </div>

 
    </div>

    <!-- Company Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Industry</th>
                    <th>Contact</th>
                    <th>Employees</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="company-cell">
                                <?php
                                  // Avatar initials
                                  $parts = explode(" ", $row['company_name']);
                                  $initials = "";
                                  foreach ($parts as $p) { $initials .= strtoupper($p[0]); }
                                ?>
                                <div class="avatar"><?php echo $initials; ?></div>
                                <div>
                                    <strong><?php echo htmlspecialchars($row['company_name']); ?></strong>
                                    <p class="sub">Joined <?php echo date("M Y", strtotime($row['submitted_at'])); ?></p>
                                </div>
                            </td>

                            <td><?php echo htmlspecialchars($row['industry']); ?></td>

                            <td>
                                <?php echo htmlspecialchars($row['email']); ?><br>
                                <span class="sub"><?php echo htmlspecialchars($row['phone']); ?></span>
                            </td>

                            <td><?php echo htmlspecialchars($row['num_employees']); ?></td>

                            <td><?php echo htmlspecialchars($row['city_state']); ?></td>

                            <td>
                                <span class="status <?php echo strtolower($row['status']); ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>

                           <td class="actions">
    <a href="viewCompany.php?id=<?php echo $row['company_id']; ?>">
        <i class="fa-regular fa-eye"></i>
    </a>
</td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7">No companies registered yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

</body>
</html>
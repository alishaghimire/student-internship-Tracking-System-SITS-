<?php
require_once "studentdbconnection.php";

// Get filter and keyword from query string
$filter  = $_GET['filter']  ?? 'recent';
$keyword = trim($_GET['keyword'] ?? '');

// Base SQL with deadline filter
$sql = "
    SELECT 
        id,
        company_id,
        title,
        company_name,
        department,
        location,
        type,
        stipend,
        duration,
        deadline,
        description,
        created_at
    FROM postposition
    WHERE deadline >= CURDATE()
";

// Add keyword search if provided
if ($keyword !== '') {
    $safeKeyword = "%".$conn->real_escape_string($keyword)."%";
    $sql .= " AND (title LIKE '$safeKeyword'
                OR company_name LIKE '$safeKeyword'
                OR department LIKE '$safeKeyword'
                OR location LIKE '$safeKeyword'
                OR description LIKE '$safeKeyword')";
}

// Sorting / filter
switch ($filter) {
    case 'stipend':
        $sql .= " ORDER BY stipend DESC";
        break;
    case 'remote':
        $sql .= " AND location LIKE '%Remote%' ORDER BY created_at DESC";
        break;
    default: // recent
        $sql .= " ORDER BY created_at DESC";
        break;
}

$result = $conn->query($sql);
$internshipCount = $result->num_rows;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Browse Internships</title>
  <link rel="stylesheet" href="/project/css/Student/BrowseInternship.css" />
</head>

<body>

  <header>
    <h1>Browse Internships</h1>
    <p>Discover your next opportunity from 
      <span id="internship-count"><?php echo $internshipCount; ?></span> 
      available internships.
    </p>
  </header>

  <!-- Search + Filter Form -->
  <section class="search-filter">
    <form method="get" style="display:flex; gap:10px;">
      <input type="text" name="keyword" placeholder="Search by keyword..." 
             value="<?= htmlspecialchars($keyword) ?>" />
      <button type="submit" class="filter-btn">Search</button>
      <select name="filter" class="sort-dropdown" onchange="this.form.submit()">
        <option value="recent" <?= $filter=='recent'?'selected':'' ?>>Most Recent</option>
        <option value="stipend" <?= $filter=='stipend'?'selected':'' ?>>Highest Stipend</option>
        <option value="remote" <?= $filter=='remote'?'selected':'' ?>>Remote Only</option>
      </select>
    </form>
  </section>

  <!-- Internship List -->
  <section class="internship-list">

    <?php if ($internshipCount == 0): ?>
        <p>No internships available right now.</p>

    <?php else: ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card">
                <h2><?php echo htmlspecialchars($row['title']); ?></h2>

                <p class="company">
                    <?php echo htmlspecialchars($row['company_name']); ?> — <?php echo htmlspecialchars($row['location']); ?>
                </p>

                <p class="type"><?php echo ucfirst($row['type']); ?></p>

                <p class="stipend">
                    Stipend: Rs. <?php echo number_format($row['stipend']); ?>/month
                </p>

                <p class="description">
                    <?php echo nl2br(substr($row['description'], 0, 150)); ?>...
                </p>

                <div class="tags">
                    <span><?php echo htmlspecialchars($row['department']); ?></span>
                    <span><?php echo htmlspecialchars($row['duration']); ?></span>
                    <span>Deadline: <?php echo htmlspecialchars($row['deadline']); ?></span>
                </div>

                <div class="actions">
                    <button onclick="window.location.href='internshipDetails.php?id=<?php echo $row['id']; ?>'">
                        View Details
                    </button>

                    <button class="apply"
                        onclick="window.location.href='applypage1.php?post_id=<?= $row['id'] ?>&company_id=<?= $row['company_id'] ?>'">
                        Apply Now
                    </button>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>

  </section>

</body>
</html>
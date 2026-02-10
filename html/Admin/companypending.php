<?php include "db_aadminconnect.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Company Approvals</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="/project/css/Admin/companypending.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>

<div class="container">

    <!-- FILTER BAR -->
    <div class="filter-bar">
        <button class="filter-btn tab-btn" data-tab="registration">Registration Forms</button>
    </div>

    <!-- ============================================================
         TAB 1 — ALL COMPANY APPLICATIONS
    ============================================================ -->
    <div class="tab-content" id="all">

        <!-- ===================== COMPANY REGISTRATIONS ===================== -->
        <?php
        $sql2 = "SELECT * FROM company_registration";
        $result2 = $conn->query($sql2);

        while ($row = $result2->fetch_assoc()) {
            $statusClass = $row['status'] == 'approved' ? 'approved' : 'pending';
        ?>
        <div class="app-card company">
            <div class="app-label company-label <?php echo $statusClass; ?>">
                <i class="fa-solid fa-building"></i> Company Registration (<?php echo ucfirst($row['status']); ?>)
            </div>

            <h2><?php echo $row['company_name']; ?></h2>

            <div class="info-grid">
                <div><strong>Industry:</strong> <?php echo $row['industry']; ?></div>
                <div><strong>Website:</strong> <?php echo $row['website'] ?? 'N/A'; ?></div>
                <div><strong>Company Size:</strong> <?php echo $row['num_employees']; ?></div>
                <div><strong>Location:</strong> <?php echo $row['city_state']; ?></div>
                <div><strong>Contact Email:</strong> <?php echo $row['email']; ?></div>
                <div><strong>Contact Phone:</strong> <?php echo $row['phone']; ?></div>
                <div><strong>Submitted At:</strong> <?php echo $row['submitted_at']; ?></div>
            </div>

            <?php if ($row['status'] == 'pending') { ?>
            <div class="action-buttons">
                <button class="reject-btn" onclick="rejectCompany(<?php echo $row['company_id']; ?>)">
                    <i class="fa-solid fa-xmark"></i> Reject
                </button>

                <button class="approve-btn" onclick="approveCompany(<?php echo $row['company_id']; ?>)">
                    <i class="fa-solid fa-check"></i> Approve
                </button>
            </div>
            <?php } ?>
        </div>
        <?php } ?>
    </div>

    <!-- ============================================================
         TAB 2 — REGISTRATION FORMS ONLY
    ============================================================ -->
    <div class="tab-content" id="registration" style="display:none;">

        <?php
        $sql3 = "SELECT * FROM company_registration";
        $result3 = $conn->query($sql3);

        while ($row = $result3->fetch_assoc()) {
            $statusClass = $row['status'] == 'approved' ? 'approved' : 'pending';
        ?>
        <div class="app-card company">
            <div class="app-label company-label <?php echo $statusClass; ?>">
                <i class="fa-solid fa-building"></i> Company Registration (<?php echo ucfirst($row['status']); ?>)
            </div>

            <h2><?php echo $row['company_name']; ?></h2>

            <div class="info-grid">
                <div><strong>Industry:</strong> <?php echo $row['industry']; ?></div>
                <div><strong>Website:</strong> <?php echo $row['website']; ?></div>
                <div><strong>Company Size:</strong> <?php echo $row['num_employees']; ?></div>
                <div><strong>Location:</strong> <?php echo $row['city_state']; ?></div>
                <div><strong>Contact Email:</strong> <?php echo $row['email']; ?></div>
                <div><strong>Contact Phone:</strong> <?php echo $row['phone']; ?></div>
                <div><strong>Submitted At:</strong> <?php echo $row['submitted_at']; ?></div>
            </div>

            <?php if ($row['status'] == 'pending') { ?>
            <div class="action-buttons">
                <button class="reject-btn" onclick="rejectCompany(<?php echo $row['company_id']; ?>)">
                    <i class="fa-solid fa-xmark"></i> Reject
                </button>

                <button class="approve-btn" onclick="approveCompany(<?php echo $row['company_id']; ?>)">
                    <i class="fa-solid fa-check"></i> Approve
                </button>
            </div>
            <?php } ?>
        </div>
        <?php } ?>
    </div>

</div>

<!-- TAB SWITCHING SCRIPT -->
<script>
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        document.querySelectorAll('.tab-content').forEach(tab => tab.style.display = 'none');

        let tabName = this.getAttribute('data-tab');
        document.getElementById(tabName).style.display = 'block';
    });
});

// AJAX FUNCTIONS
function approveCompany(id) {
    $.post("approveregist_company.php", { company_id: id }, function(response) {
        if (response === "success") {
            alert("Company approved!");
            location.reload();
        }
    });
}

function rejectCompany(id) {
    $.post("rejectregi_company.php", { company_id: id }, function(response) {
        if (response === "success") {
            alert("Company rejected!");
            location.reload();
        }
    });
}
</script>

</body>
</html>
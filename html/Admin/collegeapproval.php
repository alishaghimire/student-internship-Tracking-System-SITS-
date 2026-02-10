<?php
require_once 'db_aadminconnect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>College Requests</title>
<style>
.college-card {
    width: 100%;
    max-width: 750px;
    background: #fff;
    padding: 22px;
    margin: 20px auto;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    font-family: "Poppins", sans-serif;
}
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.card-header h2 { font-size: 22px; font-weight: 600; margin: 0; }
.status { padding: 6px 14px; border-radius: 20px; font-size: 14px; font-weight: 500; }
.status.pending { background: #fff3cd; color: #b88600; }
.status.approved { background: #d4f8d4; color: #1e7e34; }
.status.rejected { background: #ffdddd; color: #c0392b; }
.request { font-size: 16px; margin: 12px 0 18px; color: #444; }
.details p { margin: 6px 0; font-size: 15px; color: #333; }
.actions { margin-top: 18px; display: flex; gap: 12px; }
.actions button {
    padding: 10px 18px;
    border: none;
    border-radius: 6px;
    font-size: 15px;
    cursor: pointer;
    font-weight: 500;
}
.reject { background: #ffdddd; color: #c0392b; }
.reject:hover { background: #ffcccc; }
.approve { background: #d4f8d4; color: #1e7e34; }
.approve:hover { background: #c2f1c2; }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $('.approve, .reject').click(function(e){
        e.preventDefault();
        var button = $(this);
        var id = button.data('id');
        var action = button.hasClass('approve') ? 'approve' : 'reject';

        $.post('updatestatuscollege.php', {id: id, action: action}, function(response){
            if(response.trim() === 'success'){
                var statusSpan = button.closest('.college-card').find('.status');
                if(action === 'approve') statusSpan.text('Approved').removeClass('pending rejected').addClass('approved');
                else statusSpan.text('Rejected').removeClass('pending approved').addClass('rejected');
                button.closest('.actions').hide();
            } else {
                alert('Failed to update status');
            }
        });
    });
});
</script>
</head>
<body>

<?php
$query = "SELECT college_id, college_name, college_type, affiliated_university, city, district, province, 
          authorized_name, authorized_email, authorized_phone, established_year, status 
          FROM colleges ORDER BY established_year DESC";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $statusClass = strtolower($row['status'] ?? 'pending'); 
        $statusText = $row['status'] ?? 'Pending';
        ?>
        <div class="college-card">
            <div class="card-header">
                <h2><?php echo htmlspecialchars($row['college_name']); ?></h2>
                <span class="status <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
            </div>

            <p class="request">Request for collaboration</p>

            <div class="details">
                <p><strong>Type:</strong> <?php echo htmlspecialchars($row['college_type']); ?></p>
                <p><strong>Affiliated University:</strong> <?php echo htmlspecialchars($row['affiliated_university']); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($row['city'] . ', ' . $row['district'] . ', ' . $row['province']); ?></p>
                <p><strong>Contact:</strong> <?php echo htmlspecialchars($row['authorized_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($row['authorized_email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($row['authorized_phone']); ?></p>
                <p><strong>Established:</strong> <?php echo htmlspecialchars($row['established_year']); ?></p>
            </div>

            <?php if($statusText === 'Pending'){ ?>
            <div class="actions">
                <button class="approve" data-id="<?php echo $row['college_id']; ?>">Approve</button>
                <button class="reject" data-id="<?php echo $row['college_id']; ?>">Reject</button>
            </div>
            <?php } ?>
        </div>
        <?php
    }
} else {
    echo "<p style='text-align:center; margin-top:20px;'>No colleges found.</p>";
}
?>

</body>
</html>

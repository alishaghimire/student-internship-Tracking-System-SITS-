<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .student-card {
    width: 100%;
    max-width: 700px;
    background: #fff;
    padding: 22px;
    margin: 20px auto;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    font-family: "Poppins", sans-serif;
}

.card-header {
    display: flex;
    align-items: center;
    gap: 12px;
}

.card-header h2 {
    font-size: 22px;
    font-weight: 600;
    margin: 0;
}

.tag {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.tag.student {
    background: #e3f2fd;
    color: #0d6efd;
}

.status {
    margin-left: auto;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
}

.status.pending {
    background: #fff3cd;
    color: #b88600;
}

.request {
    font-size: 16px;
    margin: 14px 0 18px;
    color: #444;
}

.details p {
    margin: 6px 0;
    font-size: 15px;
    color: #333;
}

.actions {
    margin-top: 20px;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

.actions button {
    padding: 10px 18px;
    border: none;
    border-radius: 6px;
    font-size: 15px;
    cursor: pointer;
    font-weight: 500;
}

.reject {
    background: #ffdddd;
    color: #c0392b;
}

.reject:hover {
    background: #ffcccc;
}

.approve {
    background: #d4f8d4;
    color: #1e7e34;
}

.approve:hover {
    background: #c2f1c2;
}
    </style>
</head>
<body>
    <div class="student-card">
    <div class="card-header">
        <h2>Emily Davis</h2>
        <span class="tag student">Student</span>
        <span class="status pending">Pending</span>
    </div>

    <p class="request">Application for Summer Internship Program</p>

    <div class="details">
        <p><strong>Major:</strong> Software Engineering</p>
        <p><strong>Year:</strong> Junior</p>
        <p><strong>GPA:</strong> 3.8</p>
        <p><strong>Submission Date:</strong> 12/12/2025</p>
    </div>

    <div class="actions">
        <button class="reject">Reject</button>
        <button class="approve">Approve</button>
    </div>
</div>
</body>
</html>
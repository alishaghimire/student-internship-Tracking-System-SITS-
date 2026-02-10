<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Details Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f6f9;
      padding: 40px;
      margin: 0;
    }

    .container {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      max-width: 1100px;
      margin: auto;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .title {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 22px;
      font-weight: bold;
      margin-bottom: 25px;
    }

    .title span {
      font-size: 26px;
      color: #6c63ff;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 45px;
      margin-bottom: 10px;
    }

    .full {
      grid-column: span 1;
    }

    label {
      font-size: 14px;
      font-weight: bold;
      margin-bottom: 6px;
      display: block;
      color: #555;
    }

    input {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
      outline: none;
    }

    input:focus {
      border-color: #6c63ff;
      box-shadow: 0 0 4px rgba(108, 99, 255, 0.4);
    }

    .btn-row {
      display: flex;
      justify-content: flex-end;
      gap: 12px;
      margin-top: 20px;
    }

    .btn {
      padding: 12px 22px;
      border-radius: 7px;
      border: none;
      font-size: 15px;
      cursor: pointer;
    }

    .save {
      background: #1ba94c;
      color: white;
    }

    .cancel {
      background: #3b3f46;
      color: white;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="title"><span>👤</span> Student Details</div>

    <div class="grid">
      <div>
        <label>Full Name</label>
        <input type="text" value="John Anderson" />
      </div>
      <div>
        <label>Email</label>
        <input type="email" value="john.anderson@college.edu" />
      </div>

      <div>
        <label>Phone</label>
        <input type="text" value="+1 (555) 123-4567" />
      </div>

      <div>
        <label>Date of Birth</label>
        <input type="date" value="1998-05-15" />
      </div>

      <div>
        <label>Enrollment Date</label>
        <input type="date" value="2024-01-09" />
      </div>

      <div class="full">
        <label>Address</label>
        <input type="text" value="123 Campus Drive, University City, CA 94305" />
      </div>
    <div class="btn-row">
      <a href= "Student.php"><button class="btn cancel">✖ Cancel</button>
      <a href= "Student.php"><button class="btn save">💾 Save</button>
    </div>
  </div>
</body>
</html>

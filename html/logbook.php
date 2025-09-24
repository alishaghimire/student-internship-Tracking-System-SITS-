<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>logbook</title>
    <link rel="stylesheet" href="/PROJECT/css/proj/logbook.css">
</head>
<body>
    <h2>Internship logbook</h2>
    <p>track your daily activity and progress</p>

    <div class="container">
        <div class="title">
            <h2>Logbook Entries</h2>
        </div>
        <div class="details">

       <button class="add-entry" id="addEntry">
  <img src="../pictures/pluse.png" alt="Add Icon">
  Add Entry
</button>

<table id="logbookTable">
  <thead>
    <tr>
      <th>Date</th>
      <th>Hours</th>
      <th>Supervisor</th>
      <th>Description</th>
      <th>Tasks</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>
        </div>
    </div>
<!-- Pop-up Form Container -->
<div class="popup-overlay" id="popupOverlay">
  <div class="popup-form">
    <form id="logbookForm">
      <h2>Add Logbook Entry</h2>

      <label for="date">Date</label>
      <input type="date" id="date" name="date" placeholder="dd/mm/yyyy" >

      <label for="hours">Hours Worked</label>
      <input type="number" id="hours" name="hours" >

      <label for="supervisor">Supervisor</label>
      <input type="text" id="supervisor" name="supervisor" placeholder="Supervisor name" >

      <label for="description">Description</label>
      <textarea id="description" name="description" placeholder="What did you work on today?" ></textarea>

      <label for="tasks">Tasks Completed</label>
      <textarea id="tasks" name="tasks" placeholder="List the tasks you completed..." ></textarea>

      <label for="reflections">Reflections</label>
      <textarea id="reflections" name="reflections" placeholder="What did you learn? Any challenges?" ></textarea>

      <div class="form-buttons">
        <button type="button" onclick="closeForm()">Cancel</button>
        <button type="submit">Save Entry</button>
      </div>
    </form>
  </div>
</div>
<script src="../javascript/logbook.js"></script>
</body>
</html>
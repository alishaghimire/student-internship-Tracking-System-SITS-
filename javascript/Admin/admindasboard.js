document.addEventListener("DOMContentLoaded", () => {
  const buttons = document.querySelectorAll(".menu-bar button");
  const panels = document.querySelectorAll(".contentPanel");

  buttons.forEach((btn, index) => {
    btn.addEventListener("click", () => {
      // Remove active class from all buttons
      buttons.forEach(b => b.classList.remove("active"));
      btn.classList.add("active");

      // Hide all panels
      panels.forEach(panel => panel.style.display = "none");

      // Show the selected panel
const panelIds = [
  "dashboardContent",   // Dashboard
  "approvalContent",    // Pending Approval
  "companiesContent",   // Companies
  "departmentsContent", // Departments
  "studentsContent",    // Students
  "reportsContent",     // Reports
  "ManageAdminContent", // Manage Admin
  "settingsContent"     // Settings
];      document.getElementById(panelIds[index]).style.display = "block";
    });
  });
});
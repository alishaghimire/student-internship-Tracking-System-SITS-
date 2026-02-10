document.addEventListener("DOMContentLoaded", () => {
  const pendingReviewContent = document.getElementById("approvalContent");

  // When "Review Posts" is clicked
  document.getElementById("reviewPostsBtn").addEventListener("click", () => {
    // Hide dashboard
    dashboardContent.style.display = "none";
    // Show pending review
    pendingReviewContent.style.display = "block";
  });
});
document.addEventListener("DOMContentLoaded", () => {
  const pendingReviewContent = document.getElementById("companiesContent");

  // When "Review Posts" is clicked
  document.getElementById("managecompantBtn").addEventListener("click", () => {
    // Hide dashboard
    dashboardContent.style.display = "none";
    // Show pending review
    pendingReviewContent.style.display = "block";
  });
});
document.addEventListener("DOMContentLoaded", () => {
  const pendingReviewContent = document.getElementById("reportsContent");

  // When "Review Posts" is clicked
  document.getElementById("viewreport").addEventListener("click", () => {
    // Hide dashboard
    dashboardContent.style.display = "none";
    // Show pending review
    pendingReviewContent.style.display = "block";
  });
});
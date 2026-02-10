document.addEventListener("DOMContentLoaded", () => {

    /* ---------------- MODAL HANDLING ---------------- */

    const modal = document.getElementById("postPositionModal");
    const modalBody = document.getElementById("modalBody");
    const closeBtn = document.getElementById("closeModalBtn");

    const postButtons = document.querySelectorAll(".post-btn");

    // Open modal
    postButtons.forEach(btn => {
        btn.addEventListener("click", async () => {
            const res = await fetch("postposition.php");
            const html = await res.text();
            modalBody.innerHTML = html;
            modal.style.display = "flex";
        });
    });

    // Close modal
    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // Click outside → close modal
    window.addEventListener("click", (e) => {
        if (e.target === modal) modal.style.display = "none";
    });

document.querySelectorAll(".nav-link").forEach(link => {
    link.addEventListener("click", (e) => e.preventDefault());
});

    /* ---------------- NAVIGATION ---------------- */

    function showSection(sectionId) {

        // Hide all page sections
        document.querySelectorAll("main > div").forEach(div => {
            div.style.display = "none";
        });

        // Show selected section
        document.getElementById(sectionId).style.display = "block";

        // Dashboard UI control
        const pageTitle = document.querySelector(".page-title");
        const dashboardTopbar = document.getElementById("dashboardTopbar");

        if (sectionId === "dashboardContent") {
            pageTitle.style.display = "block";
            dashboardTopbar.style.display = "flex";
        } else {
            pageTitle.style.display = "none";
            dashboardTopbar.style.display = "none";
        }
    }

    // Sidebar menu events
    document.getElementById("dashboardNav").onclick = () => showSection("dashboardContent");
    document.getElementById("applicationNav").onclick = () => showSection("applicationContent");
    document.getElementById("studentNav").onclick = () => showSection("studentContent");
    document.getElementById("interviewNav").onclick = () => showSection("interviewContent");
    document.getElementById("messageNav").onclick = () => showSection("messageContent");
    document.getElementById("reportNav").onclick = () => showSection("reportContent");
    document.getElementById("internshipNav").onclick = () => showSection("internshipContent");
    document.getElementById("settingsNav").onclick = () => showSection("settingsContent");

});

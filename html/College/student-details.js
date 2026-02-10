document.querySelector(".logout-btn").addEventListener("click", () => {
    alert("Logged out!");
    window.location.href = "login.php";
});

document.querySelector(".edit-btn").addEventListener("click", () => {
    alert("Editing !");
    window.location.href = "editing.php"
});

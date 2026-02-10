document.getElementById("loginForm").addEventListener("submit", function (e) {
    e.preventDefault();

    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value.trim();

    if (email === "" || password === "") {
        alert("Please fill out all fields.");
        return;
    }

    // Demo successful login
    alert("Login successful!");
    window.location.href = "collage.php";
});

function logout() {
    alert("You have been logged out!");
    window.location.href = "login.php";
}

function openPage(page) {
    window.location.href = page;
}

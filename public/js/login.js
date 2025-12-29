document.getElementById("loginForm").addEventListener("submit", function (e) {
    let hasError = false;

    document
        .querySelectorAll(".error-message")
        .forEach((el) => (el.textContent = ""));

    const email = document.getElementById("email");
    const password = document.getElementById("password");

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email.value.trim()) {
        document.getElementById("emailError").textContent =
            "The email field is required.";
        hasError = true;
    } else if (!emailPattern.test(email.value)) {
        document.getElementById("emailError").textContent =
            "Please enter a valid email address.";
        hasError = true;
    }

    if (!password.value) {
        document.getElementById("passwordError").textContent =
            "The password field is required.";
        hasError = true;
    } else if (password.value.length < 8) {
        document.getElementById("passwordError").textContent =
            "The password must be at least 8 characters.";
        hasError = true;
    }

    if (hasError) {
        e.preventDefault();
    }
});

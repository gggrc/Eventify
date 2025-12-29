document
    .getElementById("registerForm")
    .addEventListener("submit", function (e) {
        let hasError = false;

        document
            .querySelectorAll(".error-message")
            .forEach((el) => (el.textContent = ""));

        const name = document.getElementById("name");
        const email = document.getElementById("email");
        const password = document.getElementById("password");
        const confirmation = document.getElementById("password_confirmation");

        if (name.value.trim() === "") {
            document.getElementById("nameError").textContent =
                "The name field is required.";
            hasError = true;
        }

        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email.value)) {
            document.getElementById("emailError").textContent =
                "The email must be a valid email address.";
            hasError = true;
        }

        if (password.value.length < 8) {
            document.getElementById("passwordError").textContent =
                "The password must be at least 8 characters.";
            hasError = true;
        }

        if (password.value !== confirmation.value) {
            document.getElementById("confirmationError").textContent =
                "The password confirmation does not match.";
            hasError = true;
        }

        if (hasError) {
            e.preventDefault();
        }
    });

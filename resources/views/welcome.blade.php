<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventify</title>
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,900;1,900&display=swap" rel="stylesheet">
</head>
<body>


    <header class="navbar">
        <div class="logo">Eventify</div>
        <div class="nav-actions">
            <a href="{{ route('login') }}" class="login-link">Log in</a>
            <a href="{{ route('register') }}" class="btn-register">Register</a>
        </div>
    </header>

    <main class="hero">
        <p class="hero-eyebrow">MANAGE YOUR EVENTS BETTER</p>
        <h1 class="hero-title">
            Organize Your Events<br>
            <span>Easily.</span>
        </h1>
        <p class="hero-description">
            Manage tasks, collaborate with your team, and track progress all in one
            place with <strong class="brand">Eventify</strong>.
        </p>
    </main>

    <footer class="footer">
        &copy; 2025 Eventify. All rights reserved.
    </footer>

</body>
</html>

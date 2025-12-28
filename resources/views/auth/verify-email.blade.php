<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Email</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
</head>
<body>

<div class="page">
    <div class="overlay"></div>

    <div class="header fade-up">
        <h1>Verify your email</h1>
        <p>Before getting started, please verify your email address.</p>
    </div>

    <div class="card zoom-in">

        @if (session('status') == 'verification-link-sent')
            <div class="status">
                A new verification link has been sent to your email address.
            </div>
        @endif

        <div class="info-text mb-4">
            Thanks for signing up! If you didn’t receive the email, you can request another one.
        </div>

        <div class="flex-buttons">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-primary">Resend Verification Email</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">Log Out</button>
            </form>
        </div>

    </div>
</div>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <style>
        .error-message {
            color: #ff4d4d;
            font-size: 0.8rem;
            margin-top: 5px;
            display: block;
            font-weight: 500;
        }
        .hidden { display: none; }
    </style>
</head>
<body>

<div class="page">
    <div class="overlay"></div>
    <div class="header fade-up">
        <h1>Create your account</h1>
        <p>Join us and start your journey</p>
    </div>

    <div class="card zoom-in">
        <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
            @csrf

            <div class="form-group">
                <label for="name">Full name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="John Doe">
                <span id="nameError" class="error-message">
                    @error('name') {{ $message }} @enderror
                </span>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="mail@gmail.com">
                <span id="emailError" class="error-message">
                    @error('email') {{ $message }} @enderror
                </span>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input id="password" type="password" name="password" placeholder="••••••••">
                </div>
                <span id="passwordError" class="error-message">
                    @error('password') {{ $message }} @enderror
                </span>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" placeholder="••••••••">
                <span id="confirmationError" class="error-message"></span>
            </div>

            <button type="submit" class="btn-primary">Create account</button>

            <p class="footer-text">
                Already have an account? <a href="{{ route('login') }}">Sign in</a>
            </p>
        </form>
    </div>
</div>

<script src="{{ asset('js/dashboard/dragdrop.js') }}"></script>

</body>
</html>
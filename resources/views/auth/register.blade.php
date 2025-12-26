<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,900;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/register.css">
    <link rel="stylesheet" href="css/register.css">
</head>
<body>

<div class="page">
    <div class="overlay"></div>

    <div class="header fade-up">
        <h1>Create your account</h1>
        <p>Join us and start your journey</p>
    </div>

    <div class="card zoom-in">

        @if (session('status'))
            <div class="status">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="name">Full name</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="John Doe"
                    required
                    autofocus
                >
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="mail@gmail.com"
                    required
                >
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        required
                    >
                    <button type="button"
                            class="toggle-password"
                            onclick="togglePassword('password')"
                            aria-label="Toggle password">
                        <svg id="password-eye-open" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5
                                    c4.478 0 8.268 2.943 9.542 7
                                    -1.274 4.057-5.064 7-9.542 7
                                    -4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>

                        <svg id="password-eye-closed" class="hidden"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3l18 18"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.5 10.5a3 3 0 004.243 4.243"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5
                                    c4.478 0 8.268 2.943 9.542 7
                                    a9.97 9.97 0 01-1.249 2.592"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm password</label>
                <div class="password-wrapper">
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        placeholder="••••••••"
                        required
                    >
                    <button type="button"
                            class="toggle-password"
                            onclick="togglePassword('password_confirmation')"
                            aria-label="Toggle confirm password">
                        <svg id="confirm-eye-open" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5
                                    c4.478 0 8.268 2.943 9.542 7
                                    -1.274 4.057-5.064 7-9.542 7
                                    -4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>

                        <svg id="confirm-eye-closed" class="hidden"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3l18 18"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.5 10.5a3 3 0 004.243 4.243"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5
                                    c4.478 0 8.268 2.943 9.542 7
                                    a9.97 9.97 0 01-1.249 2.592"/>
                        </svg>
                    </button>
                </div>
                @error('password_confirmation')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-primary">Create account</button>

            <p class="footer-text">
                Already have an account? <a href="{{ route('login') }}">Sign in</a>
            </p>
        </form>

    </div>
</div>

<script>
function togglePassword(fieldId) {
    const input = document.getElementById(fieldId);
    const eyeOpen = document.getElementById(fieldId === 'password' ? 'password-eye-open' : 'confirm-eye-open');
    const eyeClosed = document.getElementById(fieldId === 'password' ? 'password-eye-closed' : 'confirm-eye-closed');

    if (input.type === 'password') {
        input.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
    } else {
        input.type = 'password';
        eyeClosed.classList.add('hidden');
        eyeOpen.classList.remove('hidden');
    }
}
</script>

</body>
</html>

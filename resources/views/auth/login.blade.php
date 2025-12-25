<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log in</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,900;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

<div class="page">
    <div class="overlay"></div>

    <div class="header fade-up">
        <h1>Let's get started now!</h1>
        <p>Or create an account if not registered yet</p>
    </div>

    <div class="card zoom-in">

        @if (session('status'))
            <div class="status">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="mail@gmail.com"
                    required
                    autofocus
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
                            onclick="togglePassword()"
                            aria-label="Toggle password">
                            
                        <svg id="eye-open" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5
                                    c4.478 0 8.268 2.943 9.542 7
                                    -1.274 4.057-5.064 7-9.542 7
                                    -4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>

                        <svg id="eye-closed" class="hidden"
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
            </div>


            <div class="form-row">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="link">
                        Forgot password?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn-primary">
                Sign in
            </button>

            <p class="footer-text">
                Don't have an account?
                <a href="{{ route('register') }}">Sign up</a>
            </p>

        </form>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const eyeOpen = document.getElementById('eye-open');
    const eyeClosed = document.getElementById('eye-closed');

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

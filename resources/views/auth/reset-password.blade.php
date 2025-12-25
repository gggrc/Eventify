<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/resetpass.css') }}">
</head>
<body>

<div class="page">
    <div class="overlay"></div>

    <div class="header fade-up">
        <h1>Reset your password</h1>
        <p>Create a new password for your account</p>
    </div>

    <div class="card zoom-in">

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            {{-- Email --}}
            <div class="form-group">
                <label for="email">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', $request->email) }}"
                    placeholder="mail@mail.com"
                    required
                    autofocus
                >
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Password --}}
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
                            onclick="togglePassword('password', 'eye-open', 'eye-closed')"
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
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
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
                            onclick="togglePassword('password_confirmation', 'eye-open2', 'eye-closed2')"
                            aria-label="Toggle password">

                        <svg id="eye-open2" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5
                                     c4.478 0 8.268 2.943 9.542 7
                                     -1.274 4.057-5.064 7-9.542 7
                                     -4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>

                        <svg id="eye-closed2" class="hidden"
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

            <button type="submit" class="btn-primary">Reset Password</button>
        </form>

    </div>
</div>

<script>
function togglePassword(inputId, eyeOpenId, eyeClosedId) {
    const input = document.getElementById(inputId);
    const eyeOpen = document.getElementById(eyeOpenId);
    const eyeClosed = document.getElementById(eyeClosedId);

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

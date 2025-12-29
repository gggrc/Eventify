<x-guest-layout>
    <x-slot name="title">Register</x-slot>
    <x-slot name="headerTitle">Create Account</x-slot>
    <x-slot name="headerSubTitle">Join us and start your journey today</x-slot>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label for="name">Full Name</label>
            <input id="name" type="text" name="name" placeholder="John Doe" required>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input id="email" type="email" name="email" placeholder="example@mail.com" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="password-wrapper">
                <input id="password" type="password" name="password" placeholder="••••••••" required>
                <button type="button" class="toggle-password" onclick="togglePassword('password', 'eye-r-o', 'eye-r-c')">
                    <svg id="eye-r-o" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg id="eye-r-c" class="hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18M10.5 10.5a3 3 0 004.243 4.243M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-1.249 2.592"/></svg>
                </button>
            </div>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <div class="password-wrapper">
                <input id="password_confirmation" type="password" name="password_confirmation" placeholder="••••••••" required>
                <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation', 'eye-rc-o', 'eye-rc-c')">
                    <svg id="eye-rc-o" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg id="eye-rc-c" class="hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18M10.5 10.5a3 3 0 004.243 4.243M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-1.249 2.592"/></svg>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-primary">Create Account</button>
        <p class="footer-text">Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
    </form>
</x-guest-layout>
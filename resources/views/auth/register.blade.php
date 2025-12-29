<x-guest-layout>
    <x-slot name="title">Register</x-slot>
    <x-slot name="headerTitle">Create Account</x-slot>
    <x-slot name="headerSubTitle">Join us and start your journey today</x-slot>

    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf
        
        <div class="form-group">
            <label for="name">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="John Doe">
            <span id="nameError" class="error-message" style="color: red; font-size: 0.8rem;">{{ $errors->first('name') }}</span>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="example@mail.com">
            <span id="emailError" class="error-message" style="color: red; font-size: 0.8rem;">{{ $errors->first('email') }}</span>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="password-wrapper">
                <input id="password" type="password" name="password" placeholder="••••••••">
                <button type="button" class="toggle-password" onclick="togglePassword('password', 'eye-r-o', 'eye-r-c')">
                    </button>
            </div>
            <span id="passwordError" class="error-message" style="color: red; font-size: 0.8rem;">{{ $errors->first('password') }}</span>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <div class="password-wrapper">
                <input id="password_confirmation" type="password" name="password_confirmation" placeholder="••••••••">
                <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation', 'eye-rc-o', 'eye-rc-c')">
                    </button>
            </div>
            <span id="confirmationError" class="error-message" style="color: red; font-size: 0.8rem;"></span>
        </div>

        <button type="submit" class="btn-primary">Create Account</button>
        <p class="footer-text">Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
    </form>

    <script src="{{ asset('js/register.js') }}"></script>
</x-guest-layout>
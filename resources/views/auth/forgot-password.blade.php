<x-guest-layout>
    <x-slot name="title">Forgot Password</x-slot>
    <x-slot name="headerTitle">Reset Password</x-slot>
    <x-slot name="headerSubTitle">No problem. Just let us know your email address and we will email you a password reset link.</x-slot>

    <div class="mb-4">
        <x-auth-session-status :status="session('status')" />
    </div>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group">
            <label for="email">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="example@mail.com" required autofocus>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div style="margin-top: 24px;">
            <button type="submit" class="btn-primary">
                Email Password Reset Link
            </button>
        </div>

        <p class="footer-text">
            Remember your password? <a href="{{ route('login') }}">Back to Sign In</a>
        </p>
    </form>
</x-guest-layout>
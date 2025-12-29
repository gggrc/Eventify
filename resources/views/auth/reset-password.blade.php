<x-guest-layout>
    <x-slot name="title">Reset Password</x-slot>
    <x-slot name="headerTitle">New Password</x-slot>
    <x-slot name="headerSubTitle">Please enter your email and choose a strong new password.</x-slot>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="form-group">
            <label for="email">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" placeholder="example@mail.com" required autofocus autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="form-group">
            <label for="password">New Password</label>
            <div class="password-wrapper">
                <input id="password" type="password" name="password" placeholder="••••••••" required autocomplete="new-password">
                <button type="button" class="toggle-password" onclick="togglePassword('password', 'eye-p-o', 'eye-p-c')">
                    <svg id="eye-p-o" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg id="eye-p-c" class="hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18M10.5 10.5a3 3 0 004.243 4.243M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-1.249 2.592"/></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm New Password</label>
            <div class="password-wrapper">
                <input id="password_confirmation" type="password" name="password_confirmation" placeholder="••••••••" required autocomplete="new-password">
                <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation', 'eye-pc-o', 'eye-pc-c')">
                    <svg id="eye-pc-o" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg id="eye-pc-c" class="hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18M10.5 10.5a3 3 0 004.243 4.243M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-1.249 2.592"/></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div style="margin-top: 32px;">
            <button type="submit" class="btn-primary">
                Reset Password
            </button>
        </div>
    </form>
</x-guest-layout>
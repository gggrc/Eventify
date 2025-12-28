<section>
    <header class="section-header">
        <h2>Update Password</h2>
        <p>Ensure your account is using a long, random password to stay secure.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input id="current_password" name="current_password" type="password">
            @error('current_password') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="password">New Password</label>
            <input id="password" name="password" type="password">
            @error('password') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password">
        </div>

        <div style="display: flex; align-items: center; gap: 15px;">
            <button type="submit" class="btn-primary btn-small" style="width: auto; padding: 10px 25px;">Update Password</button>
            @if (session('status') === 'password-updated')
                <span class="status" style="color: #16a34a; font-size: 13px; margin: 0;">Password updated.</span>
            @endif
        </div>
    </form>
</section>


<section>
            <a href="{{ route('dashboard') }}" class="btn-back-inline">
                &larr;  Back to Login
            </a>
    <header>
        <h2 class="section-title">{{ __('Profile Information') }}</h2>
        <p class="section-description">{{ __("Update your account's profile information and email address.") }}</p>
    </header>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="form-group">
            <label for="name">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @error('name') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="email">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @error('email') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div style="display: flex; align-items: center; gap: 15px;">
            <button type="submit" class="btn-primary" style="width: auto; padding: 10px 25px;">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p style="color: #16a34a; font-size: 13px; margin: 0;">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
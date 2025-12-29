<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Eventify</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cardlist.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ search: '' }" class="bg-gray-50 text-gray-900">
    <header class="dashboard-header">
        <div class="logo">Eventify</div>
        <div class="user-menu">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="flex items-center gap-2">
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" alt="avatar" class="header-avatar">
                    </button>
                </x-slot>
                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </header>

     <header class="topbar">
        <div class="topbar-left">
            <h2>{{ __('Account Settings') }}</h2>
        </div>
        <div class="topbar-right">
            <a href="{{ route('dashboard') }}" class="btn-back">Back to Dashboard</a>
        </div>
    </header>

    <div class="profile-container">
        <div class="card">
            <div class="card-section">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="card">
            <div class="card-section">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="card danger-area">
            <div class="card-section">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</body>
</html>
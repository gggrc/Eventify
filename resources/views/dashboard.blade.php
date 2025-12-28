<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Eventify</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
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
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            Log Out
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </header>

    <main class="dashboard-main">
        <div class="dashboard-title-section">
            <div>
                <h1>My Projects</h1>
            </div>

            <form action="{{ route('boards.store') }}" method="POST" class="create-project-form">
                @csrf
                <input type="text" name="title" placeholder="Project Name..." required class="project-input">
                <button type="submit" class="btn-create-project">
                    <i class="fa-solid fa-plus"></i> <span>New Project</span>
                </button>
            </form>
        </div>

        <div class="project-grid">
            @forelse($boards as $board)
                <a href="{{ route('boards.show', $board) }}" class="project-card-link">
                    <div class="project-card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $board->title }}</h3>
                            <span class="status-badge">Active</span>
                        </div>

                        <p class="card-description">Manage this project.</p>

                        <div class="card-footer">
                            <div class="owner-info">
                                <div class="owner-avatar">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                                <span class="owner-label">Personal Project</span>
                            </div>
                            <svg class="card-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>
            @empty
                <div class="empty-state">
                    <p>No projects found. Create one to get started!</p>
                </div>
            @endforelse
        </div>
    </main>
</body>
</html>

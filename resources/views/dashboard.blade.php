<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Eventify</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
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

    <main class="dashboard-main">
        <div class="dashboard-title-section">
            <h1>My Projects</h1>
            <div class="create-project-form">
                <input type="text" x-model="search" placeholder="Search boards..." class="project-input">
                <button type="button" x-on:click.prevent="$dispatch('open-modal', 'create-project-modal')" class="btn-create-project">
                    <i class="fa-solid fa-plus"></i> <span>New Project</span>
                </button>
            </div>
        </div>

        <div class="mt-8 mb-4">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                Active Projects
            </h2>
        </div>
        <div id="project-grid" class="project-grid">
            @forelse($activeBoards as $board)
                <div class="project-card-wrapper relative cursor-move" 
                     data-id="{{ $board->id }}"
                     x-show="search === '' || '{{ strtolower($board->title) }}'.includes(search.toLowerCase())"
                     x-data="{ menuOpen: false }">
                    
                    <div class="project-card">
                        <div class="card-header flex justify-between items-start">
                            <h3 class="card-title">{{ $board->title }}</h3>
                            
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 text-xs font-bold rounded-md bg-green-100 text-green-600">Active</span>

                                <div class="relative">
                                    <button @click.prevent="menuOpen = !menuOpen" class="text-gray-400 hover:text-gray-600 p-1">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>

                                    <div x-show="menuOpen" @click.away="menuOpen = false" x-cloak
                                        class="absolute right-0 mt-2 w-32 bg-white border border-gray-200 rounded-md shadow-lg py-1 z-50">
                                        <button @click.prevent="$dispatch('open-modal', 'edit-project-{{ $board->id }}')" 
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Edit
                                        </button>
                                        <form method="POST" action="{{ route('boards.destroy', $board) }}" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('boards.show', $board) }}" class="block mt-4">
                            <p class="card-description">Manage this project.</p>
                            <div class="card-footer mt-4">
                                <div class="owner-info">
                                    <div class="owner-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                                    <span class="owner-label">Personal Project</span>
                                </div>
                            </div>
                        </a>
                    </div>

                    <x-modal name="edit-project-{{ $board->id }}" focusable>
                        <div class="p-6 pb-8">
                            <h2 class="text-lg font-bold text-gray-900 mb-6">Edit Project</h2>
                            <form method="POST" action="{{ route('boards.update', $board) }}">
                                @csrf
                                @method('PUT')
                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Project Name</label>
                                    <input type="text" name="title" value="{{ $board->title }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg outline-none" required>
                                </div>
                                <div class="mb-8">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white outline-none">
                                        <option value="active" {{ $board->status === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $board->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="flex justify-end items-center gap-3">
                                   <button type="button" x-on:click="$dispatch('close')" class="btn-cancel">Cancel</button>
                                    <button type="submit" class="btn-submit-blue">Save</button>
                                </div>
                            </form>
                        </div>
                    </x-modal>
                </div>
            @empty
                <div class="empty-state col-span-full">
                    <p>No active projects found.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-20 mb-4  pt-8">
            <h2 class="text-xl font-bold text-gray-500 flex items-center gap-2">
                Inactive Projects
            </h2>
        </div>
        <div id="inactive-project-grid" class="project-grid">
            @forelse($inactiveBoards as $board)
                <div class="project-card-wrapper relative cursor-move" 
                     data-id="{{ $board->id }}"
                     x-show="search === '' || '{{ strtolower($board->title) }}'.includes(search.toLowerCase())"
                     x-data="{ menuOpen: false }">
                    
                    <div class="project-card bg-gray-100 opacity-75 border-dashed border-2 border-gray-300">
                        <div class="card-header flex justify-between items-start">
                            <h3 class="card-title text-gray-500">{{ $board->title }}</h3>
                            
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 text-xs font-bold rounded-md bg-red-100 text-red-600">Inactive</span>

                                <div class="relative">
                                    <button @click.prevent="menuOpen = !menuOpen" class="text-gray-400 hover:text-gray-600 p-1">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>

                                    <div x-show="menuOpen" @click.away="menuOpen = false" x-cloak
                                        class="absolute right-0 mt-2 w-32 bg-white border border-gray-200 rounded-md shadow-lg py-1 z-50">
                                        <button @click.prevent="$dispatch('open-modal', 'edit-project-{{ $board->id }}')" 
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Edit
                                        </button>
                                        <form method="POST" action="{{ route('boards.destroy', $board) }}" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <p class="card-description italic text-gray-400 text-sm">Project is currently disabled.</p>
                        </div>
                    </div>

                    <x-modal name="edit-project-{{ $board->id }}" focusable>
                        <div class="p-6 pb-8">
                            <h2 class="text-lg font-bold text-gray-900 mb-6">Edit Project</h2>
                            <form method="POST" action="{{ route('boards.update', $board) }}">
                                @csrf
                                @method('PUT')
                                <div class="mb-5">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Project Name</label>
                                    <input type="text" name="title" value="{{ $board->title }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg outline-none" required>
                                </div>
                                <div class="mb-8">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white outline-none">
                                        <option value="active" {{ $board->status === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $board->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="flex justify-end items-center gap-3">
                                   <button type="button" x-on:click="$dispatch('close')" class="btn-cancel">Cancel</button>
                                    <button type="submit" class="btn-submit-blue">Save</button>
                                </div>
                            </form>
                        </div>
                    </x-modal>
                </div>
            @empty
                <div class="empty-state col-span-full border-dashed border-2 border-gray-200 py-4">
                    <p class="text-gray-400">No inactive projects.</p>
                </div>
            @endforelse
        </div>
    </main>

    <x-modal name="create-project-modal" focusable>
        <div class="p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-6">New Project</h2>
            <form id="create-project-form-ajax">
                @csrf
                <div class="mb-6">
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Project Name</label>
                    <input id="title" name="title" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg outline-none" required>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close')" class="btn-cancel">Cancel</button>
                    <button type="submit" class="btn-submit-blue">Create Project</button>
                </div>
            </form>
        </div>
    </x-modal>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle create project via AJAX
            const createForm = document.getElementById('create-project-form-ajax');
            if (createForm) {
                createForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    
                    try {
                        const response = await fetch("{{ route('boards.store') }}", {
                            method: "POST",
                            headers: {
                                "X-Requested-With": "XMLHttpRequest"
                            },
                            body: formData
                        });
                        
                        const result = await response.json();
                        if (result.success) {
                            // Karena ini project baru, user dialihkan ke halaman project tersebut
                            window.location.href = result.redirect_url;
                        }
                    } catch (error) {
                        console.error('Error creating project:', error);
                    }
                });
            }

            // Fungsi untuk mengirim urutan baru ke server
            const updateOrder = (gridElement) => {
                let order = [];
                gridElement.querySelectorAll('.project-card-wrapper').forEach((el, index) => {
                    order.push({
                        id: el.getAttribute('data-id'),
                        position: index
                    });
                });

                fetch("{{ route('boards.reorder') }}", {
                    method: "PATCH",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ order: order })
                });
            };

            // Inisialisasi Sortable untuk Active Projects
            const activeGrid = document.getElementById('project-grid');
            if (activeGrid) {
                new Sortable(activeGrid, {
                    animation: 150,
                    ghostClass: 'opacity-50',
                    onEnd: () => updateOrder(activeGrid)
                });
            }

            // Inisialisasi Sortable untuk Inactive Projects
            const inactiveGrid = document.getElementById('inactive-project-grid');
            if (inactiveGrid) {
                new Sortable(inactiveGrid, {
                    animation: 150,
                    ghostClass: 'opacity-50',
                    onEnd: () => updateOrder(inactiveGrid)
                });
            }
        });
    </script>
</body>
</html>
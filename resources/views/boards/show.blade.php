<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $board->title }} - Eventify</title>
    <link rel="stylesheet" href="{{ asset('css/cardlist.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .dragging { opacity: 0.5; cursor: grabbing; }
        .dragging-column { opacity: 0.4; border: 2px dashed #ccc; }
        .cards-area { min-height: 50px; }
        .add-task-form, .add-list-form { display: none; margin-top: 10px; }
        .add-task-input { width: 100%; border: 1px solid #ddd; padding: 8px; border-radius: 5px; outline: none; }
        .kanban-column { cursor: grab; }
        
        .app-wrapper {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        .dashboard-header {
            background: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <div class="app-wrapper">
        <header class="dashboard-header">
            <div class="logo">Eventify</div>

            <div class="user-menu">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 border-none bg-transparent cursor-pointer">
                            <div class="user-name">{{ Auth::user()->name }}</div>
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" alt="avatar" class="header-avatar" style="width: 32px; height: 32px; border-radius: 50%;">
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </header>

        <header class="topbar">
            <div class="topbar-left">
                <h2>{{ $board->title }}</h2>
            </div>
            <div class="topbar-right">
                <a href="{{ route('dashboard') }}" class="btn-back">Back to Dashboard</a>
            </div>
        </header>

        <main class="kanban-board" id="kanban-container">
            @php $colors = ['purple', 'blue', 'orange', 'red']; @endphp

            @foreach($board->taskLists as $index => $list)
                <section class="kanban-column {{ $colors[$index % 4] }} column-draggable" 
                         draggable="true" 
                         data-list-id="{{ $list->id }}">
                    <div class="column-header">
                        <h3>{{ $list->title }} <span>{{ $list->cards->count() }}</span></h3>
                        <i class="fa-solid fa-plus" onclick="toggleForm('task-form-{{ $list->id }}')"></i>
                    </div>

                    <div class="cards-area card-list" id="list-{{ $list->id }}">
                        @foreach($list->cards as $card)
                            <div class="card" draggable="true" data-card-id="{{ $card->id }}">
                                <p class="title">{{ $card->title }}</p>
                                <div class="meta">
                                    <span>{{ $card->created_at->format('D H:i') }}</span>
                                    <span class="badge medium">Task</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button class="add-task" onclick="toggleForm('task-form-{{ $list->id }}')">+ Add new task</button>
                    <div id="task-form-{{ $list->id }}" class="add-task-form">
                        <form action="{{ route('cards.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="task_list_id" value="{{ $list->id }}">
                            <input type="text" name="title" class="add-task-input" placeholder="Enter task..." required>
                        </form>
                    </div>
                </section>
            @endforeach

            <div class="add-list-section">
                <span onclick="toggleForm('list-form-main')"><i class="fa-solid fa-plus"></i> Add another list</span>
                <div id="list-form-main" class="add-list-form">
                    <form action="{{ route('lists.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="board_id" value="{{ $board->id }}">
                        <input type="text" name="title" class="add-task-input" placeholder="Enter list name..." required>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleForm(id) {
            const form = document.getElementById(id);
            form.style.display = form.style.display === 'block' ? 'none' : 'block';
            if(form.style.display === 'block') form.querySelector('input').focus();
        }

        const cards = document.querySelectorAll('.card');
        const lists = document.querySelectorAll('.cards-area');

        cards.forEach(card => {
            card.addEventListener('dragstart', (e) => {
                e.stopPropagation();
                card.classList.add('dragging');
            });
            card.addEventListener('dragend', () => card.classList.remove('dragging'));
        });

        lists.forEach(list => {
            list.addEventListener('dragover', e => {
                e.preventDefault();
                const draggingCard = document.querySelector('.dragging');
                if(draggingCard) list.appendChild(draggingCard);
            });
            list.addEventListener('drop', async () => {
                const draggingCard = document.querySelector('.dragging');
                if(!draggingCard) return;
                const cardId = draggingCard.dataset.cardId;
                const newListId = list.closest('.kanban-column').dataset.listId;
                const position = Array.from(list.querySelectorAll('.card')).indexOf(draggingCard);
                await fetch(`/cards/${cardId}/move`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ list_id: newListId, position: position })
                });
            });
        });

        const columns = document.querySelectorAll('.column-draggable');
        const container = document.getElementById('kanban-container');

        columns.forEach(col => {
            col.addEventListener('dragstart', () => col.classList.add('dragging-column'));
            col.addEventListener('dragend', async () => {
                col.classList.remove('dragging-column');
                const updatedOrder = Array.from(document.querySelectorAll('.column-draggable')).map((c, idx) => ({ id: c.dataset.listId, position: idx }));
                await fetch(`/lists/reorder`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ order: updatedOrder })
                });
            });
        });

        container.addEventListener('dragover', e => {
            e.preventDefault();
            const draggingCol = document.querySelector('.dragging-column');
            if(!draggingCol) return;
            const afterElement = getDragAfterElement(container, e.clientX);
            if(afterElement == null) {
                container.insertBefore(draggingCol, document.querySelector('.add-list-section'));
            } else {
                container.insertBefore(draggingCol, afterElement);
            }
        });

        function getDragAfterElement(container, x) {
            const draggables = [...container.querySelectorAll('.column-draggable:not(.dragging-column)')];
            return draggables.reduce((closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = x - box.left - box.width / 2;
                if(offset < 0 && offset > closest.offset) return { offset: offset, element: child };
                else return closest;
            }, { offset: Number.NEGATIVE_INFINITY }).element;
        }
    </script>
</body>
</html>
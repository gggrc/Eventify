<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $board->title }} - Eventify</title>
    
    <link rel="stylesheet" href="{{ asset('css/cardlist.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Priority Colors */
        .priority-low { background-color: #3b82f6 !important; color: white !important; }
        .priority-medium { background-color: #f59e0b !important; color: white !important; }
        .priority-urgent { background-color: #ef4444 !important; color: white !important; }
        .badge-priority { padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; text-transform: uppercase; }

        /* Drag and Drop Visuals - Menjamin area kosong tetap bisa menerima drop */
        .cards-area { min-height: 100px; padding-bottom: 20px; transition: background-color 0.2s; }
        .cards-area.drag-over { background-color: rgba(0, 0, 0, 0.05); border-radius: 8px; }
        .dragging { opacity: 0.5; cursor: grabbing; }
        .dragging-column { opacity: 0.4; border: 2px dashed #ccc; }

        /* Modal Backdrop */
        .modal-backdrop { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center; }
        .modal-backdrop.active { display: flex; }
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
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" alt="avatar" style="width: 32px; height: 32px; border-radius: 50%;">
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
                <h2>{{ $board->title }}</h2>
            </div>
            <div class="topbar-right">
                <a href="{{ route('dashboard') }}" class="btn-back">Back to Dashboard</a>
            </div>
        </header>

        <main class="kanban-board" id="kanban-container">
            @php $colors = ['purple', 'blue', 'orange', 'red']; @endphp

            @foreach($board->taskLists as $index => $list)
                <section class="kanban-column {{ $colors[$index % 4] }} column-draggable" draggable="true" data-list-id="{{ $list->id }}">
                    <div class="column-header">
                        <h3 class="column-title">{{ $list->title }} <span>{{ $list->cards->count() }}</span></h3>
                        <i class="fa-solid fa-plus cursor-pointer" onclick="toggleForm('task-form-{{ $list->id }}')"></i>
                    </div>

                    <div class="cards-area card-list" id="list-{{ $list->id }}" data-list-id="{{ $list->id }}">
                        @foreach($list->cards as $card)
                            <div class="card" draggable="true" data-card-id="{{ $card->id }}" onclick="openCardModal({{ $card->id }})">
                                <p class="title">{{ $card->title }}</p>
                                <div class="meta flex justify-between items-center mt-2">
                                    <span class="text-xs text-gray-500">{{ $card->created_at->format('D H:i') }}</span>
                                    <span class="badge-priority priority-{{ strtolower($card->priority ?? 'low') }}">
                                        {{ $card->priority ?? 'Low' }}
                                    </span>
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
                <span onclick="toggleForm('list-form-main')" class="cursor-pointer"><i class="fa-solid fa-plus"></i> Add another list</span>
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

    <div id="cardModal" class="modal-backdrop" onclick="closeModal()">
        <div class="bg-white p-6 rounded-lg w-full max-w-2xl shadow-xl" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <input type="text" id="modalTitle" class="text-xl font-bold border-none w-full focus:ring-0" placeholder="Judul Card">
                <button onclick="closeModal()" class="text-gray-400 hover:text-black text-2xl">&times;</button>
            </div>

            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="col-span-2">
                    <label class="block text-sm font-semibold mb-1"><i class="fa-solid fa-align-left mr-2"></i>Deskripsi</label>
                    <textarea id="modalDescription" class="w-full border-gray-300 rounded-md text-sm p-2" rows="3" placeholder="Tambahkan deskripsi..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Priority</label>
                    <select id="modalPriority" class="w-full border-gray-300 rounded-md text-sm p-2">
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="Urgent">Urgent</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2"><i class="fa-solid fa-list-check mr-2"></i>Checklist</label>
                <div id="checklistItems" class="space-y-2 mb-2">
                    </div>
                <button onclick="addChecklistItem()" class="text-xs bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded border">
                    + Tambah Item Checklist
                </button>
            </div>

            <div class="flex justify-end gap-2 border-t pt-4">
                <button onclick="closeModal()" class="px-4 py-2 text-sm text-gray-600">Batal</button>
                <button onclick="saveCardChanges()" class="px-6 py-2 text-sm bg-blue-600 text-white rounded font-bold hover:bg-blue-700">
                    SAVE
                </button>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let activeCardId = null;

        // --- DRAG AND DROP LOGIC ---
        function initDragAndDrop() {
            document.querySelectorAll('.card').forEach(card => {
                card.addEventListener('dragstart', (e) => {
                    e.stopPropagation();
                    card.classList.add('dragging');
                });
                card.addEventListener('dragend', () => card.classList.remove('dragging'));
            });

            document.querySelectorAll('.cards-area').forEach(listArea => {
                listArea.addEventListener('dragover', e => {
                    e.preventDefault();
                    listArea.classList.add('drag-over');
                    const draggingCard = document.querySelector('.card.dragging');
                    if (!draggingCard) return;

                    const afterElement = getDragAfterElement(listArea, e.clientY);
                    if (afterElement == null) {
                        listArea.appendChild(draggingCard);
                    } else {
                        listArea.insertBefore(draggingCard, afterElement);
                    }
                });

                listArea.addEventListener('dragleave', () => listArea.classList.remove('drag-over'));

                listArea.addEventListener('drop', async (e) => {
                    e.preventDefault();
                    listArea.classList.remove('drag-over');
                    const draggingCard = document.querySelector('.card.dragging');
                    if (!draggingCard) return;

                    const cardId = draggingCard.dataset.cardId;
                    const newListId = listArea.dataset.listId;
                    const position = Array.from(listArea.querySelectorAll('.card')).indexOf(draggingCard);

                    try {
                        const response = await fetch(`/cards/${cardId}/move`, {
                            method: 'PATCH',
                            headers: { 
                                'Content-Type': 'application/json', 
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ list_id: newListId, position: position })
                        });
                        if (!response.ok) throw new Error('Gagal memindahkan card');
                    } catch (error) { console.error('Move error:', error); }
                });
            });
        }

        function getDragAfterElement(container, y) {
            const draggableElements = [...container.querySelectorAll('.card:not(.dragging)')];
            return draggableElements.reduce((closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;
                if (offset < 0 && offset > closest.offset) {
                    return { offset: offset, element: child };
                } else { return closest; }
            }, { offset: Number.NEGATIVE_INFINITY }).element;
        }

        // --- POPUP MODAL LOGIC ---
        async function openCardModal(cardId) {
            activeCardId = cardId;
            try {
                const res = await fetch(`/cards/${cardId}`, {
                    headers: { 'Accept': 'application/json' }
                });
                
                if (!res.ok) {
                    throw new Error(`Server returned error ${res.status}`);
                }

                const card = await res.json();

                document.getElementById('modalTitle').value = card.title;
                document.getElementById('modalDescription').value = card.description || '';
                document.getElementById('modalPriority').value = card.priority || 'Low';

                const checklistContainer = document.getElementById('checklistItems');
                checklistContainer.innerHTML = '';
                (card.tasks || []).forEach(task => addChecklistItem(task.title, task.is_completed));

                document.getElementById('cardModal').classList.add('active');
            } catch (e) { 
                console.error('Error fetching card:', e);
                alert("Gagal memuat detail card. Pastikan route GET /cards/{id} sudah benar di backend.");
            }
        }

        function addChecklistItem(title = '', checked = false) {
            const container = document.getElementById('checklistItems');
            const div = document.createElement('div');
            div.className = 'flex items-center gap-2 mb-2';
            div.innerHTML = `
                <input type="checkbox" class="task-check rounded text-blue-600" ${checked ? 'checked' : ''}>
                <input type="text" class="task-title border-gray-300 rounded text-sm flex-grow p-1" value="${title}" placeholder="Nama task...">
                <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 text-lg">&times;</button>
            `;
            container.appendChild(div);
        }

        async function saveCardChanges() {
            if(!activeCardId) return;
            const title = document.getElementById('modalTitle').value;
            const description = document.getElementById('modalDescription').value;
            const priority = document.getElementById('modalPriority').value;
            
            const tasks = [];
            document.querySelectorAll('#checklistItems .flex').forEach(item => {
                const tTitle = item.querySelector('.task-title').value;
                if(tTitle.trim() !== "") {
                    tasks.push({
                        title: tTitle,
                        is_completed: item.querySelector('.task-check').checked
                    });
                }
            });

            try {
                const res = await fetch(`/cards/${activeCardId}`, {
                    method: 'PUT',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ title, description, priority, tasks })
                });

                if(res.ok) { 
                    location.reload(); 
                } else {
                    const errorData = await res.json();
                    console.error('Save failed:', errorData);
                }
            } catch (error) { console.error('Save error:', error); }
        }

        function closeModal() { document.getElementById('cardModal').classList.remove('active'); activeCardId = null; }
        
        function toggleForm(id) {
            const form = document.getElementById(id);
            form.style.display = form.style.display === 'block' ? 'none' : 'block';
            if(form.style.display === 'block') form.querySelector('input').focus();
        }

        document.addEventListener('DOMContentLoaded', initDragAndDrop);
    </script>
</body>
</html>
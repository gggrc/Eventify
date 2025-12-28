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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        <header class="topbar">
            <div class="topbar-left">
                <h2>{{ $board->title }}</h2>
            </div>
            <div class="topbar-right">
                <a href="{{ route('dashboard') }}" class="btn-back">Back to Dashboard</a>
            </div>
        </header>
        
    <div class="app-wrapper">
        <main class="kanban-board" id="kanban-container">
            @php $colors = ['purple', 'blue', 'orange', 'red']; @endphp

            @foreach($board->taskLists as $index => $list)
                <section class="kanban-column {{ $colors[$index % 4] }} column-draggable" draggable="true" data-list-id="{{ $list->id }}">
                    <div class="column-header">
                        <h3 class="column-title">{{ $list->title }} <span>{{ $list->cards->count() }}</span></h3>
                        <div class="flex gap-2 items-center">
                            <button type="button" onclick="confirmDeleteList({{ $list->id }})" class="text-gray-400 hover:text-red-500">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
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

                    <div class="add-task-area" id="task-container-{{ $list->id }}">
                        <button class="add-task-trigger" onclick="openAddTask({{ $list->id }})">
                            <i class="fa-solid fa-plus"></i> Add a card
                        </button>

                        <div id="task-form-wrapper-{{ $list->id }}" class="task-form-wrapper" style="display: none;">
                            <form action="{{ route('cards.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="task_list_id" value="{{ $list->id }}">
                                <textarea name="title" class="task-input-textarea" placeholder="Enter a title for this card..." required></textarea>
                                <div class="task-form-footer">
                                    <button type="submit" class="btn-confirm-add">Add card</button>
                                    <button type="button" class="btn-close-add" onclick="closeAddTask({{ $list->id }})">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            @endforeach

            <div class="add-list-container" id="list-container-main">
                <button class="add-list-trigger" onclick="showListForm()">
                    <i class="fa-solid fa-plus"></i> Add another list
                </button>

                <div id="list-form-main" class="list-input-form" style="display: none;">
                    <form action="{{ route('lists.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="board_id" value="{{ $board->id }}">
                        <input type="text" name="title" class="list-entry-input" placeholder="Enter list title..." required>
                        <div class="list-form-actions">
                            <button type="submit" class="btn-list-submit">Add List</button>
                            <button type="button" class="btn-list-cancel" onclick="hideListForm()">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <div id="cardModal" class="modal-backdrop" onclick="closeModal()">
        <div class="modal-card-container" onclick="event.stopPropagation()">
            <div class="modal-card-header">
                <div class="flex-grow">
                    <div class="modal-label-group">
                        <span class="modal-label-text">Task Details</span>
                    </div>
                    <input type="text" id="modalTitle" class="modal-title-input" placeholder="Task Title">
                </div>
                <button onclick="closeModal()" class="modal-close-icon">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="modal-card-body">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="modal-section-title">Description</label>
                        <textarea id="modalDescription" class="modal-desc-area" placeholder="Add detailed description..."></textarea>
                    </div>
                    
                    <div>
                        <label class="modal-section-title">Priority</label>
                        <select id="modalPriority" class="modal-priority-select">
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="Urgent">Urgent</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="modal-section-title">
                        Checklist
                    </label>
                    <div id="checklistItems" class="checklist-items-container"></div>
                    <button onclick="addChecklistItem()" class="btn-add-checklist-item">
                        + Add Item
                    </button>
                </div>
            </div>

            <div class="modal-card-footer justify-between flex">
                <button onclick="deleteCard()" class="btn-modal-delete">
                    <i class="fa-solid fa-trash"></i> DELETE CARD
                </button>
                <div>
                    <button onclick="saveCardChanges()" class="btn-modal-save">SAVE CHANGES</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let activeCardId = null;

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
                    const draggingCard = document.querySelector('.card.dragging');
                    if (!draggingCard) return;

                    const afterElement = getDragAfterElement(listArea, e.clientY);
                    if (afterElement == null) {
                        listArea.appendChild(draggingCard);
                    } else {
                        listArea.insertBefore(draggingCard, afterElement);
                    }
                });

                listArea.addEventListener('drop', async (e) => {
                    e.preventDefault();
                    const draggingCard = document.querySelector('.card.dragging');
                    if (!draggingCard) return;

                    const cardId = draggingCard.dataset.cardId;
                    const newListId = listArea.dataset.listId;
                    
                    const cardsInList = Array.from(listArea.querySelectorAll('.card'));
                    const cardOrder = cardsInList.map((card, index) => {
                        return { id: card.dataset.cardId, position: index };
                    });

                    const position = cardsInList.indexOf(draggingCard);
    
                    updateColumnCounts();

                    try {
                        await fetch(`/cards/${cardId}/move`, {
                            method: 'PATCH',
                            headers: { 
                                'Content-Type': 'application/json', 
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ 
                                list_id: newListId, 
                                position: position,
                                card_order: cardOrder 
                            })
                        });
                    } catch (error) { 
                        console.error('Move error:', error);
                    }
                });
            });

            const kanbanContainer = document.getElementById('kanban-container');
            document.querySelectorAll('.column-draggable').forEach(column => {
                column.addEventListener('dragstart', (e) => {
                    if (e.target.classList.contains('card')) return;
                    column.classList.add('dragging-list');
                });

                column.addEventListener('dragend', async () => {
                    column.classList.remove('dragging-list');
                    
                    const listOrder = [];
                    document.querySelectorAll('.column-draggable').forEach((col, index) => {
                        listOrder.push({ id: col.dataset.listId, position: index });
                    });

                    try {
                        await fetch("{{ route('lists.reorder') }}", {
                            method: 'PATCH',
                            headers: { 
                                'Content-Type': 'application/json', 
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ order: listOrder })
                        });
                    } catch (error) {
                        console.error('Reorder error:', error);
                    }
                });
            });

            kanbanContainer.addEventListener('dragover', e => {
                e.preventDefault();
                const draggingList = document.querySelector('.dragging-list');
                if (!draggingList) return;

                const afterElement = getDragAfterElementList(kanbanContainer, e.clientX);
                const addSection = document.querySelector('.add-list-section');
                
                if (afterElement == null) {
                    kanbanContainer.insertBefore(draggingList, addSection);
                } else {
                    kanbanContainer.insertBefore(draggingList, afterElement);
                }
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

        function getDragAfterElementList(container, x) {
            const draggableElements = [...container.querySelectorAll('.column-draggable:not(.dragging-list)')];
            return draggableElements.reduce((closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = x - box.left - box.width / 2;
                if (offset < 0 && offset > closest.offset) {
                    return { offset: offset, element: child };
                } else { return closest; }
            }, { offset: Number.NEGATIVE_INFINITY }).element;
        }

        async function confirmDeleteList(listId) {
            const result = await Swal.fire({
                title: 'Delete List?',
                text: "All cards in this list will also be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/lists/${listId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        Swal.fire('Deleted!', 'The list has been deleted.', 'success')
                            .then(() => location.reload());
                    }
                } catch (error) {
                    Swal.fire('Error!', 'An error occurred while deleting.', 'error');
                }
            }
        }

        async function openCardModal(cardId) {
            activeCardId = cardId;
            try {
                const res = await fetch(`/cards/${cardId}`, {
                    headers: { 'Accept': 'application/json' }
                });
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
            }
        }

        function addChecklistItem(title = '', checked = false) {
            const container = document.getElementById('checklistItems');
            const div = document.createElement('div');
            div.className = 'checklist-item-row group';
            div.innerHTML = `
                <input type="checkbox" class="task-check" ${checked ? 'checked' : ''}>
                <input type="text" class="task-title-input-field" value="${title}" placeholder="Task name...">
                <button onclick="this.parentElement.remove()" class="btn-delete-checklist-item">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            `;
            container.appendChild(div);
        }

        function updateColumnCounts() {
            document.querySelectorAll('.kanban-column').forEach(column => {
                const count = column.querySelectorAll('.card').length;
                const countBadge = column.querySelector('.column-title span');
                if (countBadge) {
                    countBadge.innerText = count;
                }
            });
        }

        function openAddTask(listId) {
            document.querySelector(`#task-container-${listId} .add-task-trigger`).style.display = 'none';
            const wrapper = document.getElementById(`task-form-wrapper-${listId}`);
            wrapper.style.display = 'block';
            wrapper.querySelector('textarea').focus();
        }

        function closeAddTask(listId) {
            document.querySelector(`#task-container-${listId} .add-task-trigger`).style.display = 'flex';
            document.getElementById(`task-form-wrapper-${listId}`).style.display = 'none';
        }

        function showListForm() {
            document.querySelector('#list-container-main .add-list-trigger').style.display = 'none';
            const form = document.getElementById('list-form-main');
            form.style.display = 'block';
            form.querySelector('input[name="title"]').focus();
        }

        function hideListForm() {
            document.querySelector('#list-container-main .add-list-trigger').style.display = 'flex';
            document.getElementById('list-form-main').style.display = 'none';
        }

        async function saveCardChanges() {
            if(!activeCardId) return;
            const title = document.getElementById('modalTitle').value;
            const description = document.getElementById('modalDescription').value;
            const priority = document.getElementById('modalPriority').value;
            
            const tasks = [];
            document.querySelectorAll('#checklistItems .checklist-item-row').forEach(item => {
                const tTitle = item.querySelector('.task-title-input-field').value;
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

                if(res.ok) { location.reload(); }
            } catch (error) { 
                console.error('Save error:', error); 
            }
        }

        async function deleteCard() {
            if (!activeCardId) return;
            const result = await Swal.fire({
                title: 'Delete Card?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Delete'
            });

            if (result.isConfirmed) {
                try {
                    const res = await fetch(`/cards/${activeCardId}`, {
                        method: 'DELETE',
                        headers: { 
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });
                    if (res.ok) { location.reload(); }
                } catch (error) { console.error('Delete error:', error); }
            }
        }

        function closeModal() { 
            document.getElementById('cardModal').classList.remove('active'); 
            activeCardId = null; 
        }
        
        function toggleForm(id) {
            const form = document.getElementById(id);
            form.style.display = form.style.display === 'block' ? 'none' : 'block';
            if(form.style.display === 'block') form.querySelector('input').focus();
        }

        document.addEventListener('DOMContentLoaded', initDragAndDrop);
    </script>
</body>
</html>
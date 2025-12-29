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
    <div class="flex-grow cursor-pointer group" onclick="editListTitle(event, {{ $list->id }})">
        <h3 class="column-title" id="list-title-text-{{ $list->id }}">
            {{ $list->title }} <span>{{ $list->cards->count() }}</span>
        </h3>
        <input type="text" 
               id="list-title-input-{{ $list->id }}" 
               class="list-title-edit-input" 
               value="{{ $list->title }}" 
               style="display: none;" 
               onblur="saveListTitle({{ $list->id }})"
               onkeydown="if(event.key === 'Enter') saveListTitle({{ $list->id }})">
    </div>
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
                            <form onsubmit="submitCardAjax(event, {{ $list->id }})">
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
                    <form onsubmit="submitListAjax(event)">
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
                    <label class="modal-section-title">Checklist</label>
                    <div id="checklistItems" class="checklist-items-container"></div>
                    <button onclick="addChecklistItem()" class="btn-add-checklist-item">+ Add Item</button>
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
                const addSection = document.getElementById('list-container-main');
                
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

        function updateColumnCounts() {
            document.querySelectorAll('.kanban-column').forEach(column => {
                const count = column.querySelectorAll('.card').length;
                const countBadge = column.querySelector('.column-title span');
                if (countBadge) countBadge.innerText = count;
            });
        }

        async function submitCardAjax(event, listId) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const textarea = form.querySelector('textarea');

            try {
                const response = await fetch("{{ route('cards.store') }}", {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    const cardHtml = `
                        <div class="card" draggable="true" data-card-id="${data.card.id}" onclick="openCardModal(${data.card.id})">
                            <p class="title">${data.card.title}</p>
                            <div class="meta flex justify-between items-center mt-2">
                                <span class="text-xs text-gray-500">${data.card.created_at}</span>
                                <span class="badge-priority priority-low">Low</span>
                            </div>
                        </div>`;
                    document.getElementById(`list-${listId}`).insertAdjacentHTML('beforeend', cardHtml);
                    textarea.value = '';
                    closeAddTask(listId);
                    updateColumnCounts();
                    initDragAndDrop();
                }
            } catch (error) { console.error('Error:', error); }
        }

        async function saveCardChanges() {
    if (!activeCardId) return;

    const title = document.getElementById('modalTitle').value;
    const description = document.getElementById('modalDescription').value;
    const priority = document.getElementById('modalPriority').value;
    
    const tasks = [];
    document.querySelectorAll('#checklistItems .checklist-item-row').forEach(item => {
        const tTitle = item.querySelector('.task-title-input-field').value;
        if (tTitle.trim() !== "") {
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
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ title, description, priority, tasks })
        });

        const data = await res.json();

        if (data.success) {
            const cardElement = document.querySelector(`.card[data-card-id="${activeCardId}"]`);
            
            if (cardElement) {
                cardElement.querySelector('.title').innerText = data.card.title;
                const priorityBadge = cardElement.querySelector('.badge-priority');
                priorityBadge.innerText = data.card.priority;
                priorityBadge.className = `badge-priority priority-${data.card.priority.toLowerCase()}`;
            }

            Swal.fire({
                icon: 'success',
                title: 'Tersimpan!',
                text: 'Perubahan kartu berhasil diperbarui.',
                timer: 1500,
                showConfirmButton: false
            });

            closeModal();
        }
    } catch (error) { 
        console.error('Save error:', error);
        Swal.fire('Gagal!', 'Terjadi kesalahan saat menyimpan perubahan.', 'error');
    }
}

        async function submitListAjax(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const container = document.getElementById('kanban-container');
            const addListSection = document.getElementById('list-container-main');

            try {
                const response = await fetch("{{ route('lists.store') }}", {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    const colors = ['purple', 'blue', 'orange', 'red'];
                    const nextColor = colors[document.querySelectorAll('.kanban-column').length % 4];
                    const listHtml = `
                        <section class="kanban-column ${nextColor} column-draggable" draggable="true" data-list-id="${data.list.id}">
                            <div class="column-header">
                                <h3 class="column-title">${data.list.title} <span>0</span></h3>
                                <div class="flex gap-2 items-center">
                                    <button type="button" onclick="confirmDeleteList(${data.list.id})" class="text-gray-400 hover:text-red-500"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                </div>
                            </div>
                            <div class="cards-area card-list" id="list-${data.list.id}" data-list-id="${data.list.id}"></div>
                            <div class="add-task-area" id="task-container-${data.list.id}">
                                <button class="add-task-trigger" onclick="openAddTask(${data.list.id})"><i class="fa-solid fa-plus"></i> Add a card</button>
                                <div id="task-form-wrapper-${data.list.id}" class="task-form-wrapper" style="display: none;">
                                    <form onsubmit="submitCardAjax(event, ${data.list.id})">
                                        @csrf
                                        <input type="hidden" name="task_list_id" value="${data.list.id}">
                                        <textarea name="title" class="task-input-textarea" placeholder="Enter a title..." required></textarea>
                                        <div class="task-form-footer">
                                            <button type="submit" class="btn-confirm-add">Add card</button>
                                            <button type="button" class="btn-close-add" onclick="closeAddTask(${data.list.id})"><i class="fa-solid fa-xmark"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>`;
                    addListSection.insertAdjacentHTML('beforebegin', listHtml);
                    form.reset();
                    hideListForm();
                    initDragAndDrop();
                }
            } catch (error) { console.error('Error:', error); }
        }

function editListTitle(event, listId) {
    const titleText = document.getElementById(`list-title-text-${listId}`);
    const titleInput = document.getElementById(`list-title-input-${listId}`);

    titleText.style.display = 'none';
    titleInput.style.display = 'block';
    titleInput.focus();
    titleInput.select(); 
}

async function saveListTitle(listId) {
    const titleText = document.getElementById(`list-title-text-${listId}`);
    const titleInput = document.getElementById(`list-title-input-${listId}`);
    const newTitle = titleInput.value.trim();

    if (newTitle === "") {
        titleInput.style.display = 'none';
        titleText.style.display = 'block';
        return;
    }

    try {
        const response = await fetch(`/lists/${listId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ title: newTitle })
        });

        const data = await response.json();

        if (data.success) {
            const cardCount = titleText.querySelector('span').innerText;
            titleText.innerHTML = `${data.list.title} <span>${cardCount}</span>`;
        } else {
            alert('Gagal memperbarui judul.');
        }
    } catch (error) {
        console.error('Error updating list title:', error);
    } finally {
        titleInput.style.display = 'none';
        titleText.style.display = 'block';
    }
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

        async function openCardModal(cardId) {
            activeCardId = cardId;
            try {
                const res = await fetch(`/cards/${cardId}`, { headers: { 'Accept': 'application/json' } });
                const card = await res.json();
                document.getElementById('modalTitle').value = card.title;
                document.getElementById('modalDescription').value = card.description || '';
                document.getElementById('modalPriority').value = card.priority || 'Low';
                const checklistContainer = document.getElementById('checklistItems');
                checklistContainer.innerHTML = '';
                (card.tasks || []).forEach(task => addChecklistItem(task.title, task.is_completed));
                document.getElementById('cardModal').classList.add('active');
            } catch (e) { console.error('Error:', e); }
        }
        function closeModal() { document.getElementById('cardModal').classList.remove('active'); activeCardId = null; }

        document.addEventListener('DOMContentLoaded', initDragAndDrop);
    </script>
</body>
</html>
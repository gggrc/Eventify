<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Movadex Project</title>
    <link rel="stylesheet" href="{{ asset('css/cardlist.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="app-wrapper">
        <header class="topbar">
            <div class="topbar-left">
                <h2>Movadex Project <i class="fa-solid fa-chevron-down"></i></h2>
                <input type="text" placeholder="Search here" />
            </div>

            <div class="topbar-right">
                <button class="btn-icon"><i class="fa-solid fa-plus"></i></button>

                <button class="btn-filter">
                    <i class="fa-solid fa-list"></i>
                    <i class="fa-solid fa-grip"></i>
                </button>

                <select class="task-filter">
                    <option>All tasks</option>
                </select>
            </div>
        </header>

        <main class="kanban-board">
            <section class="kanban-column purple">
                <div class="column-header">
                    <h3>Design <span>3</span></h3>
                    <i class="fa-solid fa-plus"></i>
                </div>

                <div class="card high">
                    <p class="title">Create a preview for the last article from our blog</p>
                    <div class="meta">
                        <span>Tomorrow 16:00</span>
                        <span class="badge high">High</span>
                    </div>
                </div>

                <div class="card medium">
                    <p class="title">Motion and Static Ads for Instagram and Facebook</p>
                    <div class="meta">
                        <span>Today 11:00</span>
                        <span class="badge medium">Medium</span>
                    </div>
                </div>

                <div class="card low">
                    <p class="title">Icons design for the landing page</p>
                    <div class="meta">
                        <span>Thursday 09:00</span>
                        <span class="badge low">Low</span>
                    </div>
                </div>

                <button class="add-task">+ Add new task</button>
            </section>

            <section class="kanban-column blue">
                <div class="column-header">
                    <h3>Front-End <span>3</span></h3>
                    <i class="fa-solid fa-plus"></i>
                </div>

                <div class="card high">
                    <p class="title">Fix switching between pages on the User profile</p>
                    <div class="meta">
                        <span>Today 11:00</span>
                        <span class="badge high">High</span>
                    </div>
                </div>

                <div class="card low">
                    <p class="title">Implement design on the new landing</p>
                    <div class="meta">
                        <span>Wednesday 11:00</span>
                        <span class="badge low">Low</span>
                    </div>
                </div>

                <div class="card medium">
                    <p class="title">Make background white and add picture to About Us</p>
                    <div class="meta">
                        <span>Today 09:00</span>
                        <span class="badge medium">Medium</span>
                    </div>
                </div>

                <button class="add-task">+ Add new task</button>
            </section>

            <section class="kanban-column orange">
                <div class="column-header">
                    <h3>Back-End <span>2</span></h3>
                    <i class="fa-solid fa-plus"></i>
                </div>

                <div class="card high">
                    <p class="title">Create API keys generator platform</p>
                    <div class="meta">
                        <span>Today 13:00</span>
                        <span class="badge high">High</span>
                    </div>
                </div>

                <div class="card medium">
                    <p class="title">Add endpoints for new artist</p>
                    <div class="meta">
                        <span>Today 13:00</span>
                        <span class="badge medium">Medium</span>
                    </div>
                </div>

                <button class="add-task">+ Add new task</button>
            </section>

            <section class="kanban-column red">
                <div class="column-header">
                    <h3>Testing <span>3</span></h3>
                    <i class="fa-solid fa-plus"></i>
                </div>

                <div class="card high">
                    <p class="title">Test user contact form (ISSUE-34)</p>
                    <div class="meta">
                        <span>Today 09:00</span>
                        <span class="badge high">High</span>
                    </div>
                </div>

                <div class="card medium">
                    <p class="title">Pass testcases pm company page</p>
                    <div class="meta">
                        <span>Today 14:00</span>
                        <span class="badge medium">Medium</span>
                    </div>
                </div>

                <div class="card low">
                    <p class="title">Pass testcases on landing page</p>
                    <div class="meta">
                        <span>Thursday 12:30</span>
                        <span class="badge low">Low</span>
                    </div>
                </div>

                <button class="add-task">+ Add new task</button>
            </section>

        </main>
    </div>
</body>
</html>

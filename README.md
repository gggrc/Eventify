# Eventify
## Web-Based Project Management System 

Eventify is a modern web-based project management application built using **Laravel 12**.  
The system allows users to manage projects through boards, task lists, and cards using a clean, responsive, and structured interface inspired by Trello.

---

## Table of Contents

1. Introduction  
2. System Overview  
3. Features  
4. Technology Stack  
5. System Architecture  
6. Project Structure  
7. Installation and Setup  

---

## 1. Introduction

Eventify is designed to help individuals and teams organize tasks efficiently by visualizing workflows and project progress.  
The application follows Laravel’s MVC architecture and uses **Blade Templates** for server-side rendering to ensure performance, maintainability, and scalability.

---

## 2. System Overview

The system enables authenticated users to:
- Create and manage project boards
- Organize tasks into lists and cards
- Move cards between lists to represent task progress
- Manage user profiles securely

Eventify can be used as an academic project, a personal productivity tool, or as a foundation for a scalable team collaboration system.

---

## 3. Features

### Project Boards
- Create, update, reorder, and delete project boards
- Persistent board ordering

### Task Lists
- Create and manage lists within project boards
- Dynamic interactions without full page reload

### Task Cards
- Create, edit, delete, and move cards between lists
- Drag-and-drop functionality

### Authentication
- User registration and login
- Email verification
- Password reset
- Implemented using Laravel Breeze

### User Profile Management
- Update personal information
- Change password securely

### User Interface
- Responsive and modern design
- Server-rendered views using Blade Templates
- Interactive components powered by Alpine.js

---

## 4. Technology Stack

### Backend
- PHP 8.2 or higher
- Laravel 12

### Frontend
- Blade Templates
- Tailwind CSS 4.0
- Alpine.js

### Build Tools
- Vite

### Additional Packages
- vinkla/hashids – Secure ID obfuscation
- laravel/breeze – Authentication scaffolding
- axios – Asynchronous HTTP requests

---

## 5. System Architecture

Eventify is built using the Model-View-Controller (MVC) architecture:
- Models handle database interactions and business logic
- Controllers manage HTTP requests and application flow
- Views are rendered using Blade Templates
- Routes define application endpoints
- Middleware manages authentication and authorization

This architecture ensures separation of concerns and improves maintainability.

---

## 6. Project Structure

app/
├── Http/
│ ├── Controllers/
│ ├── Middleware/
├── Models/
resources/
├── views/
│ ├── layouts/
│ ├── dashboard/
│ ├── boards/
│ ├── profile/
├── css/
├── js/
routes/
├── web.php
database/
├── migrations/
├── seeders/


---

## 7. Installation and Setup

### Step 1: Clone the Repository
```bash
git clone https://github.com/gggrc/Eventify
cd Eventify
```

### Step 2: Install Dependencies and Setup Project

Run the following command to install all required dependencies and initialize the project.

This command will:
- Install Composer dependencies
- Install NPM dependencies
- Create the `.env` file
- Generate the application key
- Run database migrations
- Build frontend assets

```bash
composer run setup
```

### Step 3: Run the Application

You can start the application using one of the following methods.

#### Option 1: Using Composer Script (Recommended)
```bash
composer run dev
```

#### Option 2: Using Default Laravel Server
```bash
php artisan serve
```

The application will be available at:
```bash
http://localhost:8000
```

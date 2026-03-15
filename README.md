# Mini Trello

A lightweight project and task management application built with Laravel, Livewire, and Flux UI.

## Features

- **Projects** — Create, edit, and delete projects with name and description
- **Tasks** — Add, edit, delete, and reorder tasks within each project via drag-and-drop
- **Dashboard** — Overview showing the number of projects
- **Authentication** — Full auth with two-factor authentication support (Laravel Fortify)

## Tech Stack

- [Laravel 12](https://laravel.com/) — PHP framework
- [Livewire 4](https://livewire.laravel.com/) — Full-stack framework for dynamic interfaces
- [Flux UI](https://flux.livewire.laravel.com/) — Component library for Livewire
- [Tailwind CSS 4](https://tailwindcss.com/) — Utility-first CSS
- [SortableJS](https://sortablejs.github.io/Sortable/) — Drag-and-drop task reordering
- [Pest](https://pestphp.com/) — Testing framework

## Requirements

- PHP 8.2+
- Composer
- Node.js & npm

## Installation

```bash
# Clone the repository
git clone <repository-url> mini-trello
cd mini-trello

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Set up environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Build assets
npm run build
```

## Development

```bash
# Start the dev server and Vite
php artisan serve
npm run dev
```

## Usage

1. **Register** — Create an account on the registration page
2. **Login** — Sign in with your credentials
3. **Create a Project** — Navigate to the Projects page and add a new project with a name and description
4. **Add Tasks** — Click the "Tasks" button on a project to open its task board, then add tasks to that project
5. **Manage Tasks** — Edit, delete, or drag-and-drop to reorder tasks within a project

> A project must be created before tasks can be added, as every task belongs to a project.

## Testing

```bash
php artisan test
```

## License

MIT

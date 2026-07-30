# EVSU NSTP-CWTS Management System

A student registry and management system for the EVSU NSTP-CWTS (National Service Training Program – Civic Welfare Training Service), built with Laravel, Livewire, Alpine.js, and Tailwind CSS.

## Requirements

Before you begin, make sure you have the following installed:

- PHP >= 8.2
- Composer
- Node.js >= 18.x and npm
- MySQL (or your preferred supported database)
- Git

## Getting Started

### 1. Clone the repository

```bash
git clone <repository-url>
cd <project-folder>
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install JavaScript dependencies

```bash
npm install
```

### 4. Set up environment file

Copy the example environment file and generate the application key:

```bash
cp .env.example .env
php artisan key:generate
```

> **Windows (Command Prompt) users:** use `copy .env.example .env` instead of `cp`.

### 5. Configure your database

Open the `.env` file and update the database connection details:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=evsu_nstp_cwts
DB_USERNAME=root
DB_PASSWORD=
```

Create the database (e.g. via phpMyAdmin, MySQL CLI, or TablePlus):

```sql
CREATE DATABASE evsu_nstp_cwts;
```

### 6. Run migrations (and seeders, if available)

```bash
php artisan migrate
```

If seeders are set up (e.g. for default admin accounts or sample students):

```bash
php artisan migrate --seed
```

### 7. Link storage (if the app uses file uploads, e.g. CSV imports)

```bash
php artisan storage:link
```

### 8. Build frontend assets

For development (with hot reload):

```bash
npm run dev
```

For a production build:

```bash
npm run build
```

### 9. Serve the application

```bash
php artisan serve
```

The app will be available at:

```
http://127.0.0.1:8000
```

## Running Development Mode (recommended)

To run the PHP server and Vite dev server together, open two terminal tabs:

```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

Or, if a `composer.json` dev script is configured:

```bash
composer run dev
```

## Useful Artisan Commands

| Command | Description |
|---|---|
| `php artisan migrate:fresh --seed` | Reset database and re-seed |
| `php artisan route:list` | List all registered routes |
| `php artisan make:livewire ComponentName` | Create a new Livewire component |
| `php artisan cache:clear` | Clear application cache |
| `php artisan config:clear` | Clear config cache |
| `php artisan view:clear` | Clear compiled Blade views |
| `php artisan optimize:clear` | Clear all caches at once |

## Default Login (if seeded)

If a database seeder creates a default account, check `database/seeders/DatabaseSeeder.php` (or a related seeder file) for credentials, or ask a team member for access.

## Tech Stack

- **Backend:** Laravel
- **Frontend:** Livewire, Alpine.js, Tailwind CSS
- **Build tool:** Vite
- **Database:** MySQL

## Troubleshooting

- **Blank page / 500 error:** Run `php artisan config:clear` and check `.env` values are correct.
- **Class not found errors:** Run `composer dump-autoload`.
- **Styles/scripts not updating:** Make sure `npm run dev` is running, or rebuild with `npm run build`.
- **Permission errors (storage/logs):** Ensure `storage/` and `bootstrap/cache/` are writable.

## License

Internal project for EVSU NSTP-CWTS. All rights reserved.
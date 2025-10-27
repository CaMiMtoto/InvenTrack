# InvenTrack

InvenTrack is a Laravel-based Inventory, Order, and Delivery Management System.
It helps businesses manage products, customer orders, deliveries, returns, stock reconciliation, and finances — all in one place.

---

## Overview

InvenTrack centralizes day-to-day operations for small and medium businesses:
- Manage customers and products
- Create, approve, and fulfill orders
- Assign and track deliveries (including partial/failed deliveries and returns)
- Reconcile stock automatically on returns
- Record non-cash payments and expenses, and view basic financial reports

The admin dashboard and role-based permissions ensure the right users see the right actions.

---

## Features

- Customers: CRUD, detailed profiles, order history
- Orders: create, approve/reject, assign to delivery
- Deliveries: track status, process returns with reasons
- Stock: purchases, adjustments, history, reconciliation
- Payments: non-cash receipts linked to orders
- Expenses and reports: sales, payments, stock, purchases, items, expenses
- Roles and permissions, users management

User roles visible in UI and code:
- Customer Care
- Store Keeper
- Delivery Person
- Finance
- Admin

---

## Tech Stack

Backend and tooling
- PHP 8.2+
- Laravel 12.x (`laravel/framework` ^12.0)
- MySQL (primary DB)
- Queue worker (listens during dev) — driver configurable via `.env`
- PHPUnit 11 for testing

Frontend and assets
- Blade templates, with optional Vue 3 components (`@vitejs/plugin-vue` present)
- Livewire 3 (`livewire/livewire`)
- Tailwind CSS via Vite
- Vite build pipeline (`laravel-vite-plugin`), image optimization plugins

Notable Laravel packages
- spatie/laravel-permission (roles/permissions)
- spatie/laravel-medialibrary (media handling)
- owen-it/laravel-auditing (model audits)
- maatwebsite/excel (Excel exports)
- barryvdh/laravel-dompdf + dompdf (PDFs)
- yajra/laravel-datatables (server-side DataTables)
- pragmarx/google2fa-laravel (2FA)
- predis/predis (Redis client; optional)
- simplesoftwareio/simple-qrcode (QR codes)

Auth scaffold
- Uses Laravel UI session-based auth (`laravel/ui` + `Auth::routes()`), not Breeze/Sanctum.

---

## Requirements

- PHP 8.2+
- Composer 2.x
- Node.js 18+ and npm 9+
- MySQL 8.x (or compatible MariaDB)
- A web server for production (Nginx/Apache) configured to serve `public/`
- Optional: Redis for cache/queue

---

## Setup

1) Clone and install
```
git clone <repo-url>
cd InvenTrack
composer install
npm install
```

2) Environment
```
cp .env.example .env
php artisan key:generate
```
Edit `.env` and configure at least:
- APP_NAME=InvenTrack
- APP_URL=http://localhost
- DB_CONNECTION=mysql
- DB_HOST=127.0.0.1
- DB_PORT=3306
- DB_DATABASE=inventrack
- DB_USERNAME=...
- DB_PASSWORD=...
- QUEUE_CONNECTION=sync  # or database/redis
- SESSION_DRIVER=file     # or database/redis
- CACHE_STORE=file        # or redis
- MAIL_MAILER=smtp        # if emailing needed
- FILESYSTEM_DISK=public

3) Database
```
php artisan migrate
# Optional: seed demo data if available
php artisan db:seed   # TODO: confirm seeders and credentials
```

4) Storage symlink (for public uploads)
```
php artisan storage:link
```

---

## Run (development)

Option A — single composer script (PHP server + queue listener + Vite)
```
composer run dev
```

Option B — separate terminals
```
php artisan serve
php artisan queue:listen --tries=1
npm run dev
```
Then open http://127.0.0.1:8000

Entry points
- Web: `public/index.php` (Laravel)
- Vite entry assets: defined in `vite.config.js` inputs (`resources/css/app.css`, `resources/sass/*.scss`, `resources/js/*.js`)

---

## Build (production)

```
npm run build
# Configure your web server to serve the public/ directory
# Run queue worker (e.g., Supervisor) if using queues
```

Example env tweaks for production
- APP_ENV=production, APP_DEBUG=false
- QUEUE_CONNECTION=redis (recommended) — ensure Redis is running
- CACHE_STORE=redis (optional)
- SESSION_DRIVER=redis or database (optional)

---

## Available Scripts

Composer
- `composer run dev` — concurrently runs PHP server, queue:listen, and Vite
- `composer test` — clears config cache and runs the test suite

NPM
- `npm run dev` — start Vite in dev mode
- `npm run build` — build assets for production

---

## Testing

The project uses PHPUnit.
- Config: `phpunit.xml` (uses in-memory SQLite by default for tests)
- Run:
```
composer test
# or
php artisan test
```

Tip: ensure no pending migrations depend on MySQL-only features when running against SQLite in memory.

---

## Project Structure

Key paths at repository root:
- `app/` — application code (Models, Http/Controllers, Middleware, etc.)
- `bootstrap/` — framework bootstrap
- `config/` — configuration files
- `database/` — migrations, seeders, factories
- `public/` — web server document root
- `resources/` — Blade views, JS, CSS/SASS
- `routes/` — route definitions (`web.php`, `api.php`)
- `storage/` — logs, cache, compiled views, uploads
- `tests/` — Feature and Unit tests
- `vite.config.js`, `tailwind.config.js`, `postcss.config.js` — frontend tooling

See `routes/web.php` for available endpoints and route groups (admin dashboard, products, orders, deliveries, payments, settings, reports, users/roles, etc.).

---

## Environment Variables

Common `.env` keys used by Laravel and/or packages in this repo:
- Core: `APP_NAME`, `APP_ENV`, `APP_KEY`, `APP_URL`, `APP_DEBUG`, `TIMEZONE`
- DB: `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- Cache/Queue: `CACHE_STORE`, `QUEUE_CONNECTION`, `REDIS_HOST`, `REDIS_PASSWORD`, `REDIS_PORT`
- Session: `SESSION_DRIVER`, `SESSION_LIFETIME`
- Mail: `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_ENCRYPTION`, `MAIL_FROM_ADDRESS`
- Files: `FILESYSTEM_DISK`
- 2FA: `GOOGLE2FA_SECRET` (package-specific; TODO: document usage if applicable)

TODOs
- Confirm and document demo login credentials if seeders provide them
- Add environment variable docs for any custom features (reports, media library, auditing) if required

---

## License

This project declares license MIT in `composer.json`.
- If a `LICENSE` file is not present in the repository root, please add one.  
  TODO: add `LICENSE` file with MIT text.

---

## Notes for Production

- Point your web server to the `public/` directory
- Ensure queues and scheduled tasks are supervised (Supervisor/systemd)
- Configure backups and environment-specific logging
- Harden permissions on `storage/` and `bootstrap/cache/`



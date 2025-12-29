# Simple Cart

A simple e-commerce shopping cart application built with Laravel 12, Vue 3, Inertia.js, and Tailwind CSS.

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- SQLite (default) or MySQL/PostgreSQL
- Docker (optional, for Mailpit)

## Quick Start

```bash
# Clone the repository
git clone <repository-url>
cd simple-cart

# Run the setup script (installs dependencies, creates .env, runs migrations, builds assets)
composer setup

# Start the development server
composer dev
```

The application will be available at http://localhost:8000

## Initial Setup

````bash
# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Run seeders
php artisan db:seed


## Development

Start all development services (server, queue, logs, vite):

```bash
composer dev
````

Or run services individually:

```bash
# Start Laravel development server
php artisan serve

# Start Vite dev server (in another terminal)
npm run dev

# Start queue worker (in another terminal)
php artisan queue:listen
```

## Email Testing with Mailpit

The project includes Docker configuration for Mailpit, a local email testing tool.

```bash
# Start Mailpit
docker compose up -d

# View emails at
open http://localhost:8025
```

Mailpit captures all emails sent by the application, useful for testing:

- Order confirmation emails
- Low stock notifications
- Daily sales reports

## Environment Configuration

Key environment variables in `.env`:

| Variable              | Description                      | Default             |
| --------------------- | -------------------------------- | ------------------- |
| `APP_URL`             | Application URL                  | `http://localhost`  |
| `DB_CONNECTION`       | Database driver                  | `sqlite`            |
| `MAIL_PORT`           | SMTP port (Mailpit)              | `1025`              |
| `MAIL_WEB_PORT`       | Mailpit web UI port              | `8025`              |
| `ADMIN_EMAIL`         | Admin email for notifications    | `admin@example.com` |
| `LOW_STOCK_THRESHOLD` | Stock level for low stock alerts | `5`                 |

## Available Commands

```bash
# Run schedule manually
composer schedule:test

# Run tests
composer test

# Format code
npm run format

# Lint code
npm run lint

# Build for production
npm run build


## Tech Stack

- **Backend**: Laravel 12, PHP 8.2+
- **Frontend**: Vue 3, Inertia.js, Tailwind CSS 4
- **UI Components**: Reka UI (shadcn-vue), Headless UI
- **Build**: Vite 7
- **Database**: SQLite (default), MySQL, PostgreSQL
- **Email**: Mailpit (development)

## License

MIT
```

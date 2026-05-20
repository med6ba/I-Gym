# I-Gym

![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3-38B2AC?style=for-the-badge&logo=tailwindcss&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-111827?style=for-the-badge)

I-Gym is a multi-role fitness SaaS platform for modern gyms. It brings gym operations, class bookings, member access, subscriptions, progress tracking, coaching workflows, analytics, and AI fitness assistance into one Laravel application.

The project is built as a realistic gym management product, not just a demo screen: every main workspace is role-aware, tenant-scoped by gym, localized, responsive, and backed by structured Laravel controllers, requests, models, factories, migrations, and seeders.

## Highlights

- Multi-gym SaaS workspace for platform owners and gym teams.
- Role-based dashboards for super admins, gym admins, coaches, reception staff, and members.
- Course scheduling, reservations, attendance, subscriptions, notifications, and member progress.
- NFC access simulation for members and reception check-ins.
- IGyma, an in-app AI fitness assistant powered by Groq when configured.
- Super admin gym exports to styled Excel and branded PDF reports.
- Dark/light themes, language settings, RTL Arabic support, and PWA assets.

## Feature Map

| Role | What they can do |
| --- | --- |
| Super Admin | Manage gyms, gym admins, customer status, subscription plans, platform analytics, and gym exports. |
| Gym Admin | Manage members, coaches, courses, reservations, subscriptions, attendance, notifications, and activity logs. |
| Coach | View assigned classes, mark class attendance, manage training plans, and record member progress. |
| Reception | Simulate NFC bracelet scanning and record front-desk check-ins. |
| Member | Book classes, manage reservations, view subscription status, track progress, receive notifications, use NFC access, and chat with IGyma. |

## Tech Stack

- Laravel 13, Laravel Breeze, Blade components
- PHP 8.3+, Eloquent ORM, migrations, factories, seeders
- MySQL by default, with Laravel-supported database alternatives available
- TailwindCSS, Alpine.js, Chart.js, Vite
- Form requests, custom middleware, scoped route model binding
- `simplesoftwareio/simple-qrcode`
- Groq API integration for IGyma

## Requirements

- PHP 8.3 or newer
- Composer
- Node.js 22 or newer
- NPM
- MySQL or another Laravel-supported database

## Quick Start

Clone the repository and install dependencies:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Create a database and update `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=igym
DB_USERNAME=root
DB_PASSWORD=
```

Run the application:

```bash
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Open:

```text
http://127.0.0.1:8000
```

For active development, run Vite in a separate terminal:

```bash
npm run dev
```

## Demo Accounts

All seeded demo accounts use:

```text
password
```

| Role | Email |
| --- | --- |
| Super Admin | `super@igym.com` |
| Gym Admin | `admin@igym.com` |
| Coach | `coach@igym.com` |
| Reception | `reception@igym.com` |
| Member | `member@igym.com` |

## Environment

Optional IGyma AI setup:

```env
GROQ_API_KEY=your_key_here
GROQ_MODEL=llama-3.1-8b-instant
```

Useful defaults for local development:

```env
APP_ENV=local
APP_DEBUG=true
SESSION_DRIVER=file
CACHE_STORE=database
QUEUE_CONNECTION=database
```

## Project Structure

```text
app/
  Actions/                 Shared domain actions
  Http/Controllers/         Role-specific controllers
  Http/Middleware/          Role and gym access guards
  Http/Requests/            Validated form requests
  Models/                   Eloquent models and relationships
  Support/                  Helpers and export support
database/
  migrations/               Core schema
  seeders/                  Demo SaaS data
resources/
  views/                    Blade UI by workspace
  lang/                     English, French, Spanish, Arabic
routes/
  web.php                   Role-protected route groups
```

## Security Model

I-Gym uses authenticated route groups, role middleware, and gym workspace access checks. Most operational records include `gym_id`, and route model bindings scope users, courses, reservations, subscriptions, and notifications to the authenticated user’s allowed workspace.

Super admins operate globally. Gym admins, coaches, reception staff, and members operate inside their assigned gym.

## Useful Commands

```bash
php artisan route:list
php artisan migrate:fresh --seed
php artisan test
npm run build
vendor/bin/pint
```

## Product Notes

I-Gym is designed for a polished demo and a credible SaaS foundation. The current implementation focuses on gym operations, member experience, role isolation, reporting, and responsive UI. Production extensions could include online payments, real NFC hardware, push notifications, tenant subdomains, and advanced analytics.

## License

This project is open-sourced under the MIT license.

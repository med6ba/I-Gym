# I-Gym

**Smart Fitness Management for the Next Generation.**

I-Gym is a Laravel SaaS fitness platform built for the hackathon theme **“I-Gym: La Plateforme Fitness Nouvelle Génération”**. It helps a modern gym manage customer gyms, roles, bookings, subscriptions, QR access, coach workflows, attendance, member progress, notifications, and smart dashboards.

## Main Features

- Four roles: `super_admin`, `gym_admin`, `coach`, `member`
- Laravel Breeze authentication with role redirects
- Simple SaaS multi-gym isolation using `gym_id`
- Super Admin customer gym management and global analytics
- Gym Admin member, coach, course, subscription, attendance, reservation, and notification tools
- Coach class attendance, member follow-up, training plans, and progress capture
- Member booking, QR code access, reservations, subscription status, progress, and notifications
- Smart occupancy alerts, no-show tracking, subscription expiration alerts, and simulated AI recommendations
- Responsive Blade + Tailwind UI with dark/light mode
- English, French, Spanish, and Arabic localization with RTL support
- PWA manifest, service worker, icon placeholder, and offline fallback

## Tech Stack

- Laravel 13
- Laravel Breeze
- Laravel Blade
- MySQL
- Eloquent relationships, migrations, seeders, factories
- Form requests and role middleware
- TailwindCSS, Alpine.js, Chart.js
- `simplesoftwareio/simple-qrcode`

## Installation

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Create the MySQL database:

```sql
CREATE DATABASE igym CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Set the database credentials in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=igym
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations and seeders:

```bash
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Open `http://127.0.0.1:8000`.

## Demo Accounts

All demo accounts use the password:

```text
password
```

- Super Admin: `super@igym.test`
- Gym Admin: `admin@igym.test`
- Coach: `coach@igym.test`
- Member: `member@igym.test`

## Roles

`super_admin` manages the commercial SaaS layer: gyms, activation states, subscription plans, and platform analytics.

`gym_admin` manages only users and operational data where `gym_id` matches their gym.

`coach` sees only assigned courses and can mark attendance, manage plans, and record progress inside their gym.

`member` sees only their own bookings, QR code, subscription, progress, and notifications.

## Database Overview

Core tables:

- `gyms`
- `users`
- `subscriptions`
- `courses`
- `reservations`
- `attendances`
- `notifications`
- `training_plans`
- `member_progress`

Every operational table includes `gym_id`. Super admins are global and have `gym_id = null`.

## Innovation Features

- Smart occupancy calculation: reservations divided by max capacity
- Full class booking lockout
- High demand alert at 80% occupancy
- QR access simulation with scannable member payloads
- AI recommendation simulation based on member goal
- Business insights for gym admins
- Subscription expiration alerts within seven days
- No-show tracking from missed reserved classes
- SaaS customer layer for multiple gyms

## PWA

I-Gym includes:

- `public/manifest.json`
- `public/service-worker.js`
- `public/offline.html`
- `public/icons/icon.svg`
- Standalone display mode and orange theme color

## Localization

Language files live in:

- `resources/lang/en/messages.php`
- `resources/lang/fr/messages.php`
- `resources/lang/es/messages.php`
- `resources/lang/ar/messages.php`

Arabic sets `dir="rtl"` at the HTML level.

## Dark and Light Mode

Tailwind dark mode uses the `class` strategy. The UI persists the selected theme in `localStorage` and stores the authenticated user preference in `users.theme`.

## Demo Scenario

1. Login as Super Admin.
2. Show SaaS dashboard and gym customers.
3. Open global analytics.
4. Login as Gym Admin.
5. Show gym stats, occupancy, and smart alerts.
6. Create or view a course.
7. Show reservations and subscription expiration alerts.
8. Login as Member.
9. Book an available class.
10. Show QR code.
11. Login as Coach.
12. Mark the member present.
13. Return to dashboards and show updated attendance.

## Verification Notes

Validated in this workspace:

- `composer dump-autoload`
- `php artisan route:list --except-vendor`
- `npm run build`
- PHP syntax check across app/database/routes/config
- `php artisan view:cache`

MySQL migration/seed verification requires valid local MySQL credentials. The current shell could not authenticate to MariaDB as `root`.

## Future Improvements

- Real payment gateway integration
- Real QR scanner camera flow
- Push notifications
- Subdomain-based tenant routing
- Member mobile app wrapper
- Real AI workout recommendation service
- Advanced reporting exports

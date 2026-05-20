# I-Gym Pitch

## One-Liner

I-Gym is a next-generation fitness SaaS platform that helps gyms manage members, classes, access, coaching, subscriptions, progress, and insights from one role-based workspace.

## The Problem

Many gyms still run daily operations across disconnected tools: manual attendance sheets, messaging apps, spreadsheet subscriptions, separate booking workflows, and limited visibility into member behavior. That creates friction for staff and a weaker experience for members.

Gym owners need a platform that is simple enough for daily use, but complete enough to support bookings, check-ins, coaches, progress, subscriptions, and business decisions.

## The Solution

I-Gym centralizes gym operations in one Laravel SaaS product. Each role gets the workspace they need:

- Platform owners manage customer gyms and exports.
- Gym admins manage operations and analytics.
- Coaches manage classes, plans, and progress.
- Reception staff handle access check-ins.
- Members book classes, track progress, view subscriptions, and use IGyma.

The result is a clearer workflow for teams and a smoother digital experience for members.

## Target Users

| User | Need |
| --- | --- |
| SaaS owner | Manage multiple gyms from one platform. |
| Gym admin | Control classes, members, coaches, reservations, and subscriptions. |
| Coach | Track assigned classes, attendance, training plans, and progress. |
| Reception staff | Check members in quickly using NFC simulation. |
| Member | Book classes, follow progress, receive notifications, and get fitness guidance. |

## Core Value

- Less manual work for gym teams.
- Better booking and attendance flow.
- Clearer subscription and progress visibility.
- Stronger member engagement through dashboards, notifications, and IGyma.
- A SaaS-ready structure for multi-gym management.

## Key Features

- Multi-gym SaaS architecture with `gym_id` isolation.
- Role-based dashboards and protected routes.
- Course booking, cancellation, occupancy, and no-show tracking.
- Subscription management with expiration awareness.
- NFC bracelet access simulation.
- Coach training plans and member progress records.
- Admin notifications and activity logs.
- Super admin Excel/PDF gym exports.
- Dark/light themes, localization, RTL Arabic support, and PWA assets.
- IGyma AI fitness assistant with fitness-only guardrails.

## Differentiation

I-Gym is not just a booking screen. It connects the operational loop:

1. Admin creates classes and manages subscriptions.
2. Member books a class.
3. Reception or coach records attendance.
4. Progress and no-show data update dashboards.
5. Admin sees insights and can act.
6. Super admin monitors the SaaS customer layer.

This makes the platform feel like a real product for a modern gym business.

## Demo Flow

1. Login as the Super Admin and show SaaS metrics.
2. Open gym management and export the gym list as Excel or PDF.
3. Login as the Gym Admin and show members, coaches, courses, and smart alerts.
4. Create or review a class with reservations.
5. Login as a Member and book a class.
6. Show subscription, progress, NFC access, and IGyma.
7. Login as a Coach and mark attendance.
8. Return to dashboards to show updated activity.

## Technical Foundation

- Laravel 13 and Breeze authentication.
- Eloquent relationships with tenant-aware `gym_id` scoping.
- Custom role and gym access middleware.
- Form request validation for clean workflows.
- Blade components, TailwindCSS, Alpine.js, Chart.js, and Vite.
- MySQL-ready schema with migrations, factories, and seeders.
- Localized UI in English, French, Spanish, and Arabic.

## Business Potential

I-Gym can be positioned as an affordable SaaS for independent gyms, fitness studios, and small gym chains that need a modern management platform without enterprise complexity.

Possible revenue paths:

- Monthly gym subscription plans.
- Premium analytics and exports.
- Paid AI fitness assistant features.
- Add-ons for real NFC hardware, payment gateways, and mobile apps.

## Roadmap

- Payment gateway integration.
- Real NFC hardware and camera scanning.
- Push notifications.
- Tenant subdomains.
- Advanced reporting and revenue analytics.
- Native mobile wrapper.

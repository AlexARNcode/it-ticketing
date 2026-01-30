# IT Ticketing System

Simple IT ticket management application built with **Laravel + Inertia + Vue**.  

---

## Stack

- **Backend:** Laravel 12
- **Frontend:** Vue 3 + Inertia.js
- **Database:** PostgreSQL
- **Auth:** Laravel Breeze
- **Styling:** TailwindCSS
- **Dev Environment:** Laravel Sail (Docker)

---

## Features (Current)

- Authentication (login / register)
- Organizations
- Users belong to an organization
- Tickets CRUD (minimal)
- Ticket status transitions with domain rules
- SLA policies per organization
- Automatic SLA resolution on ticket creation
- Seeders with fake data
- Feature & unit tests

---

## Ticket Workflow

Allowed status transitions:

- OPEN → IN_PROGRESS → RESOLVED → CLOSED
- RESOLVED → OPEN (reopen allowed)
- CLOSED → immutable

---

## Installation

### 1. Clone the project

```bash
git clone https://github.com/your-username/it-ticketing.git
cd it-ticketing

2. Install dependencies
composer install
npm install

3. Environment
cp .env.example .env
php artisan key:generate

Configure your database in .env.

Run with Sail (Docker)
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
./vendor/bin/sail npm run dev
Database Seeding
Creates:

1 organization
1 test user
3 fake users

Default SLA policies:
./vendor/bin/sail artisan db:seed

Default login:
email: test@test.com
password: password

Tests
./vendor/bin/sail artisan test
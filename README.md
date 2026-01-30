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

## Ticket Policy (Authorization)

User permissions are role-based:

- View & Create: All users
- Update: TECH and ADMIN can update any ticket; regular users can update their own
- Delete / Restore / Force Delete: Only ADMIN

This ensures users can act on their tickets while respecting role restrictions.

## SLA Resolution for Tickets
#### What is SLA?
A Service Level Agreement (SLA) defines the expected response and resolution times for tickets based on their priority and organization.

#### SLA in this app:
The application automatically sets SLA deadlines when a ticket is created:

If the ticket doesn’t already have SLA deadlines, it finds the SLA policy matching the ticket’s organization and priority.

It calculates:
- First response due time = current time + first_response_minutes from the policy

- Resolution due time = current time + resolution_minutes from the policy

These deadlines are saved on the ticket.

If no matching SLA policy exists, an error is thrown.

This ensures every ticket has proper SLA deadlines based on its organization and priority.

---

## Installation

### 1. Clone the project

```bash
git clone https://github.com/your-username/it-ticketing.git
cd it-ticketing
```

### 2. Install dependencies
```bash
composer install
npm install
```

### 3. Environment
```bash
cp .env.example .env
php artisan key:generate
```

Configure your database in .env.

### 4. Run with Sail (Docker)
```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed // creates 1 org, 1 admin user, 1 tech user, 3 normal users
./vendor/bin/sail npm run dev
```

### 5. Default logins:
Go to http://localhost/login
```bash
email: tech@test.com
password: password

admin: admin@test.com
password: password

+ 3 normal users (see in DB)
```

### 6. Use app: 
After logged in, go to: http://localhost/tickets

### 7. Tests
```bash
./vendor/bin/sail artisan test
```
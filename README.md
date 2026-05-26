<div align="center">

![BMS](.github/readme-banner.svg)

<br/>

[![Laravel](https://img.shields.io/badge/Laravel_12-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com/)
[![PHP](https://img.shields.io/badge/PHP_8.2-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://mysql.com/)
[![Made by](https://img.shields.io/badge/Made_by_AnakCo.-0D2144?style=flat-square)](https://github.com/dvphnc)
[![Status](https://img.shields.io/badge/Status-IPT_Project-C8861A?style=flat-square)]()

</div>

---

## What is BMS?

BMS is a full-stack Barangay Management System built to digitize and streamline how **Barangay New Era** serves its residents — from filing blotter cases to issuing clearances, tracking business permits, and letting residents request documents online.

> Barangay offices still run on paper folders, physical logbooks, and manual signatures. This system replaces all of that — one place for resident records, case management, document issuance, and real-time status tracking. Built for the actual staff who use it every day.

This is our group collaboration project for **Integrative Programming and Technologies** — built as a team with every feature grounded in real barangay workflows, not textbook exercises.

---

## What We Built

> A full-stack Laravel application covering every core function of a barangay office — resident management, case tracking, document issuance, business permits, a public-facing portal, and a full reporting suite.

<details>
<summary>&nbsp;<b>Resident & Household Management</b></summary>
<br/>
A complete resident registry with photo upload, purok assignment, civil status, and household linking. Staff can search, filter, and export the full registry. Resident profiles auto-populate all document and blotter forms through a custom Select2 AJAX picker — no retyping names across forms.
</details>

<details>
<summary>&nbsp;<b>Document Issuance</b></summary>
<br/>
Issue barangay certificates (Clearance, Indigency, Residency, Business Clearance, and more) with one click. Documents auto-fill resident data including age, gender, civil status, and birthdate. Each certificate renders as a print-ready PDF that opens directly in the browser. Portal-submitted requests flow into a queue with status tracking and email notifications.
</details>

<details>
<summary>&nbsp;<b>Blotter Case Management</b></summary>
<br/>
File, track, and resolve blotter cases with full status history. Cases move through Active → Under Investigation → Mediated → Settled (or Referred to Higher Authority). Staff can attach files, log notes at each status change, and print formatted case reports. Overdue open cases are flagged automatically.
</details>

<details>
<summary>&nbsp;<b>Business Permit Tracking</b></summary>
<br/>
Issue and manage barangay business clearances with permit dates, expiry tracking, and OR numbers. The system flags overdue and expiring-soon permits on the dashboard and sends visual alerts. Staff can filter by expiry status with one click.
</details>

<details>
<summary>&nbsp;<b>Resident Portal</b></summary>
<br/>
A public-facing portal where residents submit document requests, business permit applications, and blotter reports without visiting the office. Each submission gets a reference number. Residents track real-time status updates using just their reference number — a live stepper with 15-second auto-refresh, no login required.
</details>

<details>
<summary>&nbsp;<b>Appointments & Queue Management</b></summary>
<br/>
All portal submissions surface in a dedicated Appointments page organized into three tabs: Document Requests, Blotter Reports, and Business Permits. Staff review submissions, update statuses, and push items into their respective modules — all without leaving the appointments view.
</details>

<details>
<summary>&nbsp;<b>Officials, Staff & Committees</b></summary>
<br/>
Manage barangay officials and staff with role titles, term dates, and photos. Print digital ID cards for each official. Eight committee tabs (Peace & Order, Health, Education, and more) each have their own member list, medicine inventory, and committee-specific data.
</details>

<details>
<summary>&nbsp;<b>Reports & Analytics</b></summary>
<br/>
Charts and summary tables across all modules — resident demographics, document issuance trends, blotter case distribution, permit status breakdowns, and activity logs. Every table exports to PDF and Excel. The dashboard shows birthday alerts, expiring permit warnings, and a live appointment badge.
</details>

<details>
<summary>&nbsp;<b>System Administration</b></summary>
<br/>
Role-based access control (Admin, Secretary, Committee), full activity logging with weekly/monthly charts, user management with email verification, database backup and restore, and global AJAX search across residents, documents, cases, and permits — all from the topbar.
</details>

---

## Built With

```
Laravel 12        Backend framework, routing, auth, queue, mail
PHP 8.2           Server-side logic
MySQL             Relational database (via Laragon locally)
Laravel Breeze    Authentication scaffolding
Blade             Server-rendered templating engine
Vanilla CSS/JS    Frontend styling and interactivity — no framework
Yajra DataTables  Server-side paginated and searchable tables
DomPDF            PDF generation for certificates and reports
PhpSpreadsheet    Excel export across all modules
Simple QrCode     QR codes on business permits for public verification
Font Awesome 6    Icon system throughout the UI
Poppins           Primary typeface (Google Fonts)
```

---

## My Role

I served as **Scrum Master and Full-Stack Developer** on this group project for Integrative Programming and Technologies — leading sprints, managing the backlog, and owning the end-to-end technical implementation.

**As Scrum Master:**
- Facilitated sprint planning, daily stand-ups, and retrospectives across the team
- Maintained and prioritized the product backlog, mapping barangay workflows to deliverable features
- Tracked progress and kept the team aligned on project deadlines

**As Full-Stack Developer:**
- Designed the full database schema — 20+ tables covering residents, documents, blotter, permits, committees, activity logs, and portal submissions
- Built every controller, model, migration, and route from scratch
- Implemented server-side DataTables across all modules with filtering, searching, and multi-format export
- Wrote the full PDF certificate rendering pipeline with dynamic resident data mapping (age, gender, civil status, birthdate)
- Built real-time portal tracking with 15-second polling, multi-step progress steppers, and queued email notifications
- Shipped role-based access control, a full activity log, and one-click database backup/restore

**As UI/UX Designer:**
- Designed the entire interface from scratch — no UI kit, no component library
- Built a consistent design system: navy `#0D2144`, gold `#C8861A`, Poppins, 10px border radius
- Designed the resident portal as a distinct public-facing experience separate from the staff admin panel
- Iterated on every form, table, modal, and print layout to match real barangay workflows
- Added contextual UX: overdue case badges, expiry warnings, live portal status badges, and CSS tooltip labels

---

## Run It Locally

> Requires [Laragon](https://laragon.org/) (or any local PHP 8.2 + MySQL environment).

```bash
git clone https://github.com/dvphnc/anakco-bms-newera.git
cd anakco-bms-newera
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Configure your database in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=anakco_bms
DB_USERNAME=root
DB_PASSWORD=
```

Then run migrations and seeders:

```bash
php artisan migrate --seed
npm run dev
```

Open a second terminal and start the server:

```bash
php artisan serve
```

Open `http://localhost:8000` — you're in.

**Default credentials:**

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@bms.gov.ph | Admin@12345 |

---

## How It's Organized

```
anakco-bms-newera/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ResidentController.php          resident CRUD and photo upload
│   │   │   ├── DocumentController.php          certificate issuance and PDF
│   │   │   ├── BlotterController.php           case filing and status tracking
│   │   │   ├── BusinessController.php          permit issuance and expiry
│   │   │   ├── AppointmentController.php       portal queue management
│   │   │   ├── ResidentPortalController.php    public portal and live tracker
│   │   │   ├── ReportController.php            analytics and exports
│   │   │   └── DashboardController.php         overview, alerts, charts
│   │   └── Middleware/
│   │       └── CheckRole.php                   role-based access control
│   ├── Models/                                 Eloquent models (20+ tables)
│   ├── Mail/
│   │   └── PortalStatusUpdated.php             portal email notifications
│   └── Traits/
│       └── LogsActivity.php                    activity logging trait
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php                   main layout — CSS, sidebar, topbar
│       ├── partials/                            sidebar, topbar, alerts
│       ├── dashboard.blade.php                 overview and alerts
│       ├── residents/                          registry and profiles
│       ├── documents/                          issuance and certificates
│       ├── blotter/                            case management and print report
│       ├── businesses/                         permit tracking
│       ├── portal/                             public request form and tracker
│       ├── officials/                          staff directory and ID cards
│       ├── committees/                         8 committee tabs
│       ├── reports/                            analytics and export views
│       └── components/                         reusable Blade components
│
├── database/
│   ├── migrations/                             full schema definitions
│   └── seeders/                               sample data for all modules
│
└── routes/
    └── web.php                                all routes with role middleware
```

---

## Timeline

```
January 2025     Project kickoff — requirements gathering, barangay workflow research
February 2025    Database schema design, design system, wireframes
March 2025       Core modules — residents, households, documents, blotter
April 2025       Business permits, officials, committees, reports, resident portal
May 2025         Appointments queue, portal tracker, email notifications, UX polish
May 26, 2025     Final submission — all features complete
```

<img src=".github/divider.svg" width="100%"/>

<div align="center">

![wave](.github/readme-wave.svg)

<sub>© 2025 Barangay New Era BMS · IPT Group Project · New Era University Philippines<br/>AnakCo. · Joana Daphne Sy — Scrum Master & Full-Stack Developer · All Rights Reserved.</sub>

</div>

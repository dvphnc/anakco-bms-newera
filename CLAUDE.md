# Barangay New Era Management System (BMS)

## Project Overview
A full-stack Barangay Management System for **Barangay New Era, District VI, Quezon City**.
Built as a portfolio/capstone project for barangay staff to manage residents, documents, blotter cases, business permits, and more.

## Tech Stack
- **Framework:** Laravel 12.53.0
- **Auth:** Laravel Breeze (Blade)
- **Database:** MySQL (via Laragon)
- **Frontend:** Blade templates, vanilla CSS, vanilla JS
- **Tables:** Yajra DataTables (server-side)
- **PDF:** barryvdh/laravel-dompdf
- **Excel:** phpoffice/phpspreadsheet
- **Search:** Custom Select2 AJAX resident picker
- **Local URL:** http://anakco_bms.test
- **Environment:** Laragon on Windows

## Design System
- **Primary color:** Navy `#0D2144`
- **Accent color:** Gold `#C8861A`
- **Font:** Poppins (Google Fonts)
- **Icons:** Font Awesome 6 Free
- **Border radius:** `--radius: 10px`, `--radius-sm: 6px`, `--radius-lg: 14px`
- **Layout:** Fixed sidebar (270px) + topbar (64px) + scrollable main content
- **Theme file:** `resources/views/layouts/app.blade.php`

## Credentials
- **Admin:** admin@bms.gov.ph / Admin@12345
- **Roles:** Admin, Secretary, Committee

## Punong Barangay
- **Name:** Robert S. Romano

## Directory Structure
```
app/
  Http/
    Controllers/         # All controllers
    Middleware/
      CheckRole.php      # Role-based access control
  Models/               # Eloquent models
  Traits/
    LogsActivity.php    # Activity logging trait

resources/
  views/
    layouts/
      app.blade.php     # Main layout with CSS, sidebar, topbar
    partials/
      _sidebar.blade.php
      _topbar.blade.php  # Global search bar
      _alerts.blade.php
    errors/             # Custom 404, 403, 500, 419 pages
    dashboard.blade.php
    residents/
    households/
    documents/
    blotter/
    businesses/
    officials/
    committees/
    reports/
    activity-log/
    backup/
    users/
    components/
      resident-select.blade.php  # Reusable Select2 component

routes/
  web.php              # All routes with role middleware
```

## Roles & Permissions
| Role | Access |
|------|--------|
| Admin | Everything |
| Secretary | Residents, Households, Documents, Blotter, Businesses, Officials, Reports, Activity Log |
| Committee | Dashboard, Committees only |

## Middleware
- `auth` — must be logged in
- `verified` — email must be verified
- `role:Admin,Secretary` — role check via `CheckRole` middleware

## Completed Features
- ✅ Resident CRUD with photo upload
- ✅ Household CRUD
- ✅ Document issuance with printable certificates (auto-fills resident data)
- ✅ Blotter case management with file attachments
- ✅ Business permits with expiry tracking & alerts
- ✅ Officials & Staff management with digital ID card printing
- ✅ Committee-specific tabs (8 committees)
- ✅ Reports & Analytics with charts
- ✅ PDF & Excel export for all modules
- ✅ Database backup & restore
- ✅ Global search (topbar, AJAX, role-filtered)
- ✅ Select2 resident picker on all forms
- ✅ Activity log with weekly/monthly charts
- ✅ Custom error pages (404, 403, 500, 419)
- ✅ User management with verify/unverify
- ✅ Role/permission audit
- ✅ Birthday & expiring permit alerts on dashboard
- ✅ Dynamic data mapping on certificates (age, gender, civil status, birthdate)
- ✅ Dashboard redesign — tabbed (Overview / Analytics / Appointments), stat cards, charts, quick access grid
- ✅ Resident Portal — public request form, confirmation page, Axios-powered appointment tracker
- ✅ Real-time medicine inventory (Health committee) — Axios stock adjust & delete, live badge updates
- ✅ Appointments admin page — Axios PATCH status update modal, delete with confirm dialog
- ✅ Global UX polish — font smoothing, focus-visible rings, CSS tooltip labels, custom validation messages, overdue blotter badges

## Key Conventions
- All controllers use `LogsActivity` trait
- All index pages use Yajra DataTables with server-side processing
- Forms use `@error()` directives for inline validation
- Flash messages via `session('success')`, `session('error')`, `session('warning')`
- Select2 resident search uses `/select2/residents` AJAX endpoint
- PDF certificates use `->stream()` to open in browser tab
- All dates formatted with Carbon

## Current Task
All features complete. Project is in final polish / capstone-ready state.

## Notes
- `layouts/app.blade.php` has Select2 CDN loaded globally (jQuery 3.7.1 + Select2 4.0.13)
- `_sidebar.blade.php` must only appear ONCE (was duplicated before — fixed)
- `_alerts.blade.php` uses `@if(isset($errors) && $errors->any())` — not `$errors->any()` alone
- `.main-content` div is the scrollable container (not `window`)
- Chart.js loaded per-page via `@push('scripts')`
- DataTables CSS/JS loaded per-page via `@push('scripts')`

# Barangay New Era Management System (BMS)
**Barangay New Era, District VI, Quezon City**
Built with Laravel 12 · MySQL · Blade Templates · Vanilla JS

---

## Overview

The Barangay New Era Management System is a full-stack web application designed to digitize and streamline the day-to-day administrative operations of a barangay. It replaces manual, paper-based processes with a centralized, role-secured digital system accessible to barangay staff.

---

## Features

### 1. Dashboard
The central hub for barangay staff. Displays real-time stat cards for residents, households, documents, blotter cases, and business permits. Features three tabs:
- **Overview** — key metrics and birthday alerts for today's residents
- **Analytics** — charts for resident demographics, document types, and monthly activity
- **Appointments** — live tracker of portal-submitted document requests, business permit applications, and blotter reports

Includes a **Quick Access Grid** for one-click navigation to common tasks and alert banners for expiring business permits.

---

### 2. Resident Management
Full CRUD for resident records. Each resident profile includes personal details, household assignment, civil status, and an optional photo upload. Supports a **Select2 AJAX search** for fast resident lookups across all forms in the system.

---

### 3. Household Management
Manages household records linked to residents. Tracks the number of household members and address information, giving staff a family-level view alongside individual resident profiles.

---

### 4. Document Issuance
Handles the issuance of official barangay documents such as Barangay Clearance, Certificate of Residency, Indigency Certificates, and more. Key features:
- Auto-fills resident data (name, age, gender, civil status, birthdate) onto printable PDF certificates
- Tracks document status (Pending → Released)
- Supports PDF streaming directly in the browser for signing and printing
- Integrates with the Resident Portal for online document requests

---

### 5. Blotter Case Management
Records and tracks barangay blotter incidents. Each case captures complainant and respondent details, incident narrative, file attachments, and resolution notes. Status tracking moves cases from Pending through Active, Under Investigation, Mediated, Settled, or Referred to Higher Authority. Supports PDF and Excel export.

---

### 6. Business Permits
Manages barangay business permit applications with full lifecycle tracking:
- Permit issuance with permit number, permit date, and expiry date
- Color-coded expiry indicators: **Valid** (green), **Expiring Soon** (within 30 days, yellow), **Overdue** (red)
- Dashboard alerts for expiring and overdue permits
- Status management (Active, Expired, Suspended, Cancelled)
- Integrates with the Resident Portal for online business permit applications

---

### 7. Officials & Staff
Maintains a roster of elected and appointed barangay officials and staff members. Each record includes position, term, contact details, and a profile photo. Features a **printable digital ID card** that can be generated and exported as a PDF directly from the system.

---

### 8. Committees
Manages the barangay's 8 standing committees (Peace and Order, Health, Education, Environment, Finance, Women & Family, Senior Citizens, Youth). Each committee has its own tab with member listings and committee-specific records. The **Health Committee** includes a real-time medicine inventory with live stock adjustment and deletion via Axios — no page reload required.

---

### 9. Reports & Analytics
Generates summary reports across all modules with visual charts powered by Chart.js. Reports include resident demographics breakdowns, document issuance history, blotter case summaries, and business permit statistics. All reports are exportable as **PDF** (via DomPDF) or **Excel** (via PhpSpreadsheet).

---

### 10. Activity Log
Tracks every create, update, and delete action performed in the system. Logs the acting user, affected record, and a before/after snapshot of changed data. Includes weekly and monthly activity charts to visualize system usage over time.

---

### 11. Database Backup & Restore
Allows authorized administrators to create and download a full MySQL database backup directly from the admin panel. Restore functionality is also available to roll back data when needed — no command-line access required.

---

### 12. Resident Portal (Public-Facing)
A public web portal that allows barangay residents to submit requests without visiting the barangay hall:
- **Request a Document** — submit a document request with preferred pick-up date
- **Apply for a Business Permit** — submit a business permit application online
- **Report an Incident** — file a blotter report online
- **Track an Appointment** — look up the real-time status of any submitted request using a reference number
- No login required. Personal info is saved to the browser for convenience on return visits.

Staff process portal submissions from the **Appointments** admin page, where they can issue documents, activate blotter cases, issue business permits, and update statuses — all with instant sidebar badge notifications.

---

### 13. User Management
Controls who can access the staff-side system. Administrators can create accounts, assign roles (Admin, Secretary, Committee), and verify or unverify user email addresses. Role-based access control restricts each user to only the modules relevant to their position.

| Role | Access |
|---|---|
| Admin | Full access to all modules |
| Secretary | Residents, Households, Documents, Blotter, Businesses, Officials, Reports, Activity Log |
| Committee | Dashboard and Committees only |

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 |
| Auth | Laravel Breeze |
| Database | MySQL (Laragon) |
| Frontend | Blade, Vanilla CSS, Vanilla JS |
| Tables | Yajra DataTables (server-side) |
| PDF Export | barryvdh/laravel-dompdf |
| Excel Export | phpoffice/phpspreadsheet |
| Charts | Chart.js |
| Search/Select | Select2 (AJAX) |
| HTTP Client | Axios |

---

## Punong Barangay
**Robert S. Romano**
Barangay New Era, District VI, Quezon City

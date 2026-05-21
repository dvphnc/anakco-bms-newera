# Barangay New Era Management System (BMS)
**Barangay New Era, District VI, Quezon City**
Built with Laravel 12 · MySQL · Blade Templates · Vanilla JS

---

## Overview

The Barangay New Era Management System is a full-stack web application designed to digitize and streamline the day-to-day administrative operations of a barangay. It replaces manual, paper-based processes with a centralized, role-secured digital system accessible to barangay staff and residents.

---

## Features

### 1. Dashboard
The central hub for barangay staff. Displays real-time stat cards for residents, households, documents, blotter cases, and business permits. Features three tabs:
- **Overview** — key metrics, birthday alerts for today's residents, and a Quick Access Grid for one-click navigation to common tasks
- **Analytics** — Chart.js charts for resident demographics, document type breakdown, and monthly activity trends
- **Appointments** — live tracker for all portal-submitted requests (documents, business permits, blotter reports) with action buttons to process each one directly

Additional details:
- Alert banners on the Overview tab surface business permits expiring within 30 days and those already overdue
- Birthday section shows residents celebrating today with their age
- Stat cards link directly to the relevant filtered module view
- The Quick Access Grid groups the most frequent staff actions into a visual shortcut panel

---

### 2. Resident Management
Full CRUD for resident records with the following details:
- Profile fields include full name, birthdate (age auto-calculated), gender, civil status, address, contact number, and residency status
- Optional profile photo upload with image preview
- Each resident can be linked to a household
- A **Select2 AJAX-powered resident picker** is used across all forms in the system — type a name and matching residents appear instantly without a page reload
- Resident records are listed in a **server-side DataTables** table with search, sort, and pagination
- Records are sorted by most recently updated so newly added or modified residents appear at the top

---

### 3. Household Management
Manages household records linked to individual residents:
- Tracks household address, number of members, and head of household
- Linked residents are visible from the household detail page
- Gives staff a family-level view alongside individual resident profiles

---

### 4. Document Issuance
Handles the full lifecycle of official barangay documents (Barangay Clearance, Certificate of Residency, Indigency Certificate, and more):
- Staff can issue documents and link them to a resident record
- Resident data (full name, age, gender, civil status, birthdate) is **dynamically mapped** onto printable PDF certificate templates — no manual typing on the certificate
- PDF certificates **stream directly in the browser** (opens in a new tab for signing and printing)
- Auto-generated document numbers follow the format `DOC-YYYY-XXXXX`, using a MAX-based sequence to prevent duplicate numbers even when records have been deleted
- Document status tracks the lifecycle: **Pending → Released**
- Table is sorted by most recently updated so newly issued documents appear at the top
- Integrates with the Resident Portal: online document requests submitted by residents flow directly into this module

---

### 5. Blotter Case Management
Records and tracks barangay blotter incidents end-to-end:
- Captures complainant details, respondent details, incident date, location, narrative, and resolution notes
- Supports **file attachments** (photos, documents) per case
- Full status workflow: Pending → Active → Under Investigation → Mediated → Settled / Closed / Referred to Higher Authority
- **Overdue badges** visually flag cases that have been open too long
- Table is sorted by most recently updated so active cases surface at the top
- PDF and Excel export available
- Portal-submitted blotter reports are marked with a **"Portal" source badge** and processed from the Appointments page

---

### 6. Business Permits
Manages the full lifecycle of barangay business permit applications:
- Permit records include permit number (auto-generated as `BP-YYYY-XXXXX`), business name, type, owner, permit date, and expiry date
- **Color-coded expiry indicators** per row: Valid (green), Expiring Soon within 30 days (yellow/orange), Overdue (red)
- Dashboard alert banners list businesses expiring soon and those already overdue
- Quick status update modal lets staff change a permit's status (Active, Expired, Suspended, Cancelled) with optional notes — no page navigation required
- Email notification is sent to the applicant when their portal-submitted permit status changes (if an email was provided)
- Table is sorted by most recently updated
- Portal-submitted applications are marked with a **"Portal" source badge** and processed from the Appointments page

---

### 7. Officials & Staff
Maintains a complete roster of elected and appointed barangay officials and staff:
- Records include full name, position, term dates, contact details, and a profile photo
- Features a **printable digital ID card** — generates a formatted PDF ID card directly from the record, ready for printing
- Sortable and searchable via DataTables

---

### 8. Committees
Manages all 8 standing barangay committees:
- Peace and Order, Health, Education, Environment, Finance, Women & Family, Senior Citizens, Youth
- Each committee has its own dedicated tab with member listings
- The **Health Committee** includes a real-time **medicine/supply inventory**:
  - Staff can adjust stock quantities directly from the table using + / − buttons
  - Changes save instantly via Axios (no page reload)
  - Low-stock badges update live as quantities change
  - Items can be deleted with a confirmation prompt — table updates without reload

---

### 9. Reports & Analytics
Generates visual summary reports across all modules:
- Charts for resident demographics (age groups, gender, civil status), document issuance trends, blotter status breakdown, and business permit statistics
- All reports are exportable as **PDF** (opens in browser via DomPDF) or **Excel** (downloads via PhpSpreadsheet)
- Export buttons are present on every module's index page

---

### 10. Activity Log
Full audit trail for all staff actions in the system:
- Logs every create, update, and delete operation
- Each entry records: the acting user, the affected record type and ID, and a before/after snapshot of changed fields
- Weekly and monthly **activity charts** show system usage trends over time
- Accessible to Admin and Secretary roles

---

### 11. Database Backup & Restore
Admin-only tool for data safety:
- Generate and download a full MySQL database backup as a `.sql` file directly from the browser
- Restore from a previously downloaded backup file — no command-line or server access required
- Backup file is timestamped for easy versioning

---

### 12. Resident Portal (Public-Facing)
A public-facing web portal at a separate route allowing residents to transact online without visiting the barangay hall:

**Submission forms:**
- **Request a Document** — choose document type, preferred pick-up date, contact details, and purpose. A step indicator (Fill Form → Confirmation → Claim Document) guides the resident through the process.
- **Apply for a Business Permit** — submit basic business and owner information for barangay clearance
- **Report an Incident** — file a blotter report with incident details

**Appointment Tracker:**
- Residents enter their reference number to check the real-time status of any submitted request
- Status updates made by staff on the admin side are immediately reflected here

**Portal UX details:**
- No login required — fully public
- The portal form **saves name, contact number, and email to localStorage** so returning residents don't have to retype personal info
- The `?type=` URL parameter pre-selects a document type when linking from another page
- Form submissions use **Axios** with inline field-level validation errors (no page reload)
- A spinner replaces the submit button icon while the request is processing
- Confirmation page shows the reference number and a summary of what was submitted
- The portal has its own layout with a **navbar, mobile drawer menu, and command palette** (see UX section below)

**Admin-side Appointments page:**
- Staff see all portal submissions in one place, split into three tabs: Document Requests, Business Permit Applications, Blotter Reports
- Actions available per row: Issue Document, Issue Permit, Activate Blotter Case, Update Status
- All actions use **Axios modals** — no page navigation needed
- **Sidebar badge notifications** update in real time whenever a new portal submission is pending, without requiring a page reload

---

### 13. User Management
Controls system access for all staff accounts:
- Administrators can create new user accounts and assign a role
- **Role-based access control** restricts navigation and routes per role:

| Role | Access |
|---|---|
| Admin | Full access to all modules |
| Secretary | Residents, Households, Documents, Blotter, Businesses, Officials, Reports, Activity Log |
| Committee | Dashboard and Committees only |

- Administrators can **verify or unverify** a user's email address directly from the user list
- Unverified users cannot log in until their email is verified

---

## UX & Polish Details

These smaller details were implemented to improve usability, consistency, and presentation quality throughout the system.

### Command Palette (Ctrl+K)
- Press **Ctrl+K** from anywhere in the admin system to open a command palette overlay
- Type to search and navigate to any module, or pick from a list of quick actions
- The portal has its own command palette with portal-specific actions (Request a Document, Track Appointment, etc.)
- Keyboard shortcut displayed as **Ctrl+K** in the footer of the palette

### Global Search (Topbar)
- A search bar in the topbar performs a **live AJAX search** across residents, documents, blotter cases, businesses, and officials
- Results are **role-filtered** — staff only see results for modules they have access to
- Clicking a result navigates directly to that record

### Sidebar Badge Notifications
- The sidebar shows live **badge counters** on Business Permits and Blotter menu items when there are pending portal submissions
- Badges update **in real time** without page reload — using a 30-second background poll plus an instant refresh triggered whenever a staff action is taken
- The badge also refreshes automatically when the user **switches back to the browser tab** (using the Page Visibility API)
- A **flash animation** plays on the badge when the count changes

### Tooltips
- All icon-only action buttons (view, edit, delete, status update) display **Tippy.js tooltips** on hover
- Tooltips use a custom BMS theme (navy background) consistent with the design system
- Tooltips are re-initialized after every DataTables redraw so they work on freshly loaded rows

### Axios-Powered Modals (No Page Reload)
- Status updates, permit issuance, and blotter activation all open modals and submit via Axios PATCH/POST
- The page never reloads — the DataTables row updates in place after a successful action
- Submit buttons show a **loading spinner** while the request is in flight and restore to their original state on error

### Toast Notifications
- A global `portalToast()` function displays lightweight toast messages in the bottom-right corner for success and error feedback
- Used on the portal and admin Appointments page for non-modal feedback

### Form Validation
- All forms use **custom validation messages** — errors appear inline under the relevant field in red
- On 422 responses (server-side validation), an error banner appears at the top of the form and the page scrolls to the first invalid field
- Select2 dropdowns receive the same red border highlight as text inputs when they fail validation

### Sorting — Most Recent First
- Document, Blotter, and Business Permit tables are all sorted by **most recently updated** by default
- This uses hidden DataTables columns mapped to the `updated_at` timestamp so newly issued or activated records always appear at the top

### Custom Error Pages
- Custom-designed pages for HTTP errors: **404** (Not Found), **403** (Forbidden), **500** (Server Error), and **419** (Session Expired)
- Each page matches the BMS design system and includes a button to return to the dashboard

### Favicon
- The **BNE Barangay Seal** is used as the browser tab favicon across both the admin system and the public portal
- The favicon is a multi-resolution ICO file (16×16, 32×32, 48×48) generated from the official seal image

### Accessibility & Visual Polish
- **Focus-visible rings** on all interactive elements for keyboard navigation
- **Font smoothing** applied globally for crisper text rendering
- Consistent use of the design system: Navy `#0D2144`, Gold `#C8861A`, Poppins font, Font Awesome 6 Free icons
- Mobile-responsive layouts: form rows collapse to single column, sidebar converts to a drawer on small screens

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
| Tooltips | Tippy.js |
| HTTP Client | Axios |

---

## Punong Barangay
**Robert S. Romano**
Barangay New Era, District VI, Quezon City

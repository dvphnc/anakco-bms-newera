<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\BlotterController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CommitteeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\OfficialController;
use App\Http\Controllers\PurokController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\ResidentStatusController;
use App\Http\Controllers\ResidentTransactionController;
use App\Http\Controllers\AssistanceProgramController;
use App\Http\Controllers\PabahayController;
use App\Http\Controllers\PabahayUnitController;
use App\Http\Controllers\ReligionController;
use App\Http\Controllers\ResidentPortalController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\RecycleBinController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Select2Controller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifyController;
use Illuminate\Support\Facades\Route;

// -------------------------------------------------------
// Public Verification Routes — no login required
// -------------------------------------------------------
Route::get('/verify/business/{permitNumber}', [VerifyController::class, 'business'])
    ->name('verify.business');

// -------------------------------------------------------
// Resident Portal — public, no login required
// -------------------------------------------------------
Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('/',                         [ResidentPortalController::class, 'index'])->name('index');
    Route::get('/about',                    [ResidentPortalController::class, 'about'])->name('about');
    // Document request
    Route::get('/request',                  [ResidentPortalController::class, 'create'])->name('request');
    Route::post('/request',                 [ResidentPortalController::class, 'store'])->name('store');
    Route::get('/confirmation/{number}',    [ResidentPortalController::class, 'confirmation'])->name('confirmation');
    // Blotter request
    Route::get('/blotter',                  [ResidentPortalController::class, 'blotterForm'])->name('blotter');
    Route::post('/blotter',                 [ResidentPortalController::class, 'storeBlotter'])->name('blotter.store');
    // Business permit request
    Route::get('/business',                 [ResidentPortalController::class, 'businessForm'])->name('business');
    Route::post('/business',                [ResidentPortalController::class, 'storeBusiness'])->name('business.store');
    // Generic submitted confirmation
    Route::get('/submitted/{type}/{number}',[ResidentPortalController::class, 'submitted'])->name('submitted');
    // Track
    Route::get('/track',                    [ResidentPortalController::class, 'trackForm'])->name('track');
    Route::post('/track',                   [ResidentPortalController::class, 'track'])->name('track.post');
    Route::get('/track/lookup',             [ResidentPortalController::class, 'trackLookup'])->name('track.lookup');
    // My Submissions
    Route::get('/submissions',              [ResidentPortalController::class, 'submissions'])->name('submissions');
});

// -------------------------------------------------------
// GET Logout — no CSRF token needed (safe for intranet).
// Allows bmsLogout() to do a simple window.location navigation
// instead of an Axios POST, which avoids all CSRF/redirect issues.
// -------------------------------------------------------
Route::get('/signout', function () {
    \Illuminate\Support\Facades\Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('signout');

// -------------------------------------------------------
// Guest Routes — handled by Breeze (keep this line)
// -------------------------------------------------------
require __DIR__.'/auth.php';

// -------------------------------------------------------
// Authenticated Routes — all require login
// -------------------------------------------------------
Route::middleware(['auth', 'verified'])->group(function () {

    // ---------------------------------------------------
    // Dashboard — all roles
    // ---------------------------------------------------
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    // ---------------------------------------------------
    // Residents — Admin + Secretary only
    // ---------------------------------------------------
    Route::resource('residents', ResidentController::class)
        ->middlewareFor(['index', 'show'], 'permission:residents.view')
        ->middlewareFor(['create', 'store'], 'permission:residents.create')
        ->middlewareFor(['edit', 'update'], 'permission:residents.edit')
        ->middlewareFor('destroy', 'permission:residents.archive');
    Route::get('residents/{resident}/quick-view', [ResidentController::class, 'quickView'])
        ->name('residents.quick-view')
        ->middleware('permission:residents.view');
    // Transaction history & double-claim prevention (Task 1.1)
    Route::get('residents/{resident}/transactions', [ResidentTransactionController::class, 'index'])
        ->name('residents.transactions.index')
        ->middleware('permission:transactions.view');
    Route::post('residents/{resident}/transactions', [ResidentTransactionController::class, 'store'])
        ->name('residents.transactions.store')
        ->middleware('permission:transactions.record');
    Route::get('residents/{resident}/eligibility/{program}', [ResidentTransactionController::class, 'eligibility'])
        ->name('residents.eligibility')
        ->middleware('permission:transactions.view');
    Route::patch('transactions/{transaction}/void', [ResidentTransactionController::class, 'void'])
        ->name('transactions.void')
        ->middleware('permission:transactions.void');
    Route::resource('programs', AssistanceProgramController::class)->except('show')
        ->middlewareFor(['index', 'show'], 'permission:programs.view')
        ->middlewareFor(['create', 'store'], 'permission:programs.manage')
        ->middlewareFor(['edit', 'update'], 'permission:programs.manage')
        ->middlewareFor('destroy', 'permission:programs.archive');

    // Religion list and Pabahay (ministers' housing): needs "Manage the religion list and Pabahay units".
    // Kept on the gate so a role without it gets a plain 403 (the gate reads the permission, Part 3.2).
    Route::middleware('can:manage-religion-data')->group(function () {
        Route::resource('religions', ReligionController::class)->only(['index', 'store', 'update']);
        Route::resource('pabahays', PabahayController::class)->except('destroy');
        Route::post('pabahays/{pabahay}/units', [PabahayUnitController::class, 'store'])->name('pabahays.units.store');
        Route::patch('pabahay-units/{unit}', [PabahayUnitController::class, 'update'])->name('pabahay-units.update');
    });

    // Life status (Alive / Deceased / Moved Out) — dated, logged; replaces the old one-click toggle
    Route::patch('residents/{resident}/status', [ResidentStatusController::class, 'update'])
        ->name('residents.status.update')
        ->middleware('permission:residents.status');

    // ---------------------------------------------------
    // Households — Admin + Secretary only
    // ---------------------------------------------------
    // Declared before the resource so "match" isn't read as a household id
    Route::get('households/match', [HouseholdController::class, 'match'])
        ->name('households.match')
        ->middleware('permission:residents.create,residents.edit,households.view');
    Route::patch('households/{household}/head', [HouseholdController::class, 'setHead'])
        ->name('households.head')
        ->middleware('permission:households.edit');
    Route::resource('households', HouseholdController::class)
        ->middlewareFor(['index', 'show'], 'permission:households.view')
        ->middlewareFor(['create', 'store'], 'permission:households.create')
        ->middlewareFor(['edit', 'update'], 'permission:households.edit')
        ->middlewareFor('destroy', 'permission:households.archive');

    // ---------------------------------------------------
    // Puroks — Admin + Secretary only
    // ---------------------------------------------------
    Route::get('puroks', [PurokController::class, 'index'])
        ->name('puroks.index')
        ->middleware('permission:puroks.view');
    Route::get('puroks/{purok}/edit', [PurokController::class, 'edit'])
        ->name('puroks.edit')
        ->middleware('permission:puroks.edit');
    Route::put('puroks/{purok}', [PurokController::class, 'update'])
        ->name('puroks.update')
        ->middleware('permission:puroks.edit');

    // ---------------------------------------------------
    // Documents — Admin + Secretary only
    // ---------------------------------------------------
    Route::resource('documents', DocumentController::class)
        ->middlewareFor(['index', 'show'], 'permission:documents.view')
        ->middlewareFor(['create', 'store'], 'permission:documents.create')
        ->middlewareFor(['edit', 'update'], 'permission:documents.edit')
        ->middlewareFor('destroy', 'permission:documents.archive');
    Route::patch('documents/{document}/status', [DocumentController::class, 'quickStatus'])
        ->name('documents.quickStatus')->middleware('permission:documents.edit');
    Route::get('documents/generate-or-number', [DocumentController::class, 'generateOrPreview'])
        ->name('documents.generateOrNumber')->middleware('permission:documents.create,documents.edit');

    // ---------------------------------------------------
    // Blotter Cases — Admin + Secretary only
    // ---------------------------------------------------
    Route::resource('blotter', BlotterController::class)
        ->middlewareFor(['index', 'show'], 'permission:blotter.view')
        ->middlewareFor(['create', 'store'], 'permission:blotter.create')
        ->middlewareFor(['edit', 'update'], 'permission:blotter.edit')
        ->middlewareFor('destroy', 'permission:blotter.archive');
    Route::patch('blotter/{blotter}/status', [BlotterController::class, 'quickStatus'])
        ->name('blotter.quickStatus')->middleware('permission:blotter.edit');

    // ---------------------------------------------------
    // Business Permits — Admin + Secretary only
    // ---------------------------------------------------
    Route::resource('businesses', BusinessController::class)
        ->middlewareFor(['index', 'show'], 'permission:businesses.view')
        ->middlewareFor(['create', 'store'], 'permission:businesses.create')
        ->middlewareFor(['edit', 'update'], 'permission:businesses.edit')
        ->middlewareFor('destroy', 'permission:businesses.archive');
    Route::patch('businesses/{business}/status', [BusinessController::class, 'quickStatus'])
        ->name('businesses.quickStatus')->middleware('permission:businesses.edit');

    // ---------------------------------------------------
    // Officials — Admin + Secretary only
    // ---------------------------------------------------
    Route::resource('officials', OfficialController::class)
        ->middlewareFor(['index', 'show'], 'permission:officials.view')
        ->middlewareFor(['create', 'store'], 'permission:officials.create')
        ->middlewareFor(['edit', 'update'], 'permission:officials.edit')
        ->middlewareFor('destroy', 'permission:officials.archive');
    Route::patch('officials/{official}/toggle-status', [OfficialController::class, 'toggleStatus'])
        ->name('officials.toggle-status')
        ->middleware('permission:officials.edit');

    // ---------------------------------------------------
    // Committees — all roles (Admin, Secretary, Committee)
    // ---------------------------------------------------
    Route::get('committees/{slug}', [CommitteeController::class, 'show'])
        ->name('committees.show')->middleware('permission:committees.view');

    Route::post('committees/{slug}/records', [CommitteeController::class, 'storeRecord'])
        ->name('committees.storeRecord')->middleware('permission:committees.manage');

    Route::post('committees/{slug}/activities', [CommitteeController::class, 'storeActivity'])
        ->name('committees.storeActivity')->middleware('permission:committees.manage');

    Route::post('committees/{slug}/attendance', [CommitteeController::class, 'storeAttendance'])
        ->name('committees.storeAttendance')->middleware('permission:committees.manage');

    Route::post('committees/{slug}/inventory', [CommitteeController::class, 'storeInventory'])
        ->name('committees.storeInventory')->middleware('permission:committees.manage');

    Route::post('committees/{slug}/specific', [CommitteeController::class, 'storeSpecific'])
        ->name('committees.storeSpecific')->middleware('permission:committees.manage');

    Route::post('committees/{slug}/partnerships', [CommitteeController::class, 'storePartnership'])
        ->name('committees.storePartnership')->middleware('permission:committees.manage');

    Route::delete('committees/{slug}/partnerships/{id}', [CommitteeController::class, 'destroyPartnership'])
        ->name('committees.destroyPartnership')->middleware('permission:committees.archive');

    Route::delete('committees/{slug}/medicine/{id}', [CommitteeController::class, 'destroyMedicine'])
        ->name('committees.destroyMedicine')->middleware('permission:committees.archive');

    Route::post('committees/{slug}/medicine/{id}/adjust', [CommitteeController::class, 'adjustMedicine'])
        ->name('committees.adjustMedicine')->middleware('permission:committees.manage');

    Route::delete('committees/{slug}/relief/{id}', [CommitteeController::class, 'destroyRelief'])
        ->name('committees.destroyRelief')->middleware('permission:committees.archive');

    // Generic tab deletes
    Route::delete('committees/{slug}/records/{id}',    [CommitteeController::class, 'destroyRecord'])    ->name('committees.destroyRecord')->middleware('permission:committees.archive');
    Route::delete('committees/{slug}/activities/{id}', [CommitteeController::class, 'destroyActivity'])  ->name('committees.destroyActivity')->middleware('permission:committees.archive');
    Route::delete('committees/{slug}/attendance/{id}', [CommitteeController::class, 'destroyAttendance'])->name('committees.destroyAttendance')->middleware('permission:committees.archive');
    Route::delete('committees/{slug}/inventory/{id}',  [CommitteeController::class, 'destroyInventory']) ->name('committees.destroyInventory')->middleware('permission:committees.archive');
    // Specific tab delete (single route, type-dispatched)
    Route::delete('committees/{slug}/specific/{type}/{id}', [CommitteeController::class, 'destroySpecificItem'])->name('committees.destroySpecificItem')->middleware('permission:committees.archive');
    // Generic tab updates
    Route::patch('committees/{slug}/records/{id}',     [CommitteeController::class, 'updateRecord'])     ->name('committees.updateRecord')->middleware('permission:committees.manage');
    Route::patch('committees/{slug}/activities/{id}',  [CommitteeController::class, 'updateActivity'])   ->name('committees.updateActivity')->middleware('permission:committees.manage');
    Route::patch('committees/{slug}/attendance/{id}',  [CommitteeController::class, 'updateAttendance']) ->name('committees.updateAttendance')->middleware('permission:committees.manage');
    Route::patch('committees/{slug}/inventory/{id}',   [CommitteeController::class, 'updateInventory'])  ->name('committees.updateInventory')->middleware('permission:committees.manage');
    Route::patch('committees/{slug}/partnerships/{id}',[CommitteeController::class, 'updatePartnership'])->name('committees.updatePartnership')->middleware('permission:committees.manage');
    // Specific tab update
    Route::patch('committees/{slug}/specific/{type}/{id}', [CommitteeController::class, 'updateSpecificItem'])->name('committees.updateSpecificItem')->middleware('permission:committees.manage');

    // ---------------------------------------------------
    // Appointments (Document Scheduling) — Admin + Secretary
    // ---------------------------------------------------
    Route::get('appointments', [AppointmentController::class, 'index'])
        ->name('appointments.index')->middleware('permission:appointments.view');
    Route::patch('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])
        ->name('appointments.updateStatus')->middleware('permission:appointments.process');
    Route::post('appointments/{appointment}/convert', [AppointmentController::class, 'convertToDocument'])
        ->name('appointments.convert')->middleware('permission:appointments.process');
    Route::get('appointments/verify-resident', [AppointmentController::class, 'verifyResident'])
        ->name('appointments.verifyResident')->middleware('permission:appointments.view');
    Route::delete('appointments/{appointment}', [AppointmentController::class, 'destroy'])
        ->name('appointments.destroy')->middleware('permission:appointments.archive');
    // Business permit portal appointments (separate DataTable on same page)
    Route::get('appointments/biz-data', [AppointmentController::class, 'bizAppointments'])
        ->name('appointments.bizData')->middleware('permission:appointments.view');
    Route::patch('appointments/biz/{business}/status', [AppointmentController::class, 'updateBizStatus'])
        ->name('appointments.bizStatus')->middleware('permission:appointments.process');
    Route::post('appointments/biz/{business}/issue', [AppointmentController::class, 'issueBizPermit'])
        ->name('appointments.bizIssue')->middleware('permission:appointments.process');
    Route::delete('appointments/biz/{business}', [AppointmentController::class, 'destroyBiz'])
        ->name('appointments.bizDestroy')->middleware('permission:appointments.archive');
    // Blotter portal appointments
    Route::get('appointments/blotter-data', [AppointmentController::class, 'blotterAppointments'])
        ->name('appointments.blotterData')->middleware('permission:appointments.view');
    Route::post('appointments/blotter/{blotterCase}/activate', [AppointmentController::class, 'activateBlotter'])
        ->name('appointments.blotterActivate')->middleware('permission:appointments.process');
    Route::patch('appointments/blotter/{blotterCase}/status', [AppointmentController::class, 'updateBlotterStatus'])
        ->name('appointments.blotterStatus')->middleware('permission:appointments.process');
    Route::delete('appointments/blotter/{blotterCase}', [AppointmentController::class, 'destroyBlotter'])
        ->name('appointments.blotterDestroy')->middleware('permission:appointments.archive');

    // ---------------------------------------------------
    // Portal Pending Count — for sidebar badge (Admin + Secretary)
    // ---------------------------------------------------
    Route::get('portal/pending-count', [AppointmentController::class, 'portalPendingCount'])
        ->name('portal.pending-count')->middleware('permission:appointments.view');
    // Server-Sent Events — live badge stream (Admin + Secretary)
    Route::get('portal/badge-stream', [AppointmentController::class, 'ssePortalBadges'])
        ->name('portal.badge-stream')->middleware('permission:appointments.view');

    // ---------------------------------------------------
    // Reports — Admin + Secretary only
    // ---------------------------------------------------
    Route::get('reports', [ReportController::class, 'analytics'])
        ->name('reports.index')
        ->middleware('permission:reports.view');
    Route::get('reports/preview', [ReportController::class, 'preview'])
        ->name('reports.preview')
        ->middleware('permission:reports.view');

    // ---------------------------------------------------
    // User Management — Admin only
    // ---------------------------------------------------
    Route::post('users/{user}/verify', [UserController::class, 'verify'])->name('users.verify')->middleware('permission:users.manage');
    Route::post('users/{user}/unverify', [UserController::class, 'unverify'])->name('users.unverify')->middleware('permission:users.manage');
    Route::patch('users/{user}/verify-toggle', [UserController::class, 'verifyToggle'])->name('users.verify-toggle')->middleware('permission:users.manage');
    Route::resource('users', UserController::class)
        ->middleware('permission:users.manage');

    // ---------------------------------------------------
    // Activity Log — Admin + Secretary
    // ---------------------------------------------------
    Route::get('activity-log', [ActivityLogController::class, 'index'])
        ->name('activity-log.index')
        ->middleware('permission:activity-log.view');

    // ---------------------------------------------------
    // Exports — Admin + Secretary
    // ---------------------------------------------------
    Route::get('export/excel/{module}', [ExportController::class, 'excel'])
        ->name('export.excel')
        ->middleware('permission:reports.export');
    Route::get('export/pdf/{module}', [ExportController::class, 'pdf'])
        ->name('export.pdf')
        ->middleware('permission:reports.export');
    Route::get('export/analytics/{format}', [ExportController::class, 'analytics'])
        ->name('export.analytics')
        ->middleware('permission:reports.export');

    // Report Generation — Admin + Secretary only
    Route::get('reports/generate', [ReportController::class, 'index'])->name('reports.generate')->middleware('permission:reports.view');
    Route::post('reports/generate', [ReportController::class, 'generate'])->name('reports.generate.post')->middleware('permission:reports.view');

    // Database Backup
    Route::get('backup', [BackupController::class, 'index'])->name('backup.index')->middleware('permission:backup.manage');
    Route::post('backup/create', [BackupController::class, 'create'])->name('backup.create')->middleware('permission:backup.manage');
    Route::get('backup/download/{filename}', [BackupController::class, 'download'])->name('backup.download')->middleware('permission:backup.manage');
    Route::post('backup/restore', [BackupController::class, 'restore'])->name('backup.restore')->middleware('permission:backup.manage');
    Route::post('backup/upload', [BackupController::class, 'upload'])->name('backup.upload')->middleware('permission:backup.manage');
    Route::delete('backup/{filename}', [BackupController::class, 'delete'])->name('backup.delete')->middleware('permission:backup.manage');

    // Roles & Permissions (Part 3.2): roles and what each may do are set from this page, not the code
    Route::middleware('permission:roles.manage')->group(function () {
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
        Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
        Route::put('roles/permissions', [RoleController::class, 'permissions'])->name('roles.permissions');
        Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    });

    // Recycle Bin (Part 3.1): archived records can be restored, never permanently deleted
    Route::get('recycle-bin', [RecycleBinController::class, 'index'])->name('recycle-bin.index')->middleware('permission:recycle-bin.manage');
    Route::patch('recycle-bin/{type}/{id}', [RecycleBinController::class, 'restore'])->name('recycle-bin.restore')->whereNumber('id')->middleware('permission:recycle-bin.manage');

    // Global Search — all authenticated users (results filtered by role in controller)
    Route::get('search', [SearchController::class, 'search'])->name('search');

    // Select2 AJAX — Admin + Secretary only
    Route::get('select2/residents', [Select2Controller::class, 'residents'])->name('select2.residents')->middleware('permission:residents.view,households.create,households.edit,documents.create,documents.edit,blotter.create,blotter.edit,businesses.create,businesses.edit,officials.create,officials.edit');

});

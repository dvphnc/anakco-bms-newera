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
use App\Http\Controllers\ResidentPortalController;
use App\Http\Controllers\SearchController;
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
});

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
        ->middleware('role:Admin,Secretary');
    Route::get('residents/{resident}/quick-view', [ResidentController::class, 'quickView'])
        ->name('residents.quick-view')
        ->middleware('role:Admin,Secretary');
    Route::patch('residents/{resident}/toggle-status', [ResidentController::class, 'toggleStatus'])
        ->name('residents.toggle-status')
        ->middleware('role:Admin,Secretary');

    // ---------------------------------------------------
    // Households — Admin + Secretary only
    // ---------------------------------------------------
    Route::resource('households', HouseholdController::class)
        ->middleware('role:Admin,Secretary');

    // ---------------------------------------------------
    // Puroks — Admin + Secretary only
    // ---------------------------------------------------
    Route::get('puroks', [PurokController::class, 'index'])
        ->name('puroks.index')
        ->middleware('role:Admin,Secretary');
    Route::get('puroks/{purok}/edit', [PurokController::class, 'edit'])
        ->name('puroks.edit')
        ->middleware('role:Admin,Secretary');
    Route::put('puroks/{purok}', [PurokController::class, 'update'])
        ->name('puroks.update')
        ->middleware('role:Admin,Secretary');

    // ---------------------------------------------------
    // Documents — Admin + Secretary only
    // ---------------------------------------------------
    Route::resource('documents', DocumentController::class)
        ->middleware('role:Admin,Secretary');
    Route::patch('documents/{document}/status', [DocumentController::class, 'quickStatus'])
        ->name('documents.quickStatus')->middleware('role:Admin,Secretary');

    // ---------------------------------------------------
    // Blotter Cases — Admin + Secretary only
    // ---------------------------------------------------
    Route::resource('blotter', BlotterController::class)
        ->middleware('role:Admin,Secretary');
    Route::patch('blotter/{blotter}/status', [BlotterController::class, 'quickStatus'])
        ->name('blotter.quickStatus')->middleware('role:Admin,Secretary');

    // ---------------------------------------------------
    // Business Permits — Admin + Secretary only
    // ---------------------------------------------------
    Route::resource('businesses', BusinessController::class)
        ->middleware('role:Admin,Secretary');
    Route::patch('businesses/{business}/status', [BusinessController::class, 'quickStatus'])
        ->name('businesses.quickStatus')->middleware('role:Admin,Secretary');

    // ---------------------------------------------------
    // Officials — Admin + Secretary only
    // ---------------------------------------------------
    Route::resource('officials', OfficialController::class)
        ->middleware('role:Admin,Secretary');
    Route::patch('officials/{official}/toggle-status', [OfficialController::class, 'toggleStatus'])
        ->name('officials.toggle-status')
        ->middleware('role:Admin,Secretary');

    // ---------------------------------------------------
    // Committees — all roles (Admin, Secretary, Committee)
    // ---------------------------------------------------
    Route::get('committees/{slug}', [CommitteeController::class, 'show'])
        ->name('committees.show');

    Route::post('committees/{slug}/records', [CommitteeController::class, 'storeRecord'])
        ->name('committees.storeRecord')->middleware('role:Admin,Secretary,Committee');

    Route::post('committees/{slug}/activities', [CommitteeController::class, 'storeActivity'])
        ->name('committees.storeActivity')->middleware('role:Admin,Secretary,Committee');

    Route::post('committees/{slug}/attendance', [CommitteeController::class, 'storeAttendance'])
        ->name('committees.storeAttendance')->middleware('role:Admin,Secretary,Committee');

    Route::post('committees/{slug}/inventory', [CommitteeController::class, 'storeInventory'])
        ->name('committees.storeInventory')->middleware('role:Admin,Secretary,Committee');

    Route::post('committees/{slug}/specific', [CommitteeController::class, 'storeSpecific'])
        ->name('committees.storeSpecific')->middleware('role:Admin,Secretary,Committee');

    Route::post('committees/{slug}/partnerships', [CommitteeController::class, 'storePartnership'])
        ->name('committees.storePartnership')->middleware('role:Admin,Secretary,Committee');

    Route::delete('committees/{slug}/partnerships/{id}', [CommitteeController::class, 'destroyPartnership'])
        ->name('committees.destroyPartnership')->middleware('role:Admin,Secretary,Committee');

    Route::delete('committees/{slug}/medicine/{id}', [CommitteeController::class, 'destroyMedicine'])
        ->name('committees.destroyMedicine')->middleware('role:Admin,Secretary,Committee');

    Route::post('committees/{slug}/medicine/{id}/adjust', [CommitteeController::class, 'adjustMedicine'])
        ->name('committees.adjustMedicine')->middleware('role:Admin,Secretary,Committee');

    Route::delete('committees/{slug}/relief/{id}', [CommitteeController::class, 'destroyRelief'])
        ->name('committees.destroyRelief')->middleware('role:Admin,Secretary,Committee');

    // Generic tab deletes
    Route::delete('committees/{slug}/records/{id}',    [CommitteeController::class, 'destroyRecord'])    ->name('committees.destroyRecord');
    Route::delete('committees/{slug}/activities/{id}', [CommitteeController::class, 'destroyActivity'])  ->name('committees.destroyActivity');
    Route::delete('committees/{slug}/attendance/{id}', [CommitteeController::class, 'destroyAttendance'])->name('committees.destroyAttendance');
    Route::delete('committees/{slug}/inventory/{id}',  [CommitteeController::class, 'destroyInventory']) ->name('committees.destroyInventory');
    // Specific tab delete (single route, type-dispatched)
    Route::delete('committees/{slug}/specific/{type}/{id}', [CommitteeController::class, 'destroySpecificItem'])->name('committees.destroySpecificItem');
    // Generic tab updates
    Route::patch('committees/{slug}/activities/{id}',  [CommitteeController::class, 'updateActivity'])   ->name('committees.updateActivity');
    Route::patch('committees/{slug}/attendance/{id}',  [CommitteeController::class, 'updateAttendance']) ->name('committees.updateAttendance');
    Route::patch('committees/{slug}/inventory/{id}',   [CommitteeController::class, 'updateInventory'])  ->name('committees.updateInventory');
    Route::patch('committees/{slug}/partnerships/{id}',[CommitteeController::class, 'updatePartnership'])->name('committees.updatePartnership');
    // Specific tab update
    Route::patch('committees/{slug}/specific/{type}/{id}', [CommitteeController::class, 'updateSpecificItem'])->name('committees.updateSpecificItem');

    // ---------------------------------------------------
    // Appointments (Document Scheduling) — Admin + Secretary
    // ---------------------------------------------------
    Route::get('appointments', [AppointmentController::class, 'index'])
        ->name('appointments.index')->middleware('role:Admin,Secretary');
    Route::patch('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])
        ->name('appointments.updateStatus')->middleware('role:Admin,Secretary');
    Route::delete('appointments/{appointment}', [AppointmentController::class, 'destroy'])
        ->name('appointments.destroy')->middleware('role:Admin,Secretary');

    // ---------------------------------------------------
    // Portal Pending Count — for sidebar badge (Admin + Secretary)
    // ---------------------------------------------------
    Route::get('portal/pending-count', [AppointmentController::class, 'portalPendingCount'])
        ->name('portal.pending-count')->middleware('role:Admin,Secretary');

    // ---------------------------------------------------
    // Reports — Admin + Secretary only
    // ---------------------------------------------------
    Route::get('reports', [ReportController::class, 'analytics'])
        ->name('reports.index')
        ->middleware('role:Admin,Secretary');
    Route::get('reports/preview', [ReportController::class, 'preview'])
        ->name('reports.preview')
        ->middleware('role:Admin,Secretary');

    // ---------------------------------------------------
    // User Management — Admin only
    // ---------------------------------------------------
    Route::post('users/{user}/verify', [UserController::class, 'verify'])->name('users.verify')->middleware('role:Admin');
    Route::post('users/{user}/unverify', [UserController::class, 'unverify'])->name('users.unverify')->middleware('role:Admin');
    Route::patch('users/{user}/verify-toggle', [UserController::class, 'verifyToggle'])->name('users.verify-toggle')->middleware('role:Admin');
    Route::resource('users', UserController::class)
        ->middleware('role:Admin');

    // ---------------------------------------------------
    // Activity Log — Admin + Secretary
    // ---------------------------------------------------
    Route::get('activity-log', [ActivityLogController::class, 'index'])
        ->name('activity-log.index')
        ->middleware('role:Admin,Secretary');

    // ---------------------------------------------------
    // Exports — Admin + Secretary
    // ---------------------------------------------------
    Route::get('export/excel/{module}', [ExportController::class, 'excel'])
        ->name('export.excel')
        ->middleware('role:Admin,Secretary');
    Route::get('export/pdf/{module}', [ExportController::class, 'pdf'])
        ->name('export.pdf')
        ->middleware('role:Admin,Secretary');
    Route::get('export/analytics/{format}', [ExportController::class, 'analytics'])
        ->name('export.analytics')
        ->middleware('role:Admin,Secretary');

    // Report Generation — Admin + Secretary only
    Route::get('reports/generate', [ReportController::class, 'index'])->name('reports.generate')->middleware('role:Admin,Secretary');
    Route::post('reports/generate', [ReportController::class, 'generate'])->name('reports.generate.post')->middleware('role:Admin,Secretary');

    // Database Backup
    Route::get('backup', [BackupController::class, 'index'])->name('backup.index')->middleware('role:Admin');
    Route::post('backup/create', [BackupController::class, 'create'])->name('backup.create')->middleware('role:Admin');
    Route::get('backup/download/{filename}', [BackupController::class, 'download'])->name('backup.download')->middleware('role:Admin');
    Route::post('backup/restore', [BackupController::class, 'restore'])->name('backup.restore')->middleware('role:Admin');
    Route::post('backup/upload', [BackupController::class, 'upload'])->name('backup.upload')->middleware('role:Admin');
    Route::delete('backup/{filename}', [BackupController::class, 'delete'])->name('backup.delete')->middleware('role:Admin');

    // Global Search — all authenticated users (results filtered by role in controller)
    Route::get('search', [SearchController::class, 'search'])->name('search');

    // Select2 AJAX — Admin + Secretary only
    Route::get('select2/residents', [Select2Controller::class, 'residents'])->name('select2.residents')->middleware('role:Admin,Secretary');

});

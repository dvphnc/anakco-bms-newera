<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\BlotterController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\OfficialController;
use App\Http\Controllers\CommitteeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PurokController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\VerifyController;

// -------------------------------------------------------
// Public Verification Routes — no login required
// -------------------------------------------------------
Route::get('/verify/business/{permitNumber}', [VerifyController::class, 'business'])
    ->name('verify.business');

// -------------------------------------------------------
// Guest Routes — handled by Breeze (keep this line)
// -------------------------------------------------------
require __DIR__ . '/auth.php';

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

    // ---------------------------------------------------
    // Blotter Cases — Admin + Secretary only
    // ---------------------------------------------------
    Route::resource('blotter', BlotterController::class)
        ->middleware('role:Admin,Secretary');

    // ---------------------------------------------------
    // Business Permits — Admin + Secretary only
    // ---------------------------------------------------
    Route::resource('businesses', BusinessController::class)
        ->middleware('role:Admin,Secretary');

    // ---------------------------------------------------
    // Officials — Admin + Secretary only
    // ---------------------------------------------------
    Route::resource('officials', OfficialController::class)
        ->middleware('role:Admin,Secretary');

    // ---------------------------------------------------
    // Committees — all roles (Admin, Secretary, Committee)
    // ---------------------------------------------------
    Route::get('committees/{slug}', [CommitteeController::class, 'show'])
        ->name('committees.show');

    Route::post('committees/{slug}/records', [CommitteeController::class, 'storeRecord'])
        ->name('committees.storeRecord');

    Route::post('committees/{slug}/activities', [CommitteeController::class, 'storeActivity'])
        ->name('committees.storeActivity');

    Route::post('committees/{slug}/attendance', [CommitteeController::class, 'storeAttendance'])
        ->name('committees.storeAttendance');

    Route::post('committees/{slug}/inventory', [CommitteeController::class, 'storeInventory'])
        ->name('committees.storeInventory');
    Route::post('committees/{slug}/specific', [CommitteeController::class, 'storeSpecific'])
        ->name('committees.storeSpecific');

    // ---------------------------------------------------
    // Reports — Admin + Secretary only
    // ---------------------------------------------------
    Route::get('reports', [ReportController::class, 'index'])
        ->name('reports.index')
        ->middleware('role:Admin,Secretary');

    // ---------------------------------------------------
    // User Management — Admin only
    // ---------------------------------------------------
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

    // Report Generation
    Route::get('reports/generate', [ReportController::class, 'index'])->name('reports.generate');
    Route::post('reports/generate', [ReportController::class, 'generate'])->name('reports.generate');

    // Database Backup
    Route::get('backup', [BackupController::class, 'index'])->name('backup.index')->middleware('role:Admin');
    Route::post('backup/create', [BackupController::class, 'create'])->name('backup.create')->middleware('role:Admin');
    Route::get('backup/download/{filename}', [BackupController::class, 'download'])->name('backup.download')->middleware('role:Admin');
    Route::post('backup/restore', [BackupController::class, 'restore'])->name('backup.restore')->middleware('role:Admin');
    Route::post('backup/upload', [BackupController::class, 'upload'])->name('backup.upload')->middleware('role:Admin');
    Route::delete('backup/{filename}', [BackupController::class, 'delete'])->name('backup.delete')->middleware('role:Admin');

});
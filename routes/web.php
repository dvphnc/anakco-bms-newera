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

// -------------------------------------------------------
// Guest Routes — handled by Breeze (keep this line)
// -------------------------------------------------------
require __DIR__ . '/auth.php';

// -------------------------------------------------------
// Authenticated Routes — all require login
// -------------------------------------------------------
Route::middleware(['auth', 'verified'])->group(function () {

    // ---------------------------------------------------
    // Dashboard
    // ---------------------------------------------------
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    // ---------------------------------------------------
    // Residents
    // ---------------------------------------------------
    Route::resource('residents', ResidentController::class);

    // ---------------------------------------------------
    // Households
    // ---------------------------------------------------
    Route::resource('households', HouseholdController::class);

    // ---------------------------------------------------
    // Documents
    // ---------------------------------------------------
    Route::resource('documents', DocumentController::class);

    // ---------------------------------------------------
    // Blotter Cases
    // ---------------------------------------------------
    Route::resource('blotter', BlotterController::class);

    // ---------------------------------------------------
    // Business Permits
    // ---------------------------------------------------
    Route::resource('businesses', BusinessController::class);

    // ---------------------------------------------------
    // Officials
    // ---------------------------------------------------
    Route::resource('officials', OfficialController::class);

    // ---------------------------------------------------
    // Committees — one show route + 4 store routes
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

    // ---------------------------------------------------
    // Reports
    // ---------------------------------------------------
    Route::get('reports', [ReportController::class, 'index'])
        ->name('reports.index');

    // ---------------------------------------------------
    // User Management
    // ---------------------------------------------------
    Route::resource('users', UserController::class);

});
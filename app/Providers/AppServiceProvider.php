<?php

namespace App\Providers;

use App\Models\DocumentAppointment;
use App\Models\User;
use App\Observers\DocumentAppointmentObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Sync appointment status → linked Document record (1-to-1, no echo loop)
        DocumentAppointment::observe(DocumentAppointmentObserver::class);

        // Part 2: religion and minister data is sensitive, so Admin only for now.
        // Everything checks these two names (never the role directly), so when roles
        // become editable (Part 3) this is the one place that changes.
        Gate::define('view-religion-data', fn (User $user) => $user->role === 'Admin');
        Gate::define('manage-religion-data', fn (User $user) => $user->role === 'Admin');
    }
}

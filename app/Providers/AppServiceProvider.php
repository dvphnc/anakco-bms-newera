<?php

namespace App\Providers;

use App\Models\DocumentAppointment;
use App\Observers\DocumentAppointmentObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Sync appointment status → linked Document record (1-to-1, no echo loop)
        DocumentAppointment::observe(DocumentAppointmentObserver::class);
    }
}

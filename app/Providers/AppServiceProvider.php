<?php

namespace App\Providers;

use App\Enums\ResidencyStatus;
use App\Models\DocumentAppointment;
use App\Models\PabahayUnit;
use App\Models\User;
use App\Observers\DocumentAppointmentObserver;
use App\Support\Permissions;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Sync appointment status → linked Document record (1-to-1, no echo loop)
        DocumentAppointment::observe(DocumentAppointmentObserver::class);

        // Part 3.2: every permission key is a gate, so @can('residents.create') and
        // Gate::allows() read the Roles & Permissions matrix
        foreach (Permissions::keys() as $key) {
            Gate::define($key, fn (User $user) => $user->hasPermission($key));
        }

        // Part 2: religion and minister data is sensitive (only the Admin role has it by default).
        // Everything checks these two names, never a role.
        Gate::define('view-religion-data', fn (User $user) => $user->hasPermission('religion.view'));
        Gate::define('manage-religion-data', fn (User $user) => $user->hasPermission('religion.manage'));

        // The sidebar's Pabahay dropdown: active units, grouped by Pabahay, with living residents.
        // Only built for Admin (nobody else sees that part of the sidebar).
        View::composer('partials._sidebar', function ($view) {
            if (! Gate::allows('view-religion-data')) {
                return;
            }

            $view->with('sidebarPabahayUnits', PabahayUnit::with('pabahay')
                ->withCount(['residents as living_count' => fn ($q) => $q->where('residency_status', ResidencyStatus::Alive->value)])
                ->where('is_active', true)
                ->whereHas('pabahay', fn ($q) => $q->where('is_active', true))
                ->ordered()->get()
                ->sortBy(fn ($u) => $u->pabahay->name)
                ->groupBy(fn ($u) => $u->pabahay->name));
        });
    }
}

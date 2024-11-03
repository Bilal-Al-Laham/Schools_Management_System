<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Attendance;
use App\Models\section;
use App\Policies\AttendancePolicy;
use App\Policies\SectionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        section::class => SectionPolicy::class,
        Attendance::class => AttendancePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Gate::define('manage-attendance', function($user) {
            return $user->role == 'admin';
        });
    }
}

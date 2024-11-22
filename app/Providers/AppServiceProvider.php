<?php

namespace App\Providers;

use App\Models\Examention;
use App\Models\Sanctum\PersonalAccessToken;
use App\Repositories\AssignmentRepository;
use App\Repositories\AssignmentRepositoryInterface;
use App\Repositories\AttendanceRepository;
use App\Repositories\AttendanceRepositoryInterface;
use App\Repositories\ExamentionRepository;
use App\Repositories\ExamentionRepositoryInterface;
use App\Repositories\SchoolClassRepository;
use App\Repositories\SchoolClassRepositoryInterface;
use App\Repositories\SectionRepository;
use App\Repositories\SectionRepositoryInterface;
use App\Repositories\SubjectRepository;
use App\Repositories\SubjectRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Services\AttendanceService;
use App\Services\AttendanceServiceInterface;
use App\Services\ExamentionService;
use App\Services\ExamentionServiceInterface;
use App\Services\SectionService;
use App\Services\SectionServiceInterface;
use App\Services\SubjectService;
use App\Services\SubjectServiceInterface;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Sanctum::ignoreMigrations();
        // user
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        // school Class
        $this->app->bind(SchoolClassRepositoryInterface::class, SchoolClassRepository::class);

        // Sections
        $this->app->bind(SectionRepositoryInterface::class, SectionRepository::class);
        $this->app->bind(SectionServiceInterface::class, SectionService::class);

        // Subjects
        $this->app->bind(SubjectServiceInterface::class, SubjectService::class);
        $this->app->bind(SubjectRepositoryInterface::class, SubjectRepository::class);

        // Attendances
        $this->app->bind(AttendanceServiceInterface::class, AttendanceService::class);
        $this->app->bind(AttendanceRepositoryInterface::class, AttendanceRepository::class);

        // Assignments
        $this->app->bind(AssignmentRepositoryInterface::class, AssignmentRepository::class);

        //Examentions
        $this->app->bind(ExamentionRepositoryInterface::class, ExamentionRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}

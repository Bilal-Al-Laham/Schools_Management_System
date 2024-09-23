<?php

namespace App\Providers;

use App\Models\Sanctum\PersonalAccessToken;
use App\Repositories\SchoolClassRepository;
use App\Repositories\SchoolClassRepositoryInterface;
use App\Repositories\SectionRepository;
use App\Repositories\SectionRepositoryInterface;
use App\Repositories\SubjectRepository;
use App\Repositories\SubjectRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
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
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(SchoolClassRepositoryInterface::class, SchoolClassRepository::class);
        $this->app->bind(SectionRepositoryInterface::class, SectionRepository::class);
        $this->app->bind(SectionServiceInterface::class, SectionService::class);
        $this->app->bind(SubjectServiceInterface::class, SubjectService::class);
        $this->app->bind(SubjectRepositoryInterface::class, SubjectRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}

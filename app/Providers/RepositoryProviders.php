<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Repository\DBRepositoryAuth;
use App\Http\Repository\DBRepositoryCategory;
use App\Http\Repository\DBRepositoryTransaction;
use App\Http\RepositoryInterface\RepositoryAuthInterface;
use App\Http\RepositoryInterface\RepositoryCategoryInterface;
use App\Http\RepositoryInterface\RepositoryTransactionInterface;

class RepositoryProviders extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(RepositoryAuthInterface::class, DBRepositoryAuth::class);
        $this->app->bind(RepositoryCategoryInterface::class, DBRepositoryCategory::class);
        $this->app->bind(RepositoryTransactionInterface::class, DBRepositoryTransaction::class);
    }
}

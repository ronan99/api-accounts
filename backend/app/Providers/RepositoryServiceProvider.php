<?php

namespace App\Providers;

use App\Repository\Contracts\Transaction\ITransactionRepository;
use App\Repository\Contracts\User\IUserRepository;
use App\Repository\Transaction\TransactionRepository;
use App\Repository\User\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            IUserRepository::class,
            UserRepository::class
        );

        $this->app->bind(
            ITransactionRepository::class,
            TransactionRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

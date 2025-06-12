<?php

namespace App\Providers;

use App\Exceptions\Handler;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler;
use App\Repositories\Transaction\DBTransaction;
use Core\UseCase\Interfaces\TransactionInterface;
use App\Repositories\Eloquent\CategoryRepository;
use Core\Domain\Repository\CategoryRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->singleton(ExceptionHandler::class, Handler::class);

        $this->app->bind(TransactionInterface::class, DBTransaction::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

use Illuminate\Foundation\Application;
use App\Repositories\CategoryRepository;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Core\Domain\Repository\CategoryRepositoryInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withSingletons([
        CategoryRepositoryInterface::class => CategoryRepository::class,
    ])
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Passport\Passport;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            // Aquí podrías añadir middlewares si los necesitas
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create(); // NOTA: guardamos la app en la variable $app

// 👇 Esta línea es clave: le indica a Passport dónde están las claves
Passport::loadKeysFrom(storage_path());

return $app;
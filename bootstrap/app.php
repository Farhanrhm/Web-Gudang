<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // 1. DAFTARKAN MIDDLEWARE ADMIN DI SINI
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
        ]); // <--- Pastikan ada kurung siku tutup, kurung biasa tutup, dan titik koma

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
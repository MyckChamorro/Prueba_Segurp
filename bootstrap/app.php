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
        // Registrar middleware personalizado
        $middleware->alias([
            'verificar.estado.activo' => \App\Http\Middleware\VerificarEstadoActivo::class,
        ]);
        
        // Aplicar middleware globalmente a rutas web autenticadas
        $middleware->web(append: [
            \App\Http\Middleware\VerificarEstadoActivo::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

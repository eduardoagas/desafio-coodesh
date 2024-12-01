<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    api: __DIR__ . '/../routes/api.php',
    commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    then: function () {
        Route::middleware('api')
            ->prefix('auth')
            ->name('auth.')
            ->group(base_path('routes/auth-routes.php'));
        Route::middleware('auth:api')
        ->prefix('entries')
        ->name('entries.')
        ->group(base_path('routes/entry-routes.php'));
        Route::middleware('auth:api')
            ->prefix('user')
            ->name('user.')
            ->group(base_path('routes/user-routes.php'));
    },

    )
    ->withMiddleware(function (Middleware $middleware) {})
    ->withExceptions(function (Exceptions $exceptions) {
    $exceptions->renderable(function (AuthenticationException $e, $request) {
        return response()->json([
            'message' => 'Please, sign in first!'
        ], 400);
    });
    $exceptions->renderable(function (ValidationException $e) {
        return response()->json([
            'message' => 'Erro de validacao',
            //'errors' => $e->errors(),
        ], 400);
    });
        /*$exceptions->render(function (Throwable $e) {
        /*if ( $e instanceof AccessDeniedHttpException ) {
            /* This may be overly specific, but I want to handle other
               potential AccessDeniedHttpExceptions when I come
               //across them 
            if ( $e->getPrevious() instanceof AuthorizationException ) {
                return redirect()
                    ->route('dashboard')
                    ->withErrors($e->getMessage());
            }
        }/
        if ($e instanceof ValidationException) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        }
    });*/
    })->create();

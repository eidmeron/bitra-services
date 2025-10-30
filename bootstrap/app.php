<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AnalyticsTrackingMiddleware;
use App\Http\Middleware\CompanyMiddleware;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'analytics' => AnalyticsTrackingMiddleware::class,
            'company' => CompanyMiddleware::class,
            'user' => UserMiddleware::class,
        ]);
        
        // Add analytics tracking to web routes
        $middleware->web(append: [
            AnalyticsTrackingMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sidan hittades inte',
                    'error' => 'Not Found'
                ], 404);
            }
            
            return response()->view('errors.404', [], 404);
        });
    })->create();

<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AnalyticsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Analytics API Routes
Route::prefix('analytics')->group(function () {
    Route::post('/track-page-view', [AnalyticsController::class, 'trackPageView']);
    Route::post('/track-scroll', [AnalyticsController::class, 'trackScroll']);
    Route::post('/track-clicks', [AnalyticsController::class, 'trackClicks']);
    Route::post('/track-form-interaction', [AnalyticsController::class, 'trackFormInteraction']);
    Route::post('/track-time', [AnalyticsController::class, 'trackTime']);
    Route::post('/track-conversion', [AnalyticsController::class, 'trackConversion']);
    Route::post('/track-keyword', [AnalyticsController::class, 'trackKeyword']);
    Route::post('/track-custom-event', [AnalyticsController::class, 'trackCustomEvent']);
});

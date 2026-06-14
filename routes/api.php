<?php

use App\Http\Controllers\Api\AspirasiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DapilController;
use App\Http\Controllers\Api\DesaController;
use App\Http\Controllers\Api\KamusPokirController;
use App\Http\Controllers\Api\KecamatanController;
use App\Http\Controllers\Api\OpdController;
use App\Http\Controllers\Api\PokirController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public SSO callback endpoint
    // Route::get('/auth/sso/callback', [AuthController::class, 'ssoCallback']);
    Route::post('/auth/sso/callback', [AuthController::class, 'ssoCallback']);

    // Authenticated API endpoints
    Route::middleware('auth:api')->group(function () {
        // Authenticated Auth endpoints
        Route::get('/auth/profile', [AuthController::class, 'profile']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Master Data endpoints
        Route::apiResource('dapils', DapilController::class);
        Route::apiResource('opds', OpdController::class);
        Route::apiResource('kecamatans', KecamatanController::class);
        Route::apiResource('desas', DesaController::class);
        Route::apiResource('kamus-pokir', KamusPokirController::class);

        // Transactional: Aspirasi
        Route::apiResource('aspirasi', AspirasiController::class);

        // Transactional: Pokir Custom Workflows
        Route::post('/pokir/{id}/submit', [PokirController::class, 'submit']);
        Route::post('/pokir/{id}/verify', [PokirController::class, 'verify']);
        Route::post('/pokir/{id}/request-revision', [
            PokirController::class,
            'requestRevision',
        ]);
        Route::post('/pokir/{id}/finalize', [
            PokirController::class,
            'finalize',
        ]);
        Route::post('/pokir/{id}/aspirasi', [
            PokirController::class,
            'addAspirasi',
        ]);
        Route::delete('/pokir/{id}/aspirasi/{aspirasiId}', [
            PokirController::class,
            'removeAspirasi',
        ]);

        // Transactional: Pokir Resource
        Route::apiResource('pokir', PokirController::class);
    });
});

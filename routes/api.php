<?php

use App\Http\Controllers\Api\AspirasiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DapilController;
use App\Http\Controllers\Api\DesaController;
use App\Http\Controllers\Api\KamusPokirController;
use App\Http\Controllers\Api\KamusUsulanController;
use App\Http\Controllers\Api\KecamatanController;
use App\Http\Controllers\Api\OpdController;
use App\Http\Controllers\Api\PeriodeController;
use App\Http\Controllers\Api\PokirController;
use App\Http\Controllers\Api\SsoDapilController;
use App\Http\Controllers\Api\SsoFraksiController;
use App\Http\Controllers\Api\UserController;
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
        // OPD: baca bebas untuk semua role (dipakai dropdown referensi Kamus/Aspirasi/Pokir),
        // tulis dibatasi admin.only di bawah
        Route::get('/opds', [OpdController::class, 'index']);
        Route::get('/opds/{opd}', [OpdController::class, 'show']);
        Route::apiResource('kecamatans', KecamatanController::class);
        Route::apiResource('desas', DesaController::class);
        Route::apiResource('kamus-pokir', KamusPokirController::class);

        // Periode & Freeze — lihat/edit/freeze oleh Admin & Setwan, buat/nonaktifkan khusus Admin
        Route::middleware('manager.only')->group(function () {
            Route::get('/periode', [PeriodeController::class, 'index']);
            Route::get('/periode/active', [PeriodeController::class, 'active']);
            Route::put('/periode/{id}', [PeriodeController::class, 'update']);
            Route::patch('/periode/{id}/freeze', [PeriodeController::class, 'freeze']);
            Route::patch('/periode/{id}/activate', [PeriodeController::class, 'activate']);
        });

        // Manajemen User & Master Data SSO Pusat (Dapil/Fraksi) — proxy ke SSO Pusat, khusus Admin
        Route::middleware('admin.only')->group(function () {
            Route::get('/users/dapil-options', [UserController::class, 'dapilOptions']);
            Route::get('/users/fraksi-options', [UserController::class, 'fraksiOptions']);
            Route::apiResource('users', UserController::class);
            Route::patch('/users/{id}/toggle-active', [UserController::class, 'toggleActive']);

            Route::apiResource('sso-dapils', SsoDapilController::class);
            Route::apiResource('sso-fraksis', SsoFraksiController::class);

            // OPD Referensi — operasi tulis dibatasi Admin saja
            Route::post('/opds', [OpdController::class, 'store']);
            Route::put('/opds/{opd}', [OpdController::class, 'update']);
            Route::patch('/opds/{id}/toggle-active', [OpdController::class, 'toggleActive']);
            Route::delete('/opds/{opd}', [OpdController::class, 'destroy']);

            // Periode & Freeze — buat & nonaktifkan permanen khusus Admin
            Route::post('/periode', [PeriodeController::class, 'store']);
            Route::patch('/periode/{id}/deactivate', [PeriodeController::class, 'deactivate']);

            // Kamus Usulan — referensi nomenklatur SIPD, khusus Admin
            Route::get('/kamus-usulan', [KamusUsulanController::class, 'index']);
            Route::post('/kamus-usulan', [KamusUsulanController::class, 'store']);
            Route::put('/kamus-usulan/{id}', [KamusUsulanController::class, 'update']);
            Route::patch('/kamus-usulan/{id}/status', [KamusUsulanController::class, 'toggleStatus']);
            Route::get('/bidang-urusan', [KamusUsulanController::class, 'bidangUrusan']);
        });

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

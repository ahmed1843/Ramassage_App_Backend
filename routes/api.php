<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\NotificationController;

/*

|--------------------------------------------------------------------------
| ROUTES PUBLIQUES (Accessibles par tous)
|--------------------------------------------------------------------------
*/

// Authentification
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Consultation (Pour les citoyens et le binôme)
Route::get('/zones', [ZoneController::class, 'index']);
Route::get('/schedules', [ScheduleController::class, 'index']);
Route::get('/reports', [ReportController::class, 'index']);
Route::get('/notifications', [NotificationController::class, 'index']);
Route::get('/stats/noise', [StatsController::class, 'noiseStats']);

// Actions de test (Accessibles en public pour faciliter tes tests Thunder Client/Postman)
Route::post('/reports', [ReportController::class, 'store']);
Route::post('/zones/{id}/alert', [NotificationController::class, 'sendZoneAlert']); // Le remplaçant du klaxon
Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

/*

|--------------------------------------------------------------------------
| ROUTES PROTÉGÉES (Nécessitent un Token Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/my-reports', [ReportController::class, 'myReports']);
    // ... tes autres routes protégées (logout, user, etc.)
});

    
    // Infos de l'utilisateur connecté
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |--- ROUTES RÉSERVÉES À L'ADMIN ---
    */
    Route::middleware(['admin'])->group(function () {
        Route::post('/zones', [ZoneController::class, 'store']);
        Route::post('/schedules', [ScheduleController::class, 'store']);
        Route::put('/reports/{id}', [ReportController::class, 'update']); // Pour résoudre un rapport
    });
});

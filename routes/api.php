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

// --- Authentification ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- Consultation Publique (Read-Only) ---
Route::get('/zones', [ZoneController::class, 'index']);
Route::get('/schedules', [ScheduleController::class, 'index']);
Route::get('/reports', [ReportController::class, 'index']);
Route::get('/stats/noise', [StatsController::class, 'noiseStats']);

// --- Actions Protégées (Utilisateurs connectés) ---
Route::middleware('auth:sanctum')->group(function () {
  Route::put('/user/update', [AuthController::class, 'updateProfile']); 
    
    // Profil & Déconnexion
    Route::get('/user', fn(Request $request) => $request->user());
    Route::post('/logout', [AuthController::class, 'logout']);

    // Signalements (Attacher l'user_id automatiquement)
    Route::post('/reports', [ReportController::class, 'store']);
    Route::get('/my-reports', [ReportController::class, 'myReports']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::put('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);

    // --- Actions Chauffeurs / Binômes ---
    // On protège l'alerte zone
    Route::post('/zones/{id}/alert', [NotificationController::class, 'sendZoneAlert']);


    // --- Administration ---
    Route::middleware(['admin'])->group(function () {
        Route::post('/zones', [ZoneController::class, 'store']);
        Route::post('/schedules', [ScheduleController::class, 'store']);
        Route::put('/reports/{id}', [ReportController::class, 'update']);
    });
});


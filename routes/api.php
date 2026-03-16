<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StatsController;

Route::get('/stats/noise', [StatsController::class, 'noiseStats']);


// --- ROUTES PUBLIQUES (Accessibles sans Token pour tes tests) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/zones', [ZoneController::class, 'index']);
Route::post('/zones', [ZoneController::class, 'store']);

Route::get('/reports', [ReportController::class, 'index']);
Route::post('/reports', [ReportController::class, 'store']); // Déplacé ici pour tester facilement

Route::get('/schedules', [ScheduleController::class, 'index']);
Route::post('/schedules', [ScheduleController::class, 'store']);


// --- ROUTES PROTÉGÉES (Nécessitent un Token Sanctum) ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
        // Routes accessibles uniquement par l'ADMIN connecté
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/zones', [ZoneController::class, 'store']);
    Route::post('/schedules', [ScheduleController::class, 'store']);
    
});

// Routes accessibles par tout le monde (Citoyens + Admin)
Route::get('/zones', [ZoneController::class, 'index']);
Route::get('/schedules', [ScheduleController::class, 'index']);
Route::post('/reports', [ReportController::class, 'store']); // Un citoyen peut signaler

    });
    
});

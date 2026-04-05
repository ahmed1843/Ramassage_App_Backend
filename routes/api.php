<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\NotificationController;
use App\Models\Report;
use App\Models\User;
use App\Notifications\ReportStatusUpdated;
use Illuminate\Support\Facades\Notification;



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
// Remplace l'ancienne route par celle-ci :
Route::get('/notifications', function (Request $request) {
    // On récupère les notifications de l'utilisateur connecté via ton modèle
    return \App\Models\Notification::where('user_id', $request->user()->id)
        ->latest()
        ->get();
});

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
Route::get('/schedules/zone/{zoneId}', [ScheduleController::class, 'getByZone']); 

Route::get('/reports', [ReportController::class, 'index']);
// À la fin de routes/api.php

// Dans routes/api.php
Route::get('/admin/reports', function () {
    // Correction : on utilise ->get() et non .get()
    return App\Models\Report::with('zone')
        ->orderByRaw("CASE 
            WHEN status = 'pending' THEN 1 
            WHEN status = 'in_progress' THEN 2 
            ELSE 3 
        END")
        ->latest()
        ->get(); 
});


Route::patch('/admin/reports/{id}/status', function (Illuminate\Http\Request $request, $id) {
    $report = Report::with(['user', 'zone'])->findOrFail($id);
    $report->update(['status' => $request->status]);

    if ($report->user) {
        // ✅ On utilise notre nouvelle classe simplifiée
        $notification = new \App\Notifications\ReportStatusUpdated($report);
        $notification->send($report->user);
    }

    return response()->json(['message' => 'Statut mis à jour et notif créée !']);
});

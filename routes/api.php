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
use App\Models\Zone;
use App\Models\Notification;

/*

|--------------------------------------------------------------------------
| ROUTES PUBLIQUES
|--------------------------------------------------------------------------
*/

// --- Authentification ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- Signalements (avec photo supportée) ---
Route::get('/reports', [ReportController::class, 'index']);
Route::post('/reports', [ReportController::class, 'store']);
Route::post('/signalements', [ReportController::class, 'store']);

// --- Système d'Alerte Camion ---
Route::post('/alerte-chauffeur', function (Request $request) {
    $zoneName = $request->zone_name;
    $statut = $request->actif;
    Zone::where('name', $zoneName)->update(['alerte_active' => $statut]);
    return response()->json(['success' => true]);
});

Route::post('/update-truck-gps', function (Request $request) {
    Zone::where('name', $request->zone_name)->update([
        'current_lat' => $request->lat,
        'current_lng' => $request->lng
    ]);
    return response()->json(['success' => true]);
});

Route::get('/check-alerte', function () {
    $zoneActive = Zone::where('alerte_active', true)->first();
    return response()->json([
        'actif' => $zoneActive ? true : false,
        'zone'  => $zoneActive ? $zoneActive->name : null
    ]);
});

// --- Zones et Horaires ---
Route::get('/zones', [ZoneController::class, 'index']);
Route::get('/schedules', [ScheduleController::class, 'index']);
Route::get('/schedules/zone/{zoneId}', [ScheduleController::class, 'getByZone']);

/*

|--------------------------------------------------------------------------
| ROUTES PROTÉGÉES (Sanctum) - TOUTES LES ROUTES NOTIFICATIONS ICI
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    // Utilisateur
    Route::get('/user', fn(Request $request) => $request->user());
    Route::put('/user/update', [AuthController::class, 'updateProfile']); 
    Route::get('/my-reports', [ReportController::class, 'myReports']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Notifications locales (BDD)
    Route::get('/notifications', function (Request $request) {
        return Notification::where('user_id', $request->user()->id)->latest()->get();
    });
    
    // ===== ROUTES NOTIFICATIONS PUSH =====
    Route::post('/save-push-token', [NotificationController::class, 'savePushToken']);
    Route::post('/assign-street', [NotificationController::class, 'assignStreet']);
    Route::get('/streets', [NotificationController::class, 'getStreets']);
    Route::post('/notify-street', [NotificationController::class, 'notifyStreet']);
    Route::get('/zones', [NotificationController::class, 'getZones']);
});

/*

|--------------------------------------------------------------------------
| ADMINISTRATION
|--------------------------------------------------------------------------
*/
Route::get('/admin/reports', function () {
    return Report::with('zone')->latest()->get(); 
});

Route::patch('/admin/reports/{id}/status', function (Request $request, $id) {
    $report = Report::with(['user', 'zone'])->findOrFail($id);
    $report->update(['status' => $request->status]);
    
    if ($report->user) {
        Notification::create([
            'user_id' => $report->user->id,
            'title' => 'Mise à jour de votre signalement',
            'message' => "Votre signalement #{$report->id} est maintenant : " . $request->status,
            'type' => 'status_update',
            'report_id' => $report->id
        ]);
    }
    
    return response()->json(['message' => 'Statut mis à jour avec succès !']);
});

// --- Route de test mobile ---
Route::get('/test-mobile', function () {
    return response()->json([
        'message' => 'Connexion réussie avec le Backend Laravel !',
        'status' => 'EcoWaste Online'
    ]);
});
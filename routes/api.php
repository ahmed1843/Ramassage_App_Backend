<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Models\Report;
use App\Models\Zone;
use App\Models\Notification;

/*
|--------------------------------------------------------------------------
| ROUTES PUBLIQUES
|--------------------------------------------------------------------------
*/
Route::get('/test-json', function () {
    return response()->json(['status' => 'ok']);
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/zones', [ZoneController::class, 'index']);
Route::get('/schedules', [ScheduleController::class, 'index']);
Route::get('/schedules/zone/{zoneId}', [ScheduleController::class, 'getByZone']);

Route::get('/test-mobile', function () {
    return response()->json(['message' => 'EcoWaste Online']);
});

/*
|--------------------------------------------------------------------------
| GESTION DES ALERTES (CHAUFFEUR & CITOYEN)
|--------------------------------------------------------------------------
*/

// 1. Chauffeur – activer l'alerte et envoyer la position
Route::post('/alerte-chauffeur', function (Request $request) {
    try {
        $zoneName = trim($request->zone_name);
        $actif = filter_var($request->actif, FILTER_VALIDATE_BOOLEAN);
        $lat = $request->lat ?? $request->current_lat ?? null;
        $lng = $request->lng ?? $request->current_lng ?? null;

        // Recherche de la zone (insensible à la casse, compatible PostgreSQL)
        $zone = Zone::whereRaw('LOWER(name) = LOWER(?)', [$zoneName])->first();

        if (!$zone) {
            return response()->json(['error' => 'Zone non trouvée'], 404);
        }

        $zone->alerte_active = $actif;
        if ($lat !== null) $zone->current_lat = $lat;
        if ($lng !== null) $zone->current_lng = $lng;
        $zone->save();

        return response()->json(['success' => true, 'is_active' => $actif]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::middleware('auth:sanctum')->get('/check-alerte', function (Request $request) {
    try {
        $user = $request->user();

        // ✅ Driver ne doit pas recevoir les alertes citoyens
        if ($user && $user->role === 'driver') {
            return response()->json(['actif' => false]);
        }

        if ($user && $user->street) {
            $zone = Zone::whereRaw('LOWER(name) = LOWER(?)', [$user->street])
                        ->where('alerte_active', true)
                        ->first();
        } else {
            $zone = Zone::where('alerte_active', true)->first();
        }

        if ($zone) {
            return response()->json([
                'actif'       => true,
                'zone'        => $zone->name,
                'current_lat' => (float) $zone->current_lat,
                'current_lng' => (float) $zone->current_lng,
            ]);
        }

        return response()->json(['actif' => false]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});
/*
|--------------------------------------------------------------------------
| RÉINITIALISATION DU MOT DE PASSE (corrigé)
|--------------------------------------------------------------------------
*/

// Demander la réinitialisation — envoie un code par email
Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    
    $email = strtolower(trim($request->email)); // normalisation
    $user = \App\Models\User::whereRaw('LOWER(email) = ?', [$email])->first();
    
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Aucun compte trouvé avec cet email'
        ], 404);
    }
    
    // Génère un code à 6 chiffres
    $code = rand(100000, 999999);
    
    // Stocke le code en cache pendant 15 minutes (clé en minuscules)
    \Cache::put('reset_code_' . $email, (string)$code, now()->addMinutes(15));
    
    // Envoie l'email
    \Mail::raw(
        "Votre code de réinitialisation SmartWaste : {$code}\n\nCe code expire dans 15 minutes.",
        function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('🔐 Code de réinitialisation SmartWaste');
        }
    );
    
    return response()->json([
        'success' => true,
        'message' => 'Code envoyé à ' . $user->email
    ]);
});

// Vérifier le code et changer le mot de passe
Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'code' => 'required|string',
        'new_password' => 'required|min:6',
    ]);
    
    $email = strtolower(trim($request->email));
    $cachedCode = \Cache::get('reset_code_' . $email);
    
    if (!$cachedCode || $cachedCode !== (string)$request->code) {
        return response()->json([
            'success' => false,
            'message' => 'Code invalide ou expiré'
        ], 400);
    }
    
    $user = \App\Models\User::whereRaw('LOWER(email) = ?', [$email])->first();
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé'], 404);
    }
    
    $user->password = \Hash::make($request->new_password);
    $user->save();
    
    // Supprime le code après utilisation
    \Cache::forget('reset_code_' . $email);
    
    return response()->json([
        'success' => true,
        'message' => 'Mot de passe réinitialisé avec succès'
    ]);
});

/*
|--------------------------------------------------------------------------
| ROUTES PROTÉGÉES (auth:sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Profil
    Route::get('/user', fn(Request $request) => $request->user());
    Route::put('/user/update', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Signalements
    Route::get('/reports', [ReportController::class, 'index']);
    Route::post('/reports', [ReportController::class, 'store']);

    // Route my-reports
    Route::get('/my-reports', function (Request $request) {
        $reports = Report::where('user_id', $request->user()->id)
                     ->latest()
                     ->get();
        return response()->json(['success' => true, 'data' => $reports]);
    });

    // Notifications
    Route::get('/notifications', function (Request $request) {
        return Notification::where('user_id', $request->user()->id)->latest()->get();
    });

    // Push notifications
    Route::post('/save-push-token', [NotificationController::class, 'savePushToken']);
    Route::post('/assign-street', [NotificationController::class, 'assignStreet']);
    Route::get('/streets', [NotificationController::class, 'getStreets']);
    Route::post('/notify-street', [NotificationController::class, 'notifyStreet']);
});

/*
|--------------------------------------------------------------------------
| ADMINISTRATION
|--------------------------------------------------------------------------
*/
Route::get('/admin/reports', function () {
    return Report::latest()->get();
});

Route::patch('/admin/reports/{id}/status', function (Request $request, $id) {
    $report = Report::with('user')->findOrFail($id);
    $report->update(['status' => $request->status]);

    if ($report->user) {
        Notification::create([
            'user_id' => $report->user->id,
            'title' => 'Mise à jour de votre signalement',
            'message' => "Le statut de votre signalement #{$report->id} est passé à : " . $request->status,
            'type' => 'status_update',
            'report_id' => $report->id
        ]);
    }

    return response()->json(['success' => true, 'message' => 'Statut mis à jour !']);
});
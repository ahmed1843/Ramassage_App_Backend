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
use App\Models\User;
use App\Models\SupportMessage;

// ── Routes publiques ──────────────────────────────────────────────────────────
Route::get('/test-json',   fn() => response()->json(['status' => 'ok']));
Route::get('/test-mobile', fn() => response()->json(['message' => 'EcoWaste Online']));
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
Route::post('/login',    [AuthController::class, 'login'])->middleware('throttle:10,1');

Route::get('/zones',                    [ZoneController::class,    'index']);
Route::get('/schedules',                [ScheduleController::class, 'index']);
Route::get('/schedules/zone/{zoneId}',  [ScheduleController::class, 'getByZone']);

// ── Mot de passe oublié ───────────────────────────────────────────────────────
Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    $email = strtolower(trim($request->email));
    $user  = User::whereRaw('LOWER(email) = ?', [$email])->first();
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'Aucun compte trouvé avec cet email'], 404);
    }
    $code = rand(100000, 999999);
    \Cache::put('reset_code_' . $email, (string) $code, now()->addMinutes(15));
    \Mail::raw(
        "Votre code de réinitialisation SmartWaste : {$code}\n\nCe code expire dans 15 minutes.",
        fn($m) => $m->to($user->email)->subject('🔐 Code de réinitialisation SmartWaste')
    );
    return response()->json(['success' => true, 'message' => 'Code envoyé à ' . $user->email]);
});

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'email'        => 'required|email',
        'code'         => 'required|string',
        'new_password' => 'required|min:6',
    ]);
    $email      = strtolower(trim($request->email));
    $cachedCode = \Cache::get('reset_code_' . $email);
    if (!$cachedCode || $cachedCode !== (string) $request->code) {
        return response()->json(['success' => false, 'message' => 'Code invalide ou expiré'], 400);
    }
    $user = User::whereRaw('LOWER(email) = ?', [$email])->first();
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé'], 404);
    }
    $user->password = \Hash::make($request->new_password);
    $user->save();
    \Cache::forget('reset_code_' . $email);
    return response()->json(['success' => true, 'message' => 'Mot de passe réinitialisé avec succès']);
});

// ── Alerte chauffeur (appelée depuis l'app driver) ──────────────────────────
Route::middleware('auth:sanctum')->post('/alerte-chauffeur', function (Request $request) {
    if ($request->user()->role !== 'driver') {
        abort(403, 'Réservé aux chauffeurs');
    }
    try {
        $zoneName = trim($request->zone_name);
        $actif    = filter_var($request->actif, FILTER_VALIDATE_BOOLEAN);
        $lat      = $request->lat ?? null;
        $lng      = $request->lng ?? null;
        $zone = Zone::whereRaw('LOWER(name) = LOWER(?)', [$zoneName])->first();
        if (!$zone) return response()->json(['error' => 'Zone non trouvée'], 404);
        if ($actif) {
            Zone::where('id', '!=', $zone->id)
                ->update(['alerte_active' => false, 'current_lat' => null, 'current_lng' => null]);
        }
        $zone->alerte_active = $actif;
        if ($actif && $lat && $lng && $lat != 0 && $lng != 0) {
            $zone->current_lat = (float) $lat;
            $zone->current_lng = (float) $lng;
        }
        if (!$actif) {
            $zone->current_lat = null;
            $zone->current_lng = null;
        }
        $zone->save();
        return response()->json(['success' => true, 'is_active' => $actif]);
    } catch (\Exception $e) {
        \Log::error('Erreur alerte chauffeur : ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Impossible de mettre à jour l\'alerte'], 500);
    }
});

// ── Check alerte — public avec auth optionnelle ───────────────────────────────
Route::get('/check-alerte', function (Request $request) {
    try {
        $user = null;
        try { $user = auth('sanctum')->user(); } catch (\Exception $e) {}

        if ($user && $user->role === 'driver') {
            return response()->json(['actif' => false]);
        }

        if ($user && $user->street) {
            $zone = Zone::whereRaw('LOWER(name) = LOWER(?)', [$user->street])
                        ->where('alerte_active', true)->first();
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

// ── Routes authentifiées (citoyens / chauffeurs) ──────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user',           fn(Request $r) => $r->user());
    Route::put('/user/update',    [AuthController::class, 'updateProfile']);
Route::delete('/user/delete', [AuthController::class, 'deleteAccount']); // 👈 Nouvelle route de suppression
    Route::post('/logout',        [AuthController::class, 'logout']);

    Route::get('/reports',        [ReportController::class, 'index']);
    Route::post('/reports',       [ReportController::class, 'store']);

    Route::get('/my-reports', function (Request $request) {
        return response()->json([
            'success' => true,
            'data'    => Report::where('user_id', $request->user()->id)->latest()->get(),
        ]);
    });

    Route::post('/save-push-token', [NotificationController::class, 'savePushToken']);
    Route::post('/assign-street',   [NotificationController::class, 'assignStreet']);
    Route::get('/streets',          [NotificationController::class, 'getStreets']);
    Route::post('/notify-street',   [NotificationController::class, 'notifyStreet']);

    Route::get('/notifications',              [NotificationController::class, 'index']);
    Route::put('/notifications/{id}/read',    [NotificationController::class, 'markAsRead']);

    Route::post('/support', function (Request $request) {
        SupportMessage::create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);
        return response()->json(['success' => true]);
    });
});

// ── Routes admin — protégées auth + rôle admin ────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/admin/users', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        return User::latest()->get();
    });

    Route::patch('/admin/users/{id}/role', function (Request $request, $id) {
        if (auth()->user()->role !== 'admin') abort(403);
        $request->validate(['role' => 'required|in:citizen,driver,admin']);
        User::findOrFail($id)->update(['role' => $request->role]);
        return response()->json(['success' => true]);
    });

    Route::patch('/admin/users/{id}/street', function (Request $request, $id) {
        if (auth()->user()->role !== 'admin') abort(403);
        $request->validate(['street' => 'required|string']);
        User::findOrFail($id)->update(['street' => $request->street]);
        return response()->json(['success' => true]);
    });

    Route::get('/admin/reports', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        return Report::latest()->get();
    });

    Route::patch('/admin/reports/{id}/status', function (Request $request, $id) {
        if (auth()->user()->role !== 'admin') abort(403);
        $report = Report::with('user')->findOrFail($id);
        $report->update(['status' => $request->status]);
        if ($report->user) {
            Notification::create([
                'user_id'   => $report->user->id,
                'title'     => 'Mise à jour de votre signalement',
                'message'   => "Le statut de votre signalement #{$report->id} est passé à : " . $request->status,
                'type'      => 'status_update',
                'report_id' => $report->id,
            ]);
        }
        return response()->json(['success' => true, 'message' => 'Statut mis à jour !']);
    });

    Route::get('/admin/support', function () {
        if (auth()->user()->role !== 'admin') abort(403);
        return SupportMessage::with('user')->latest()->get();
    });

    Route::post('/admin/support/{id}/reply', function (Request $request, $id) {
        if (auth()->user()->role !== 'admin') abort(403);
        $support = SupportMessage::findOrFail($id);
        $support->update([
            'reply'      => $request->reply,
            'replied_at' => now(),
            'is_read'    => true,
        ]);
        if ($support->user_id) {
            Notification::create([
                'user_id' => $support->user_id,
                'title'   => '📩 Réponse du support',
                'message' => $request->reply,
            ]);
        }
        return response()->json(['success' => true]);
    });
});
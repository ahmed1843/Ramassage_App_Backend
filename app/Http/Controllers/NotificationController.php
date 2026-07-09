<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Zone;

class NotificationController extends Controller
{
    // ─────────────────────────────────────────────────────────────
    // 1. Citoyen enregistre son token Expo Push au login/démarrage
    // POST /api/save-push-token
    // ─────────────────────────────────────────────────────────────
    public function savePushToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string|starts_with:ExponentPushToken',
        ]);

        $user = Auth::user();
        $user->expo_push_token = $request->token;
        $user->save();

        return response()->json(['success' => true]);
    }

    // ─────────────────────────────────────────────────────────────
    // 2. Chauffeur met à jour sa position GPS + zone active
    // POST /api/driver/update-position
    // ─────────────────────────────────────────────────────────────
    public function updateDriverPosition(Request $request)
    {
        $request->validate([
            'zone_id'       => 'required|exists:zones,id',
            'latitude'      => 'required|numeric',
            'longitude'     => 'required|numeric',
            'alerte_active' => 'boolean',
        ]);

        $zone = Zone::findOrFail($request->zone_id);
        $zone->current_lat      = $request->latitude;
        $zone->current_lng      = $request->longitude;
        $zone->alerte_active    = $request->alerte_active ?? true;
        $zone->truck_updated_at = now();
        $zone->save();

        if ($zone->alerte_active) {
            $this->notifyZoneCitizens($zone);
        }

        return response()->json([
            'success' => true,
            'zone'    => $zone->name,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // 3. Chauffeur active/désactive manuellement l'alerte
    // POST /api/driver/toggle-alerte
    // ─────────────────────────────────────────────────────────────
    public function toggleAlerte(Request $request)
    {
        $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'actif'   => 'required|boolean',
        ]);

        $zone = Zone::findOrFail($request->zone_id);
        $zone->alerte_active = $request->actif;
        $zone->save();

        if ($request->actif) {
            $this->notifyZoneCitizens($zone);
        }

        return response()->json([
            'success'       => true,
            'alerte_active' => $zone->alerte_active,
            'zone'          => $zone->name,
        ]);
    }
// GET /api/notifications
public function index()
{
    return response()->json(
        Auth::user()->notifications()->orderBy('created_at', 'desc')->get()
    );
}

public function markAsRead($id)
{
    $n = Auth::user()->notifications()->findOrFail($id);
    $n->update(['is_read' => true]);
    return response()->json(['success' => true]);
}

    // ─────────────────────────────────────────────────────────────
    // 4. Retourne les rues disponibles
    // GET /api/streets
    // ─────────────────────────────────────────────────────────────
    public function getStreets()
    {
        $zones = Zone::pluck('name')->toArray();
        if (empty($zones)) {
            return response()->json(['Plateau', 'Almadies', 'Médina']);
        }
        return response()->json($zones);
    }

    // ─────────────────────────────────────────────────────────────
    // 5. Notifier une rue spécifique
    // POST /api/notify-street
    // ─────────────────────────────────────────────────────────────
    public function notifyStreet(Request $request)
    {
        $request->validate([
            'street' => 'required|string',
        ]);

        $zone = Zone::whereRaw('LOWER(name) = LOWER(?)', [$request->street])->first();
        if ($zone) {
            $this->notifyZoneCitizens($zone);
        }

        return response()->json(['success' => true]);
    }

    // ─────────────────────────────────────────────────────────────
    // 6. Assigner une rue à un citoyen
    // POST /api/assign-street
    // ─────────────────────────────────────────────────────────────
    public function assignStreet(Request $request)
    {
        $user = Auth::user();
        $user->street = $request->street;
        $user->save();

        return response()->json(['success' => true]);
    }

    // ─────────────────────────────────────────────────────────────
    // PRIVÉ — Envoie les notifications push aux citoyens de la zone
    // ─────────────────────────────────────────────────────────────
    private function notifyZoneCitizens(Zone $zone)
    {
        // ✅ 'citizen' (pas 'citoyen') + filtrer par street + exclure drivers
        $users = User::where('street', $zone->name)
                    ->whereNotNull('expo_push_token')
                    ->where('role', 'citizen') // ✅ corrigé : citizen pas citoyen
                    ->get();

        if ($users->isEmpty()) {
            Log::info("Aucun citoyen avec token dans la zone: {$zone->name}");
            return;
        }

        $messages = $users->map(fn($user) => [
            'to'    => $user->expo_push_token,
            'sound' => 'default',
            'title' => '🚛 Camion poubelle approche !',
            'body'  => "Le camion arrive dans votre quartier ({$zone->name}). Sortez vos poubelles !",
            'data'  => [
                'zone' => $zone->name,
                'lat'  => $zone->current_lat,
                'lng'  => $zone->current_lng,
                'type' => 'truck_alert',
            ],
            'priority'             => 'high',
            'channelId'            => 'truck-alerts',
            '_displayInForeground' => true,
        ])->values()->all();

        try {
            $response = Http::withHeaders([
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('https://exp.host/--/api/v2/push/send', $messages);

            Log::info("Push envoyé à {$users->count()} citoyens zone {$zone->name}");
Log::info("Réponse Expo: " . $response->body());
        } catch (\Exception $e) {
            Log::error("Erreur envoi push: " . $e->getMessage());
        }
    }
}

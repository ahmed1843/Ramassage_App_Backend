<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Zone;

class NotificationController extends Controller
{
    /**
     * Mettre à jour l'alerte et la position GPS (Appelée par le Driver)
     * Route: POST /alerte-chauffeur
     */
    public function updateAlerte(Request $request)
    {
        try {
            $street = $request->zone_name;
            $isActif = $request->actif; // true ou false
            
            // On récupère la zone ou on la crée si elle n'existe pas
            $zone = Zone::where('name', $street)->first();

            if ($zone) {
                $zone->update([
                    'alerte_active' => $isActif,
                    // On enregistre les coordonnées si elles sont envoyées
                    'current_lat' => $request->current_lat ?? $zone->current_lat,
                    'current_lng' => $request->current_lng ?? $zone->current_lng,
                ]);

                // Si c'est l'activation initiale (arrivée du chauffeur), on notifie les gens
                // On vérifie si c'est un changement d'état pour ne pas spammer à chaque mouvement GPS
                if ($request->has('actif') && !$request->has('current_lat')) {
                    $action = $isActif ? 'arrivee' : 'depart';
                    $this->notifyStreetInternal($street, $action);
                }

                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'message' => 'Zone introuvable'], 404);

        } catch (\Exception $e) {
            Log::error('Erreur alerte-chauffeur: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Vérifier s'il y a une alerte en cours (Appelée par le Citoyen / Map)
     * Route: GET /check-alerte
     */
    public function checkAlerte()
    {
        // On cherche la première zone active (ou tu peux filtrer par la rue de l'utilisateur)
        $activeZone = Zone::where('alerte_active', true)->first();

        if ($activeZone) {
            return response()->json([
                'actif' => true,
                'zone' => $activeZone->name,
                'current_lat' => $activeZone->current_lat,
                'current_lng' => $activeZone->current_lng,
            ]);
        }

        return response()->json(['actif' => false]);
    }

    /**
     * Logique de notification réutilisable
     */
    private function notifyStreetInternal($street, $action)
    {
        $users = User::where('street', $street)->whereNotNull('push_token')->get();

        foreach ($users as $user) {
            $this->sendExpoNotification($user->push_token, $street, $action);
        }
    }

    // --- Garde tes autres méthodes (savePushToken, sendExpoNotification, etc.) sans changement ---

    public function savePushToken(Request $request)
    {
        $user = auth()->user();
        $user->push_token = $request->token;
        $user->save();
        return response()->json(['success' => true]);
    }

    private function sendExpoNotification($pushToken, $street, $action)
    {
        $message = [
            'to' => $pushToken,
            'sound' => 'default',
            'title' => $action === 'arrivee' ? '🚛 Collecte en cours' : '✅ Collecte terminée',
            'body' => $action === 'arrivee' 
                ? "Le camion est dans {$street}. Sortez vos poubelles !"
                : "La collecte est terminée dans {$street}.",
            'data' => ['street' => $street, 'action' => $action]
        ];
        Http::post('https://exp.host/--/api/v2/push/send', $message);
    }
}
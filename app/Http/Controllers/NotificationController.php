<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class NotificationController extends Controller
{
    // Sauvegarder le token push de l'utilisateur
    public function savePushToken(Request $request)
    {
        try {
            $user = auth()->user();
            $user->push_token = $request->token;
            $user->save();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Erreur sauvegarde token: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // Associer un utilisateur à sa rue
    public function assignStreet(Request $request)
    {
        try {
            $user = auth()->user();
            $user->street = $request->street;
            $user->save();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

public function notifyStreet(Request $request)
{
    try {

        $street = $request->street;
        $action = $request->action;

        // ✅ Active ou désactive l'alerte
        \App\Models\Zone::where('name', $street)->update([
            'alerte_active' => ($action === 'arrivee')
        ]);

        // ✅ Récupère les utilisateurs de cette rue
        $users = User::where('street', $street)->get();

        $notifiedCount = 0;

        foreach ($users as $user) {

            // Vérifie si l'utilisateur possède un token Expo
            if ($user->push_token) {

                $this->sendExpoNotification(
                    $user->push_token,
                    $street,
                    $action
                );

                $notifiedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'notified' => $notifiedCount,
            'street' => $street,
            'action' => $action
        ]);

    } catch (\Exception $e) {

        \Log::error($e);

        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}

    // Envoi de la notification via Expo
    private function sendExpoNotification($pushToken, $street, $action)
    {
        $message = [
            'to' => $pushToken,
            'sound' => 'default',
            'title' => $action === 'arrivee' ? '🚛 Collecte en cours' : '✅ Collecte terminée',
            'body' => $action === 'arrivee' 
                ? "Les éboueurs sont dans {$street}. Sortez vos poubelles !"
                : "La collecte est terminée dans {$street}. Merci pour votre contribution !",
            'data' => ['street' => $street, 'action' => $action]
        ];
        
        Http::post('https://exp.host/--/api/v2/push/send', $message);
    }
// Remplace getStreets() par ceci
public function getStreets()
{
    return response()->json(
        \App\Models\Zone::pluck('name') // ← lit les vraies zones de la BDD
    );
}

    // Récupérer les zones pour le driver
    public function getZones()
    {
        $zones = [
            ['id' => 1, 'name' => 'Rue de la Paix', 'habitants' => 45, 'status' => 'pending'],
            ['id' => 2, 'name' => 'Avenue des Champs', 'habitants' => 32, 'status' => 'pending'],
            ['id' => 3, 'name' => 'Boulevard Saint-Germain', 'habitants' => 58, 'status' => 'pending'],
            ['id' => 4, 'name' => 'Rue du Faubourg', 'habitants' => 27, 'status' => 'pending'],
            ['id' => 5, 'name' => 'Place de la République', 'habitants' => 63, 'status' => 'pending'],
        ];
        
        return response()->json($zones);
    }
}
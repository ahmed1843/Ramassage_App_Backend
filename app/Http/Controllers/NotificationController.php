<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // ✅ Permettre au citoyen de voir SES PROPRES notifications
    public function index(Request $request)
    {
        // On utilise $request->user()->id pour avoir l'utilisateur connecté via le Token
        $notifications = Notification::where('user_id', $request->user()->id)
                         ->orderBy('created_at', 'desc')
                         ->get();
                         
        return response()->json($notifications);
    }

    // ✅ Tout marquer comme lu pour l'utilisateur connecté
    public function markAllAsRead(Request $request) 
    {
        Notification::where('user_id', $request->user()->id)
                    ->update(['is_read' => true]);

        return response()->json(['message' => 'Toutes les notifications sont lues']);
    }

    // ✅ Marquer UNE SEULE notification comme lue
    public function markAsRead($id)
    {
        $notification = Notification::find($id);

        if ($notification) {
            $notification->update(['is_read' => true]);
            return response()->json(['message' => 'Notification marquée comme lue']);
        }

        return response()->json(['message' => 'Introuvable'], 404);
    }

    // ✅ Alerte Camion (Chauffeur -> Citoyens)
    public function sendZoneAlert(Request $request, $zoneId)
    {
        $zone = Zone::find($zoneId);
        if (!$zone) return response()->json(['message' => 'Zone non trouvée'], 404);

        $citizens = User::where('role', 'citizen')->get();
        $message = "📢 Alerte : Le camion de ramassage arrive dans la zone {$zone->name}. Veuillez sortir vos bacs sans bruit !";

        foreach ($citizens as $citizen) {
            Notification::create([
                'user_id' => $citizen->id,
                'title'   => "🚛 Passage du camion", // Ajout du titre pour correspondre à ton modèle
                'message' => $message,
                'is_read' => false
            ]);
        }

        return response()->json(["message" => "Alerte envoyée !"], 201);
    }
}

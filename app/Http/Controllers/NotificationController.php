<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Cette méthode remplace le klaxon par un message numérique
    public function sendZoneAlert(Request $request, $zoneId)
    {
        $zone = Zone::find($zoneId);

        if (!$zone) {
            return response()->json(['message' => 'Zone non trouvée'], 404);
        }

        $citizens = User::where('role', 'citizen')->get();

        $message = "📢 Alerte : Le camion de ramassage arrive dans la zone {$zone->name}. Veuillez sortir vos bacs sans bruit !";

        foreach ($citizens as $citizen) {
            Notification::create([
                'user_id' => $citizen->id,
                'message' => $message,
                'is_read' => false
            ]);
        }

        return response()->json([
            'message' => "Alerte envoyée à " . count($citizens) . " citoyens de la zone " . $zone->name,
            'alert' => $message
        ], 201);
    }

    // Permettre au citoyen de voir ses notifications
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', 1)->orderBy('created_at', 'desc')->get();
        return response()->json($notifications);
    }

    // --- NOUVELLE MÉTHODE AJOUTÉE ICI ---
    public function markAsRead($id)
    {
        $notification = Notification::find($id);

        if ($notification) {
            $notification->update(['is_read' => true]);
            return response()->json(['message' => 'Notification marquée comme lue']);
        }

        return response()->json(['message' => 'Introuvable'], 404);
    }
}

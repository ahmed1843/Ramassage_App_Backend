<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    // ✅ Utilisé par le Citoyen (MapScreen) pour voir si un camion arrive
    public function checkAlerte()
    {
        $zoneActive = Zone::where('alerte_active', true)->first();

        if ($zoneActive) {
            return response()->json([
                'actif' => true,
                'zone' => $zoneActive->name,
                'current_lat' => $zoneActive->current_lat,
                'current_lng' => $zoneActive->current_lng,
            ]);
        }

        return response()->json(['actif' => false]);
    }

    // ✅ Utilisé par le Chauffeur pour activer/désactiver l'alerte
    public function toggleAlerte(Request $request)
    {
        $name = trim($request->zone_name);
        $actif = filter_var($request->actif, FILTER_VALIDATE_BOOLEAN);

        $updated = Zone::where('name', $name)->update([
            'alerte_active' => $actif,
            'current_lat' => $request->lat,
            'current_lng' => $request->lng,
        ]);

        return response()->json(['success' => true, 'is_active' => $actif]);
    }

    public function index()
    {
        return response()->json(Zone::all());
    }

    // ... garde tes autres méthodes (store, show) si tu en as besoin
}
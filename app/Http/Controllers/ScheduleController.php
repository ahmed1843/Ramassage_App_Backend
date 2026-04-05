<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    // Récupérer tous les horaires (avec les infos de la zone)
   public function index(Request $request)
{
    // On crée une requête de base sur les horaires
    $query = Schedule::with('zone');

    // Si le binôme envoie un zone_id (ex: ?zone_id=1), on filtre !
    if ($request->has('zone_id')) {
        $query->where('zone_id', $request->zone_id);
    }

    $schedules = $query->get();

    return response()->json($schedules);
}
public function getByZone($zoneId)
{
    $schedule = \App\Models\Schedule::where('zone_id', $zoneId)->first();
    return response()->json($schedule);
}


public function store(Request $request)
{
    $validated = $request->validate([
        'zone_id' => 'required|exists:zones,id',
        'day_of_week' => 'required|string', // ✅ Harmonisé avec ta DB
        'pickup_time' => 'required',        // ✅ Harmonisé avec ta DB
        'truck_name'  => 'nullable|string'
    ]);

    $schedule = Schedule::create($validated);

    return response()->json([
        'message' => 'Horaire ajouté avec succès',
        'data' => $schedule
    ], 201);
}

    }



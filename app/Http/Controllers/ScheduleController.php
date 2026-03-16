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


    // Ajouter un nouvel horaire de passage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'collection_day' => 'required|string', // ex: Lundi
            'start_time' => 'required', // ex: 08:00
            'end_time' => 'required',   // ex: 10:00
        ]);

        $schedule = Schedule::create($validated);

        return response()->json([
            'message' => 'Horaire ajouté avec succès',
            'data' => $schedule
        ], 201);
    }
}

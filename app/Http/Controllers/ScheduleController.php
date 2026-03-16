<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    // Récupérer tous les horaires (avec les infos de la zone)
    public function index()
    {
        $schedules = Schedule::with('zone')->get();
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

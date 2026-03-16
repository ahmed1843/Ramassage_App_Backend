<?php

namespace App\Http\Controllers;

use App\Models\Report; // L'import doit être ICI, avant la classe
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Affiche la liste des rapports.
     */
  public function index()
{
    // Récupère les rapports avec le nom de l'utilisateur et de la zone
    return response()->json(\App\Models\Report::with(['user', 'zone'])->get());
}


    /**
     * Enregistre un nouveau rapport.
     */
  public function store(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'zone_id' => 'required|exists:zones,id',
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'noise_level' => 'nullable|integer|min:1|max:5', // Ajoute bien cette ligne
    ]);

    $report = \App\Models\Report::create($validated);

    return response()->json(['message' => 'Signalement envoyé !', 'data' => $report], 201);
}

    }


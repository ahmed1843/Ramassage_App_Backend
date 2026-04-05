<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    /**
     * Affiche toutes les zones avec leurs horaires (pour le Frontend).
     */
public function index()
{
    // On récupère toutes les zones (tu peux les créer via un Seeder ou Tinker)
    $zones = \App\Models\Zone::all();
    return response()->json($zones);
}

    /**
     * Enregistre une nouvelle zone dans la base de données.
     */
    public function store(Request $request)
    {
        // 1. Validation des données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // 2. Création dans la base de données
        $zone = Zone::create($validated);

        return response()->json([
            'message' => 'Zone créée avec succès !',
            'data' => $zone
        ], 201);
    }

    /**
     * Affiche une seule zone précise.
     */
    public function show(string $id)
    {
        $zone = Zone::with('schedules')->find($id);
        
        if (!$zone) {
            return response()->json(['message' => 'Zone non trouvée'], 404);
        }

        return response()->json($zone);
    }

    public function create() {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}

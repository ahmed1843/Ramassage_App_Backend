<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Notification; // ✅ Importation de la Notification
use Illuminate\Http\Request;
use App\Http\Resources\ReportResource;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Affiche la liste de TOUS les rapports (pour l'admin ou la carte).
     */
public function index() {
    // On prend TOUT sans filtre pour être sûr de ne rien oublier
    return \App\Models\Report::all(); 
}

    /**
     * Enregistre un nouveau rapport AVEC PHOTO.
     */
public function store(Request $request)
{
    // On valide les données entrantes
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'latitude' => 'required',
        'longitude' => 'required',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 2Mo max
    ]);

    $path = null;
    // On gère l'upload de l'image si elle existe
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('reports', 'public');
    }

    // On crée le signalement en base
    $report = \App\Models\Report::create([
        'title' => $request->title,
        'description' => $request->description,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
        'status' => 'pending',
        'image' => $path, // On stocke le chemin du fichier
        'user_id' => auth()->id() ?? 1, // On met 1 par défaut pour le test si pas de login
        'zone_id' => 1
    ]);

    return response()->json([
        'message' => 'Signalement enregistré !',
        'report' => $report
    ], 201);
}


    /**
     * Affiche les rapports de l'utilisateur connecté.
     */
    public function myReports(Request $request)
    {
        try {
            $reports = Report::where('user_id', $request->user()->id)
                             ->with('zone')
                             ->latest()
                             ->get();

            return ReportResource::collection($reports);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
public function getMapData()
{
    // On récupère les signalements qui ont des coordonnées (latitude/longitude)
    return Report::select('id', 'title', 'latitude', 'longitude', 'status')
                 ->whereNotNull('latitude')
                 ->get();
}


    /**
     * Met à jour le statut et crée une notification (Simule l'Admin).
     */
    public function update(Request $request, $id) 
    {
        $report = Report::findOrFail($id);
        $oldStatus = $report->status;
        
        $report->update(['status' => $request->status]);

        // ✅ Si le statut change, on prévient l'utilisateur
        if ($oldStatus !== $request->status) {
            Notification::create([
                'user_id' => $report->user_id,
                'title'   => "Mise à jour de votre signalement",
                'message' => "Votre signalement à " . ($report->location ?? 'Dakar') . " est désormais : " . $request->status,
                'is_read' => false
            ]);
        }

        return response()->json($report);
    }
}


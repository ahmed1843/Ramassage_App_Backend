<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function store(Request $request)
{
    try {
        // Affiche les données reçues dans les logs
        \Log::info('Données reçues:', $request->all());
        \Log::info('Fichier reçu:', $request->hasFile('photo') ? ['oui' => true, 'name' => $request->file('photo')->getClientOriginalName()] : ['non' => false]);
        
        $validated = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:5|max:1000',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $report = new Report();
        $report->title = $validated['title'];
        $report->description = $validated['description'];
        $report->location = $validated['location'];
        $report->status = 'pending';
        
        if ($request->user()) {
            $report->user_id = $request->user()->id;
        }
        
      if ($request->hasFile('image')) {
    $photoPath = $request->file('image')->store('reports/' . date('Y/m/d'), 'public');
    $report->photo_path = $photoPath;
}
        
        $report->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Signalement envoyé avec succès !',
            'data' => $report
        ], 201);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Erreur validation:', $e->errors());
        return response()->json([
            'success' => false,
            'message' => 'Erreur de validation',
            'errors' => $e->errors()
        ], 422);
        
    } catch (\Exception $e) {
        // Retourne l'erreur complète
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ], 500);
    }
}
    
    // Récupérer tous les signalements
  public function index(Request $request)
{
    // On récupère uniquement les signalements appartenant à l'utilisateur connecté
    // On utilise la relation définie dans le modèle User (si elle existe) 
    // ou on filtre par user_id
    $reports = Report::where('user_id', $request->user()->id)
                     ->latest()
                     ->paginate(20);
    
    return response()->json([
        'success' => true,
        'data' => $reports
    ]);
}
    
    // Récupérer un signalement spécifique
    public function show($id)
    {
        $report = Report::with('user')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }
    
    // Mettre à jour le statut (ex: pour les admins/chauffeurs)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved'
        ]);
        
        $report = Report::findOrFail($id);
        $report->status = $request->status;
        $report->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour',
            'data' => $report
        ]);
    }
}
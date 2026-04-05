<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Resources\ReportResource;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index() {
        return Report::all(); 
    }

    /**
     * Enregistre un nouveau rapport AVEC PHOTO.
     */
    public function store(Request $request)
    {
        try {
            // ✅ CORRECTIF : On définit les règles obligatoires
            $validated = $request->validate([
                'title'       => 'required|string',
                'description' => 'nullable|string',
                'latitude'    => 'required',
                'longitude'   => 'required',
            ]);

            // On ajoute manuellement les données système
            $validated['user_id'] = auth()->id() ?? 1; 
            $validated['zone_id'] = $request->zone_id ?? 1;
            $validated['status']  = 'pending';

            // ✅ Gérer l'image si elle est présente dans la requête
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('reports', 'public');
                $validated['image'] = $path; // Assure-toi que la colonne s'appelle 'image' dans ta DB
            }

            $report = Report::create($validated);

            return response()->json([
                'message' => 'Signalement créé !',
                'data' => $report
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Retourne les erreurs de validation précises (ex: titre manquant)
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

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
        return Report::select('id', 'title', 'latitude', 'longitude', 'status')
                     ->whereNotNull('latitude')
                     ->get();
    }

    public function update(Request $request, $id) 
    {
        $report = Report::findOrFail($id);
        $oldStatus = $report->status;
        
        $report->update(['status' => $request->status]);

        if ($oldStatus !== $request->status) {
            Notification::create([
                'user_id' => $report->user_id,
                'title'   => "Mise à jour de votre signalement",
                'message' => "Votre signalement est désormais : " . $request->status,
                'is_read' => false
            ]);
        }

        return response()->json($report);
    }
}

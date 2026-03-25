<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Http\Resources\ReportResource;
use Illuminate\Support\Facades\Storage; // Import indispensable pour les photos


class ReportController extends Controller
{
    /**
     * Affiche la liste de TOUS les rapports (pour l'admin ou la carte).
     */
    public function index() 
    {
        // On récupère les rapports avec l'utilisateur et la zone
        $reports = Report::with(['user', 'zone'])->latest()->get();
        return ReportResource::collection($reports);
    }

    /**
     * Enregistre un nouveau rapport AVEC PHOTO.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'zone_id'     => 'required|exists:zones,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'noise_level' => 'nullable|integer|min:1|max:5',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Validation photo
        ]);

        // Sécurité : ID de l'user connecté
        $validated['user_id'] = $request->user()->id;

        // --- GESTION DE L'IMAGE ---
        if ($request->hasFile('image')) {
            // Enregistre dans storage/app/public/reports
            $path = $request->file('image')->store('reports', 'public');
            $validated['image'] = $path; // On stocke le chemin dans la DB

        }

        $report = Report::create($validated);

        return new ReportResource($report);
    }

    /**
     * Affiche les rapports de l'utilisateur connecté.
     */
    public function myReports(Request $request)
    {
        $reports = Report::where('user_id', $request->user()->id)
                         ->with('zone')
                         ->latest()
                         ->get();

        return ReportResource::collection($reports);
    }
}

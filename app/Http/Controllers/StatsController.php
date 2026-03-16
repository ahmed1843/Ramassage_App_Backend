<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function noiseStats()
    {
        // On récupère les zones avec la moyenne des niveaux de bruit de leurs rapports
        $stats = Zone::withAvg('reports', 'noise_level')
                     ->withCount('reports')
                     ->get();

        return response()->json([
            'message' => 'Statistiques de nuisances récupérées',
            'data' => $stats
        ]);
    }
}

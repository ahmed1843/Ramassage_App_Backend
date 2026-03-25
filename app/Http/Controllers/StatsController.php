<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function noiseStats()
    {
      
    $stats = Zone::withAvg('reports', 'noise_level')
                 ->withCount('reports')
                 ->orderBy('reports_avg_noise_level', 'desc') // Les plus bruyantes en haut
                 ->get();

    return response()->json([
        'message' => 'Statistiques de nuisances récupérées',
        'data' => $stats
        ]);
    }
}

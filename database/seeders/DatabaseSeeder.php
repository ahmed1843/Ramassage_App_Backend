<?php

namespace Database\Seeders;

use App\Models\Zone;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Création des Zones
        $medina = Zone::create([
            'name' => 'Médina',
            'status' => 'critical',
            'description' => 'Zone à forte densité, besoin de ramassage urgent.'
        ]);

        $plateau = Zone::create([
            'name' => 'Plateau',
            'status' => 'clean',
            'description' => 'Centre-ville, ramassage effectué ce matin.'
        ]);

        $almadies = Zone::create([
            'name' => 'Almadies',
            'status' => 'clean',
            'description' => 'Zone résidentielle.'
        ]);

        // 2. Création des Horaires liés aux bonnes zones
        Schedule::create([
            'zone_id' => $medina->id,
            'day_of_week' => 'Quotidien',
            'pickup_time' => '18:00:00'
        ]);

        Schedule::create([
            'zone_id' => $plateau->id,
            'day_of_week' => 'Lundi/Jeudi',
            'pickup_time' => '07:30:00'
        ]);
        
        Schedule::create([
            'zone_id' => $almadies->id,
            'day_of_week' => 'Mercredi/Samedi',
            'pickup_time' => '09:00:00'
        ]);
    }
}

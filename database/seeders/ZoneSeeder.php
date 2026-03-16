<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Zone::create([
    'name' => 'Médina - Zone A',
    'description' => 'Passage du camion à 8h le lundi'
]);

    }
}

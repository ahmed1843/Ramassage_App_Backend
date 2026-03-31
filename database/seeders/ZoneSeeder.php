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
    \App\Models\Zone::create(['id' => 1, 'name' => 'Médina']);
    \App\Models\Zone::create(['id' => 2, 'name' => 'Plateau']);
}

}


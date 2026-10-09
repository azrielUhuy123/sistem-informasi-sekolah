<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KelasMaster;

class KelasMasterSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['I', 'II', 'III', 'IV', 'V', 'VI'] as $tingkat) {
            KelasMaster::firstOrCreate([
                'tingkat' => $tingkat,
            ]);
        }
    }
}

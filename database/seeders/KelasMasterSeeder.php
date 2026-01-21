<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KelasMaster;

class KelasMasterSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 6) as $tingkat) {
            KelasMaster::firstOrCreate([
                'tingkat' => $tingkat,
            ]);
        }
    }
}

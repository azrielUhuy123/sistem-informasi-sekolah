<?php

namespace Database\Seeders;

use App\Models\Angkatan;
use App\Models\KelasGrup;
use App\Models\KelasMaster;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class MasterAkademikAwalSeeder extends Seeder
{
    public function run(): void
    {
        TahunAjaran::updateOrCreate(
            [
                'tahun' => '2026/2027',
                'semester' => 'Ganjil',
            ],
            [
                'status' => 'aktif',
            ]
        );

        $angkatan = Angkatan::firstOrCreate([
            'tahun' => 2026,
        ]);

        $this->call(KelasMasterSeeder::class);

        foreach (['I', 'II', 'III', 'IV', 'V', 'VI'] as $tingkat) {
            $kelasMaster = KelasMaster::where('tingkat', $tingkat)->firstOrFail();

            KelasGrup::firstOrCreate([
                'angkatan_id' => $angkatan->id,
                'kelas_master_id' => $kelasMaster->id,
            ]);
        }
    }
}
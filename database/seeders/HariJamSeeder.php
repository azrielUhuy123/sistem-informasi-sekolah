<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HariJamSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('hari_sekolah')->insert([
            ['nama' => 'Senin', 'urutan' => 1],
            ['nama' => 'Selasa', 'urutan' => 2],
            ['nama' => 'Rabu', 'urutan' => 3],
            ['nama' => 'Kamis', 'urutan' => 4],
            ['nama' => 'Jumat', 'urutan' => 5],
        ]);

        for ($i = 1; $i <= 8; $i++) {
            DB::table('jam_pelajaran')->insert([
                'jam_ke' => $i,
                'mulai' => now()->setTime(7 + $i, 0),
                'selesai' => now()->setTime(7 + $i, 45),
            ]);
        }
    }
}
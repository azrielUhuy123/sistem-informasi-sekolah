<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MataPelajaran;

class MataPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        $mapels = [
            ['kode'=>'MTK','nama_mapel'=>'Matematika','kkm'=>70],
            ['kode'=>'BIND','nama_mapel'=>'Bahasa Indonesia','kkm'=>70],
            ['kode'=>'BING','nama_mapel'=>'Bahasa Inggris','kkm'=>70],
            ['kode'=>'IPA','nama_mapel'=>'IPA','kkm'=>70],
            ['kode'=>'IPS','nama_mapel'=>'IPS','kkm'=>70],
            ['kode'=>'TIK','nama_mapel'=>'TIK','kkm'=>70],
        ];

        foreach ($mapels as $m) {
            MataPelajaran::updateOrCreate(['kode'=>$m['kode']], $m);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\DB;

class MataPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('mata_pelajaran')->delete();

        DB::table('mata_pelajaran')->insert([
            ['kode_mapel'=>'MTK','nama_mapel'=>'Matematika','kkm'=>70],
            ['kode_mapel'=>'BIND','nama_mapel'=>'Bahasa Indonesia','kkm'=>70],
            ['kode_mapel'=>'BING','nama_mapel'=>'Bahasa Inggris','kkm'=>70],
            ['kode_mapel'=>'IPA','nama_mapel'=>'IPA','kkm'=>70],
            ['kode_mapel'=>'IPS','nama_mapel'=>'IPS','kkm'=>70],
            ['kode_mapel'=>'PKN','nama_mapel'=>'PKN','kkm'=>70],
            ['kode_mapel'=>'AGM','nama_mapel'=>'Agama','kkm'=>70],
            ['kode_mapel'=>'OR','nama_mapel'=>'Olahraga','kkm'=>70],
            ['kode_mapel'=>'SB','nama_mapel'=>'Seni Budaya','kkm'=>70],
            ['kode_mapel'=>'INF','nama_mapel'=>'Informatika','kkm'=>70],
        ]);

    }
}

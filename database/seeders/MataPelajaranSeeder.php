<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MataPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('mata_pelajaran')->truncate();

        DB::table('mata_pelajaran')->insert([

            [
                'kode_mapel' => 'BIND',
                'nama_mapel' => 'Bahasa Indonesia',
                'minimal_fase' => 'A',
                'kategori' => 'inti',
                'is_wajib' => true,
                'jp_per_minggu' => 6,
                'warna' => '#198754',
            ],

            [
                'kode_mapel' => 'MTK',
                'nama_mapel' => 'Matematika',
                'minimal_fase' => 'A',
                'kategori' => 'inti',
                'is_wajib' => true,
                'jp_per_minggu' => 5,
                'warna' => '#0d6efd',
            ],

            [
                'kode_mapel' => 'PABP',
                'nama_mapel' => 'Pendidikan Agama dan Budi Pekerti',
                'minimal_fase' => 'A',
                'kategori' => 'inti',
                'is_wajib' => true,
                'jp_per_minggu' => 3,
                'warna' => '#ffc107',
            ],

            [
                'kode_mapel' => 'PKN',
                'nama_mapel' => 'Pendidikan Pancasila',
                'minimal_fase' => 'A',
                'kategori' => 'inti',
                'is_wajib' => true,
                'jp_per_minggu' => 3,
                'warna' => '#dc3545',
            ],

            [
                'kode_mapel' => 'IPAS',
                'nama_mapel' => 'Ilmu Pengetahuan Alam dan Sosial',
                'minimal_fase' => 'B',
                'kategori' => 'inti',
                'is_wajib' => true,
                'jp_per_minggu' => 4,
                'warna' => '#20c997',
            ],

            [
                'kode_mapel' => 'PJOK',
                'nama_mapel' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan',
                'minimal_fase' => 'A',
                'kategori' => 'inti',
                'is_wajib' => true,
                'jp_per_minggu' => 2,
                'warna' => '#fd7e14',
            ],

            [
                'kode_mapel' => 'SENI',
                'nama_mapel' => 'Seni dan Budaya',
                'minimal_fase' => 'A',
                'kategori' => 'inti',
                'is_wajib' => true,
                'jp_per_minggu' => 2,
                'warna' => '#6f42c1',
            ],

            [
                'kode_mapel' => 'BING',
                'nama_mapel' => 'Bahasa Inggris',
                'minimal_fase' => 'C',
                'kategori' => 'tambahan',
                'is_wajib' => false,
                'jp_per_minggu' => 2,
                'warna' => '#6610f2',
            ],

            [
                'kode_mapel' => 'KKAI',
                'nama_mapel' => 'Koding dan Kecerdasan Artifisial',
                'minimal_fase' => 'C',
                'kategori' => 'tambahan',
                'is_wajib' => false,
                'jp_per_minggu' => 2,
                'warna' => '#0dcaf0',
            ],

            [
                'kode_mapel' => 'MULOK',
                'nama_mapel' => 'Muatan Lokal',
                'minimal_fase' => 'A',
                'kategori' => 'lokal',
                'is_wajib' => false,
                'jp_per_minggu' => 2,
                'warna' => '#6c757d',
            ],

        ]);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataKepegawaian extends Model
{
    protected $table = 'data_kepegawaian';

    protected $fillable = [
        // IDENTITAS
        'nama','nip','nuptk','tempat_lahir','tanggal_lahir',
        'agama','jenis_kelamin',

        // STATUS
        'status_kepegawaian',

        // IJAZAH
        'ijazah_terakhir',

        // SK
        'sk_terakhir_no','sk_terakhir_tmt',
        'sk_berkala_no','sk_berkala_tmt',
        'sk_terakhir_pangkat',
        'sk_berkala_pangkat',
        'status_kawin',

        // PEKERJAAN
        'jabatan','mulai_kerja',
        'masa_kerja_golongan','masa_kerja_keseluruhan',
        'masa_kerja_gol_th',
        'masa_kerja_gol_bln',
        'masa_kerja_total_th',
        'masa_kerja_total_bln',

        // MENGAJAR
        'mengajar_kelas','jam_mengajar',

        // GAJI
        'gaji_pokok',

        // TAMBAHAN
        'tugas_tambahan',
        'nomor_sertifikasi','tahun_sertifikasi',
        'keterangan'
    ];

    // =========================
    // CASTING (PENTING)
    // =========================
    protected $casts = [
        'tanggal_lahir' => 'date',
        'sk_terakhir_tmt' => 'date',
        'sk_berkala_tmt' => 'date',
        'mulai_kerja' => 'date',

        'masa_kerja_golongan' => 'integer',
        'masa_kerja_keseluruhan' => 'integer',
        'jam_mengajar' => 'integer',

        'gaji_pokok' => 'decimal:2',
        'tahun_sertifikasi' => 'integer',
    ];
}
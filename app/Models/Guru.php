<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $fillable = [
        'nama',
        'nip',
        'jenis_kelamin',
        'email',
        'telepon',
        'alamat',
        'foto',
        'mapel_id', // gunakan mapel_id sebagai FK ke tabel mata_pelajaran
        // jika masih ada kolom 'mapel' (string) biarkan sampai migrasi & data selesai
    ];

    /**
     * Relasi ke mata pelajaran.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mapel(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }
}

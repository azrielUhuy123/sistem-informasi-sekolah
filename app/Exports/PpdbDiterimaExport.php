<?php

namespace App\Exports;

use App\Models\PpdbPendaftar;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PpdbDiterimaExport implements FromCollection, WithHeadings, WithMapping
{
    private int $tahunAjaranId;

    private int $nomorUrut = 0;

    public function __construct(int $tahunAjaranId)
    {
        $this->tahunAjaranId = $tahunAjaranId;
    }

    public function collection(): Collection
    {
        return PpdbPendaftar::query()
            ->where('status', 'diterima')
            ->where('tahun_ajaran_id', $this->tahunAjaranId)
            ->orderBy('id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'No. Pendaftaran',
            'Nama Lengkap',
            'NIK',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Asal Sekolah',
            'Alamat',
            'Status Daftar Ulang',
        ];
    }

    public function map($pendaftar): array
    {
        $this->nomorUrut++;

        return [
            $this->nomorUrut,
            $pendaftar->no_pendaftaran,
            $pendaftar->nama,
            "'{$pendaftar->nik}",
            $pendaftar->jenis_kelamin,
            $pendaftar->tempat_lahir,
            $pendaftar->tanggal_lahir
                ? Carbon::parse($pendaftar->tanggal_lahir)->format('d-m-Y')
                : null,
            $pendaftar->nama_sekolah_asal,
            $pendaftar->alamat,
            $pendaftar->status_daftar_ulang === 'sudah'
                ? 'Sudah Daftar Ulang'
                : 'Belum Daftar Ulang',
        ];
    }
}
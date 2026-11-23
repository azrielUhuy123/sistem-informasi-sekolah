<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RekapPpdbExport implements FromCollection, WithHeadings, WithMapping
{
    private int $nomorUrut = 0;

    public function __construct(private readonly Collection $pendaftar)
    {
    }

    public function collection(): Collection
    {
        return $this->pendaftar;
    }

    public function headings(): array
    {
        return [
            'No',
            'No. Pendaftaran',
            'Nama Lengkap',
            'NIK',
            'Jenis Kelamin',
            'Tanggal Pendaftaran',
            'Tahun Ajaran',
            'Status',
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
            $pendaftar->tanggal_pendaftaran
                ? Carbon::parse($pendaftar->tanggal_pendaftaran)->format('d-m-Y')
                : null,
            $pendaftar->tahunAjaran?->tahun,
            $pendaftar->status,
        ];
    }
}
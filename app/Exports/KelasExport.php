<?php

namespace App\Exports;

use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use App\Models\TahunAjaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KelasExport implements FromCollection, WithHeadings, WithMapping
{
    protected $kelasGrup;

    public function __construct($kelasGrup)
    {
        $this->kelasGrup = $kelasGrup;
    }

    public function collection()
    {
        $tahunAktif = TahunAjaran::current();

        $kelasIds = Kelas::where(
            'kelas_grup_id',
            $this->kelasGrup->id
        )->pluck('id');

        return PenempatanSiswa::with([
                'siswa',
                'kelas',
            ])
            ->whereIn('kelas_id', $kelasIds)
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->whereNull('tanggal_keluar')
            ->orderBy('kelas_id')
            ->orderBy('id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'NIS',
            'NISN',
            'Nama',
            'Kelas',
            'JK',
            'Tempat Lahir',
            'Tanggal Lahir',
            'NIK',
            'Agama',
            'Alamat',
        ];
    }

    public function map($item): array
    {
        static $no = 0;

        return [
            ++$no,
            $item->siswa?->nis,
            $item->siswa?->nisn,
            $item->siswa?->nama,
            $item->kelas?->nama_kelas,
            $item->siswa?->jenis_kelamin,
            $item->siswa?->tempat_lahir,
            optional($item->siswa?->tanggal_lahir)->format('d/m/Y'),
            $item->siswa?->nik,
            $item->siswa?->agama,
            $item->siswa?->alamat,
        ];
    }
}
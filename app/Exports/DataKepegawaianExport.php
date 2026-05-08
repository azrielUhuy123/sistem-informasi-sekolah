<?php

namespace App\Exports;

use App\Models\DataKepegawaian;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class DataKepegawaianExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithColumnFormatting
{
    public function collection()
    {
        return DataKepegawaian::select(

            'nama',
            'nip',
            'tempat_lahir',
            'tanggal_lahir',

            'jabatan',
            'status_kepegawaian',

            'ijazah_terakhir',

            'mengajar_kelas',
            'pengalaman_tahun',

            'gaji_pokok',

            'nuptk',
            'agama',
            'jenis_kelamin',
            'status_kawin',

            'sk_terakhir_no',
            'sk_terakhir_tmt',

            'sk_berkala_no',
            'sk_berkala_tmt',

            'mulai_kerja',

            'masa_kerja_golongan',
            'masa_kerja_keseluruhan',

            'jam_mengajar',

            'tugas_tambahan',

            'nomor_sertifikasi',
            'tahun_sertifikasi',

            'keterangan',

            'sk_terakhir_pangkat',
            'sk_berkala_pangkat',

            'masa_kerja_gol_th',
            'masa_kerja_gol_bln',

            'masa_kerja_total_th',
            'masa_kerja_total_bln',

            'approval_status'

        )->get();
    }

    public function map($row): array
    {
        return [

            $row->nama,

            "'" . $row->nip,

            $row->tempat_lahir,

            $row->tanggal_lahir
                ? Carbon::parse($row->tanggal_lahir)
                    ->format('d-m-Y')
                : '-',

            $row->jabatan,

            $row->status_kepegawaian,

            $row->ijazah_terakhir,

            $row->mengajar_kelas,

            $row->pengalaman_tahun,

            $row->gaji_pokok,

            "'" . $row->nuptk,

            $row->agama,

            $row->jenis_kelamin,

            $row->status_kawin,

            $row->sk_terakhir_no,

            $row->sk_terakhir_tmt
                ? Carbon::parse($row->sk_terakhir_tmt)
                    ->format('d-m-Y')
                : '-',

            $row->sk_berkala_no,

            $row->sk_berkala_tmt
                ? Carbon::parse($row->sk_berkala_tmt)
                    ->format('d-m-Y')
                : '-',

            $row->mulai_kerja
                ? Carbon::parse($row->mulai_kerja)
                    ->format('d-m-Y')
                : '-',

            $row->masa_kerja_golongan,

            $row->masa_kerja_keseluruhan,

            $row->jam_mengajar,

            $row->tugas_tambahan,

            "'" . $row->nomor_sertifikasi,

            $row->tahun_sertifikasi,

            $row->keterangan,

            $row->sk_terakhir_pangkat,

            $row->sk_berkala_pangkat,

            $row->masa_kerja_gol_th,

            $row->masa_kerja_gol_bln,

            $row->masa_kerja_total_th,

            $row->masa_kerja_total_bln,

            $row->approval_status,

        ];
    }

    public function headings(): array
    {
        return [

            'Nama',
            'NIP',
            'Tempat Lahir',
            'Tanggal Lahir',

            'Jabatan',
            'Status Kepegawaian',

            'Ijazah Terakhir',

            'Mengajar Kelas',
            'Pengalaman Tahun',

            'Gaji Pokok',

            'NUPTK',
            'Agama',
            'Jenis Kelamin',
            'Status Kawin',

            'No SK Terakhir',
            'TMT SK Terakhir',

            'No SK Berkala',
            'TMT SK Berkala',

            'Mulai Kerja',

            'Masa Kerja Golongan',
            'Masa Kerja Keseluruhan',

            'Jam Mengajar',

            'Tugas Tambahan',

            'Nomor Sertifikasi',
            'Tahun Sertifikasi',

            'Keterangan',

            'Pangkat SK Terakhir',
            'Pangkat SK Berkala',

            'Masa Kerja Golongan (TH)',
            'Masa Kerja Golongan (BLN)',

            'Masa Kerja Total (TH)',
            'Masa Kerja Total (BLN)',

            'Status Approval',

        ];
    }

    public function columnFormats(): array
    {
        return [

            'B' => NumberFormat::FORMAT_TEXT, // NIP
            'K' => NumberFormat::FORMAT_TEXT, // NUPTK
            'X' => NumberFormat::FORMAT_TEXT, // Nomor Sertifikasi

        ];
    }
}
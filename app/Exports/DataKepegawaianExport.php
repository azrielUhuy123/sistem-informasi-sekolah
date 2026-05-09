<?php

namespace App\Exports;

use App\Models\DataKepegawaian;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class DataKepegawaianExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithColumnFormatting,
    WithStyles,
    ShouldAutoSize
{
    public function collection()
    {
        return DataKepegawaian::select(

            'nama',
            'nip',
            'nuptk',

            'tempat_lahir',
            'tanggal_lahir',

            'agama',
            'status_kawin',
            'jenis_kelamin',

            'status_kepegawaian',
            'jabatan',

            'ijazah_terakhir',

            'sk_terakhir_no',
            'sk_terakhir_tmt',
            'sk_terakhir_pangkat',

            'sk_berkala_no',
            'sk_berkala_tmt',
            'sk_berkala_pangkat',

            'mulai_kerja',

            'masa_kerja_golongan',
            'masa_kerja_keseluruhan',

            'masa_kerja_gol_th',
            'masa_kerja_gol_bln',

            'masa_kerja_total_th',
            'masa_kerja_total_bln',

            'mengajar_kelas',
            'pengalaman_tahun',
            'jam_mengajar',

            'tugas_tambahan',

            'nomor_sertifikasi',
            'tahun_sertifikasi',

            'gaji_pokok',

            'keterangan',

            'approval_status'

        )->get();
    }

    public function map($row): array
    {
        return [

            $row->nama,

            "'" . $row->nip,

            "'" . $row->nuptk,

            $row->tempat_lahir,

            $row->tanggal_lahir
                ? Carbon::parse($row->tanggal_lahir)->format('d-m-Y')
                : '-',

            $row->agama,

            $row->status_kawin,

            $row->jenis_kelamin,

            $row->status_kepegawaian,

            $row->jabatan,

            $row->ijazah_terakhir,

            $row->sk_terakhir_no,

            $row->sk_terakhir_tmt
                ? Carbon::parse($row->sk_terakhir_tmt)->format('d-m-Y')
                : '-',

            $row->sk_terakhir_pangkat,

            $row->sk_berkala_no,

            $row->sk_berkala_tmt
                ? Carbon::parse($row->sk_berkala_tmt)->format('d-m-Y')
                : '-',

            $row->sk_berkala_pangkat,

            $row->mulai_kerja
                ? Carbon::parse($row->mulai_kerja)->format('d-m-Y')
                : '-',

            $row->masa_kerja_golongan,

            $row->masa_kerja_keseluruhan,

            $row->masa_kerja_gol_th,

            $row->masa_kerja_gol_bln,

            $row->masa_kerja_total_th,

            $row->masa_kerja_total_bln,

            $row->mengajar_kelas,

            $row->pengalaman_tahun,

            $row->jam_mengajar,

            $row->tugas_tambahan,

            "'" . $row->nomor_sertifikasi,

            $row->tahun_sertifikasi,

            number_format($row->gaji_pokok, 0, ',', '.'),

            $row->keterangan,

            strtoupper($row->approval_status),

        ];
    }

    public function headings(): array
    {
        return [

            'Nama',
            'NIP',
            'NUPTK',

            'Tempat Lahir',
            'Tanggal Lahir',

            'Agama',
            'Status Kawin',
            'Jenis Kelamin',

            'Status Kepegawaian',
            'Jabatan',

            'Ijazah Terakhir',

            'No SK Terakhir',
            'TMT SK Terakhir',
            'Pangkat SK Terakhir',

            'No SK Berkala',
            'TMT SK Berkala',
            'Pangkat SK Berkala',

            'Mulai Kerja',

            'Masa Kerja Golongan',
            'Masa Kerja Keseluruhan',

            'Masa Kerja Golongan (TH)',
            'Masa Kerja Golongan (BLN)',

            'Masa Kerja Total (TH)',
            'Masa Kerja Total (BLN)',

            'Mengajar Kelas',
            'Pengalaman Tahun',
            'Jam Mengajar',

            'Tugas Tambahan',

            'Nomor Sertifikasi',
            'Tahun Sertifikasi',

            'Gaji Pokok',

            'Keterangan',

            'Status Approval',

        ];
    }

    public function columnFormats(): array
    {
        return [

            'B'  => NumberFormat::FORMAT_TEXT,
            'C'  => NumberFormat::FORMAT_TEXT,
            'AD' => NumberFormat::FORMAT_TEXT,

        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // HEADER
        $sheet->getStyle('A1:AH1')->applyFromArray([

            'font' => [
                'bold'  => true,
                'color' => [
                    'rgb' => '000000'
                ],
                'size' => 11,
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'D9EAD3'
                ],
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],

            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],

        ]);

        // ISI TABLE
        $sheet->getStyle('A2:AH' . $highestRow)->applyFromArray([

            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],

            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],

        ]);

        // CENTER BEBERAPA KOLOM
        $sheet->getStyle('B:Z')->getAlignment()->setHorizontal(
            Alignment::HORIZONTAL_CENTER
        );

        // TINGGI HEADER
        $sheet->getRowDimension(1)->setRowHeight(35);

        // FREEZE HEADER
        $sheet->freezePane('A2');

        return [];
    }
}
<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanSurat;
use setasign\Fpdi\Fpdi;


class ValidasiSuratController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $pengajuan = PengajuanSurat::latest()->get();

        return view(
            'superadmin.pengajuan-surat.index',
            compact('pengajuan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        return view(
            'superadmin.pengajuan-surat.show',
            compact('pengajuan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDASI
    |--------------------------------------------------------------------------
    */
    public function validasi($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        // FILE ASLI
        $sourcePath = public_path(
            'uploads/pengajuan-surat/' . $pengajuan->file_surat
        );

        // CEK FILE ADA
        if (!file_exists($sourcePath)) {

            dd('FILE TIDAK DITEMUKAN', $sourcePath);

        }

        // FILE HASIL VALIDASI
        $outputFileName = 'validated_' . time() . '.pdf';

        $outputPath = public_path(
            'uploads/pengajuan-surat/validated/' . $outputFileName
        );

        // BUAT FOLDER JIKA BELUM ADA
        if (!file_exists(public_path('uploads/pengajuan-surat/validated'))) {

            mkdir(
                public_path('uploads/pengajuan-surat/validated'),
                0777,
                true
            );
        }

        // LOAD PDF
        $pdf = new \setasign\Fpdi\Fpdi();

        $pageCount = $pdf->setSourceFile($sourcePath);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {

            $template = $pdf->importPage($pageNo);

            $size = $pdf->getTemplateSize($template);

            $pdf->AddPage(
                $size['orientation'],
                [$size['width'], $size['height']]
            );

            $pdf->useTemplate($template);

            // TTD DI HALAMAN TERAKHIR
            if ($pageNo == $pageCount) {

                $pdf->Image(
                    public_path('ttd/ttd.png'),
                    140,
                    220,
                    40
                );

                $pdf->SetFont('Arial', '', 11);

                $pdf->SetXY(140, 250);

                $pdf->Cell(
                    50,
                    10,
                    'Kepala Sekolah'
                );
            }
        }

        // SIMPAN FILE
        $pdf->Output($outputPath, 'F');

        // UPDATE DATABASE
        $pengajuan->update([

            'status' => 'disetujui',

            'validated_by' => auth()->id(),

            'validated_at' => now(),

            'file_final' => $outputFileName

        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Surat berhasil divalidasi & ditandatangani'
            );
    }

        /*
        |--------------------------------------------------------------------------
    | TOLAK
    |--------------------------------------------------------------------------
    */
    public function tolak($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        $pengajuan->update([

            'status' => 'ditolak'

        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Pengajuan surat ditolak'
            );
    }
}
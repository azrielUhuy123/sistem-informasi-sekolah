<?php

namespace App\Http\Controllers\TU;

use App\Http\Controllers\Controller;
use App\Models\DataKepegawaian;
use App\Exports\DataKepegawaianExport;
use Maatwebsite\Excel\Facades\Excel;

class DataKepegawaianExportController extends Controller
{
    public function export()
    {
        $pending = DataKepegawaian::where('approval_status', 'pending')->count();

        $rejected = DataKepegawaian::where('approval_status', 'rejected')->count();

        if ($pending > 0 || $rejected > 0) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Masih terdapat data yang belum disetujui. Selesaikan approval terlebih dahulu sebelum export data.'
                );
        }

        return Excel::download(
            new DataKepegawaianExport,
            'data-kepegawaian.xlsx'
        );
    }
}
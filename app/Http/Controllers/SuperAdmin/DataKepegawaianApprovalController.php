<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\DataKepegawaian;
use Illuminate\Http\Request;

class DataKepegawaianApprovalController extends Controller
{
    public function index()
    {
        $data = DataKepegawaian::all();

        return view(
            'superadmin.datakepegawaian.approval',
            compact('data')
        );
    }

    public function approve($id)
{
    $data = DataKepegawaian::findOrFail($id);

    $data->update([
        'approval_status' => 'approved',
        'rejected_reason' => null,
    ]);

    return redirect()
        ->route('superadmin.datakepegawaian.approval')
        ->with('success', 'Data berhasil diapprove');
}

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejected_reason' => 'required|string'
        ]);

        $pegawai = DataKepegawaian::findOrFail($id);

        $pegawai->update([
            'approval_status' => 'rejected',
            'rejected_reason' => $request->rejected_reason,
            'rejected_at' => now(),
            'is_message_read' => false,
        ]);

        return redirect()
            ->route('superadmin.datakepegawaian.approval')
            ->with('success', 'Data berhasil direject');
    }

    public function show($id)
    {
        $pegawai = DataKepegawaian::findOrFail($id);

        return view(
            'superadmin.datakepegawaian.detail',
            compact('pegawai')
        );
    }
}
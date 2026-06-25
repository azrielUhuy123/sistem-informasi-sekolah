<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\DataKepegawaian;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Guru;
use App\Enums\UserRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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
        DB::transaction(function () use ($id) {

            $pegawai = DataKepegawaian::findOrFail($id);

            $pegawai->update([
                'approval_status' => 'approved',
                'rejected_reason' => null,
            ]);

            // Jika sudah punya akun, tidak perlu dibuat lagi
            if ($pegawai->user) {
                return;
            }

            // Mapping Role berdasarkan Jabatan
            $jabatan = strtolower(trim($pegawai->jabatan));

            $role = match (true) {

                str_contains($jabatan, 'guru')
                    => UserRole::GURU,

                str_contains($jabatan, 'tu')
                    => UserRole::TU,

                str_contains($jabatan, 'kepala sekolah')
                    => UserRole::SUPERADMIN,

                default => null,
            };

            // Jabatan yang tidak memiliki role
            if (!$role) {
                return;
            }

        });

        return redirect()
            ->route('superadmin.datakepegawaian.approval')
            ->with(
                'success',
                'Data pegawai berhasil diapprove dan akun berhasil dibuat.'
            );
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
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
use Illuminate\Validation\Rule;

class DataKepegawaianApprovalController extends Controller
{
    public function index(Request $request)
    {
        $statusLabels = [
            'pending' => 'Validasi',
            'approved' => 'Valid',
            'rejected' => 'Ditolak',
        ];

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'array'],
            'status.*' => [
                'required',
                'string',
                'distinct',
                Rule::in(array_keys($statusLabels)),
            ],
        ]);

        $search = trim($validated['search'] ?? '');
        $selectedStatuses = $validated['status'] ?? ['pending'];

        $query = DataKepegawaian::query();

        $query
            ->when(
                ! empty($selectedStatuses),
                fn ($query) => $query->whereIn(
                    'approval_status',
                    $selectedStatuses
                )
            )
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('nama', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%")
                        ->orWhere('jabatan', 'like', "%{$search}%");
                });
            });

        $data = $query
            ->orderBy('nama')
            ->paginate(25)
            ->withQueryString();

        $statusOptions = collect($statusLabels)
            ->map(fn ($label, $value) => [
                'value' => $value,
                'label' => $label,
            ])
            ->values()
            ->all();

        $statusChipCriteria = collect($selectedStatuses)
            ->map(fn ($status) => [
                'name' => 'status',
                'label' => 'Status',
                'value' => $status,
                'display' => $statusLabels[$status],
            ])
            ->all();

        return view('superadmin.datakepegawaian.approval', compact(
            'data',
            'search',
            'selectedStatuses',
            'statusOptions',
            'statusChipCriteria'
        ));
    }

    public function approve($id)
    {
        $approved = DB::transaction(function () use ($id) {

            $pegawai = DataKepegawaian::whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            // Hanya data pending yang boleh disetujui.
            if ($pegawai->approval_status !== 'pending') {
                return false;
            }

            $pegawai->update([
                'approval_status' => 'approved',
                'rejected_reason' => null,
            ]);

            // Jika sudah memiliki akun, tidak perlu dibuat lagi.
            if ($pegawai->user) {
                return true;
            }

            // Mapping role berdasarkan jabatan.
            $jabatan = strtolower(trim($pegawai->jabatan));

            $role = match (true) {
                str_contains($jabatan, 'guru') => UserRole::GURU,
                str_contains($jabatan, 'tu') => UserRole::GURU,
                str_contains($jabatan, 'kepala sekolah') => UserRole::SUPERADMIN,
                default => null,
            };

            // Jabatan yang tidak memiliki role.
            if (!$role) {
                return true;
            }

            return true;
        });

        // Pemeriksaan dilakukan setelah transaksi selesai.
        if (!$approved) {
            return redirect()
                ->route('superadmin.datakepegawaian.approval')
                ->with('error', 'Data pegawai ini sudah tidak menunggu validasi.');
        }

        return redirect()
            ->route('superadmin.datakepegawaian.approval')
            ->with(
                'success',
                'Data pegawai berhasil disetujui.'
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
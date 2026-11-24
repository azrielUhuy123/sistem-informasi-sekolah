<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\DataKepegawaian;
use App\Models\PegawaiChangeRequest;
use App\Services\PegawaiChangeWorkflow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PegawaiChangeMonitoringController extends Controller
{
    
        
    public function employees(Request $request, PegawaiChangeWorkflow $workflow)
    {
        $workflow->assertKepsek($request->user());

        // Pilihan jabatan diambil langsung dari database.
        $jabatanOptions = DataKepegawaian::query()
            ->whereNotNull('jabatan')
            ->where('jabatan', '<>', '')
            ->distinct()
            ->orderBy('jabatan')
            ->pluck('jabatan')
            ->map(fn ($jabatan) => [
                'value' => $jabatan,
                'label' => $jabatan,
            ])
            ->values()
            ->all();

        $validJabatan = collect($jabatanOptions)
            ->pluck('value')
            ->all();

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'jabatan' => ['nullable', 'array'],
            'jabatan.*' => [
                'required',
                'string',
                'distinct',
                \Illuminate\Validation\Rule::in($validJabatan),
            ],
        ]);

        $selectedJabatan = $validated['jabatan'] ?? [];
        $search = trim($validated['search'] ?? '');

        $query = DataKepegawaian::query()
            ->withCount('pegawaiChangeRequests');

        // Filter jabatan: OR dalam kelompok.
        $query->when(
            !empty($selectedJabatan),
            fn ($query) => $query->whereIn('jabatan', $selectedJabatan)
        );

        // Search seluruh dataset, bukan hanya halaman aktif.
        $query->when($search !== '', function ($query) use ($search) {
            $query->where(function ($subQuery) use ($search) {
                $subQuery
                    ->where('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('jabatan', 'like', "%{$search}%");
            });
        });

        $employees = $query
            ->orderBy('nama')
            ->paginate(25)
            ->withQueryString();

        return view(
            'superadmin.pegawai-changes.employees',
            compact(
                'employees',
                'jabatanOptions',
                'selectedJabatan',
                'search'
            )
        );
    }

    public function index(Request $request, PegawaiChangeWorkflow $workflow)
    {
        $workflow->assertKepsek($request->user());

        $statusLabels = [
            'pending' => 'Menunggu verifikasi',
            'partially_processed' => 'Diproses sebagian',
            'approved' => 'Disetujui',
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
        $selectedStatuses = $validated['status'] ?? [];

        $requests = PegawaiChangeRequest::query()
            ->with(['pegawai', 'submittedBy'])
            ->withCount('items')
            ->when(
                ! empty($selectedStatuses),
                fn ($query) => $query->whereIn('status', $selectedStatuses)
            )
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('submitted_by_name', 'like', "%{$search}%")
                        ->orWhereHas('pegawai', function ($pegawaiQuery) use ($search) {
                            $pegawaiQuery
                                ->where('nama', 'like', "%{$search}%")
                                ->orWhere('nip', 'like', "%{$search}%");
                        });

                    if (ctype_digit($search)) {
                        $subQuery->orWhereKey((int) $search);
                    }
                });
            })
            ->latest()
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

        return view('superadmin.pegawai-changes.index', compact(
            'requests',
            'search',
            'statusOptions',
            'selectedStatuses',
            'statusChipCriteria'
        ));
    }

    public function employee(
        Request $request,
        DataKepegawaian $pegawai,
        PegawaiChangeWorkflow $workflow
    ) {
        $workflow->assertKepsek($request->user());
        $pegawai->load([
            'user',
            'guru',
            'penugasans.items.menus',
            'pegawaiChangeRequests' => fn ($query) => $query
                ->with(['submittedBy', 'items.verifier', 'items.decisions.actor'])
                ->latest(),
        ]);

        return view('superadmin.pegawai-changes.employee', compact('pegawai'));
    }

    public function show(
        Request $request,
        PegawaiChangeRequest $pegawaiChangeRequest,
        PegawaiChangeWorkflow $workflow
    ) {
        $workflow->assertKepsek($request->user());
        $pegawaiChangeRequest->load([
            'pegawai',
            'submittedBy',
            'items.verifier',
            'items.decisions.actor',
        ]);

        return view('superadmin.pegawai-changes.show', compact('pegawaiChangeRequest'));
    }
}

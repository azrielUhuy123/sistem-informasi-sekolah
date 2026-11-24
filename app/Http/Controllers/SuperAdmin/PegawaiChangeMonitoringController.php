<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\DataKepegawaian;
use App\Models\PegawaiChangeRequest;
use App\Services\PegawaiChangeWorkflow;
use Illuminate\Http\Request;

class PegawaiChangeMonitoringController extends Controller
{
    public function employees(Request $request, PegawaiChangeWorkflow $workflow)
    {
        $workflow->assertKepsek($request->user());
        $employees = DataKepegawaian::with([
            'user',
            'guru',
            'penugasans',
        ])
            ->withCount('pegawaiChangeRequests')
            ->orderBy('nama')
            ->paginate(25);

        return view('superadmin.pegawai-changes.employees', compact('employees'));
    }

    public function index(Request $request, PegawaiChangeWorkflow $workflow)
    {
        $workflow->assertKepsek($request->user());
        $requests = PegawaiChangeRequest::with(['pegawai', 'submittedBy', 'items.verifier'])
            ->latest()
            ->paginate(25);

        return view('superadmin.pegawai-changes.index', compact('requests'));
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

<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\PegawaiChangeRequest;
use App\Models\PegawaiChangeItem;
use App\Services\BusinessNotificationService;
use App\Services\PegawaiChangeWorkflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PegawaiChangeVerificationController extends Controller
{
    public function index(Request $request, PegawaiChangeWorkflow $workflow)
    {
        $workflow->assertActiveOperator($request->user());
        $requests = PegawaiChangeRequest::with(['pegawai', 'submittedBy', 'items'])
            ->whereHas('items', fn ($query) => $query->where('status', 'pending'))
            ->where('data_kepegawaian_id', '!=', $request->user()->pegawai_id)
            ->latest()
            ->paginate(20);

        return view('tu.pegawai-changes.index', compact('requests'));
    }

    public function show(
        Request $request,
        PegawaiChangeRequest $pegawaiChangeRequest,
        PegawaiChangeWorkflow $workflow
    ) {
        $workflow->assertActiveOperator($request->user());
        abort_if(
            (int) $pegawaiChangeRequest->data_kepegawaian_id === (int) $request->user()->pegawai_id,
            404
        );

        $pegawaiChangeRequest->load([
            'pegawai',
            'submittedBy',
            'items.verifier',
            'items.decisions.actor',
        ]);

        return view('tu.pegawai-changes.show', compact('pegawaiChangeRequest'));
    }

    public function approve(
        Request $request,
        PegawaiChangeItem $item,
        PegawaiChangeWorkflow $workflow,
        BusinessNotificationService $notifications
    ) {
        $item = $workflow->approve($item, $request->user());
        $notifications->notifyPegawaiChangeDecision($item);

        return $item->status === 'approved'
            ? back()->with('success', 'Item perubahan disetujui dan data resmi diperbarui.')
            : back()->with('warning', $item->rejection_reason);
    }

    public function reject(
        Request $request,
        PegawaiChangeItem $item,
        PegawaiChangeWorkflow $workflow,
        BusinessNotificationService $notifications
    ) {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:2000'],
        ]);

        $item = $workflow->reject($item, $request->user(), trim($validated['reason']));
        $notifications->notifyPegawaiChangeDecision($item);

        return back()->with('success', 'Item perubahan ditolak dengan alasan yang tercatat.');
    }
}

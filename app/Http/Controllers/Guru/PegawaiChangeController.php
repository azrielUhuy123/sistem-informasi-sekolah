<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PegawaiChangeItem;
use App\Models\PegawaiChangeRequest;
use App\Services\BusinessNotificationService;
use App\Services\PegawaiChangeWorkflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PegawaiChangeController extends Controller
{
    public function index(PegawaiChangeWorkflow $workflow)
    {
        $employee = $workflow->employeeForTeacher(request()->user());
        $requests = PegawaiChangeRequest::with(['items.verifier'])
            ->where('data_kepegawaian_id', $employee->id)
            ->latest()
            ->paginate(15);

        return view('guru.pegawai-changes.index', compact('employee', 'requests'));
    }

    public function create(Request $request, PegawaiChangeWorkflow $workflow)
    {
        $employee = $workflow->employeeForTeacher($request->user());
        $fields = $workflow->fields();
        $retryItem = null;

        if ($request->filled('retry')) {
            $retryItem = PegawaiChangeItem::with('changeRequest')
                ->whereKey($request->integer('retry'))
                ->where('status', 'rejected')
                ->whereHas('changeRequest', fn ($query) => $query
                    ->where('data_kepegawaian_id', $employee->id))
                ->firstOrFail();
            abort_if($retryItem->resubmissions()->exists(), 409, 'Item ini sudah diajukan ulang.');
        }

        return view('guru.pegawai-changes.create', compact('employee', 'fields', 'retryItem'));
    }

    public function store(
        Request $request,
        PegawaiChangeWorkflow $workflow,
        BusinessNotificationService $notifications
    )
    {
        $workflow->employeeForTeacher($request->user());
        $fields = $workflow->fields();
        $fieldNames = array_keys($fields);
        $rules = [
            'changes' => ['required', 'array:'.implode(',', $fieldNames), 'min:1', 'max:'.count($fieldNames)],
            'evidence' => ['sometimes', 'array:'.implode(',', $fieldNames)],
            'resubmits' => ['sometimes', 'array:'.implode(',', $fieldNames)],
        ];

        foreach ($fields as $field => $definition) {
            $valueRules = ['nullable'];
            if ($definition['type'] === 'date') {
                $valueRules[] = 'date';
            } elseif ($definition['type'] === 'number') {
                $valueRules[] = 'integer';
                $valueRules[] = 'min:0';
                if ($field === 'tahun_sertifikasi') {
                    $valueRules[] = 'digits:4';
                }
            } elseif ($definition['type'] === 'select') {
                $options = $definition['options'];
                $valueRules[] = \Illuminate\Validation\Rule::in(
                    array_is_list($options) ? $options : array_keys($options)
                );
            } else {
                $valueRules[] = 'string';
                $valueRules[] = 'max:'.$definition['max'];
            }
            if ($field === 'nama') {
                $valueRules = ['sometimes', 'required', 'string', 'max:'.$definition['max']];
            } elseif ($field === 'nip') {
                $valueRules[] = \Illuminate\Validation\Rule::unique('data_kepegawaian', 'nip')
                    ->ignore($request->user()->pegawai_id);
            }

            $rules['changes.'.$field] = $valueRules;
            $rules['evidence.'.$field] = ['sometimes', 'nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'];
            $rules['resubmits.'.$field] = ['sometimes', 'nullable', 'integer', 'min:1'];
        }

        $validated = $request->validate($rules);
        $changeRequest = $workflow->submit(
            $request->user(),
            $validated['changes'],
            $validated['evidence'] ?? [],
            $validated['resubmits'] ?? [],
        );
        $notifications->notifyPegawaiChangeSubmitted($changeRequest);

        return redirect()
            ->route('guru.pegawai-changes.show', $changeRequest)
            ->with('success', 'Perubahan berhasil diajukan untuk verifikasi Operator.');
    }

    public function show(PegawaiChangeRequest $pegawaiChangeRequest, PegawaiChangeWorkflow $workflow)
    {
        $employee = $workflow->employeeForTeacher(request()->user());
        abort_unless((int) $pegawaiChangeRequest->data_kepegawaian_id === (int) $employee->id, 404);

        $pegawaiChangeRequest->load(['submittedBy', 'items.verifier', 'items.decisions.actor']);

        return view('guru.pegawai-changes.show', compact('pegawaiChangeRequest'));
    }

    public function evidence(PegawaiChangeItem $item, PegawaiChangeWorkflow $workflow)
    {
        $workflow->authorizeEvidence(request()->user(), $item);
        abort_unless(Storage::disk('local')->exists($item->evidence_path), 404);

        $extension = pathinfo($item->evidence_path, PATHINFO_EXTENSION);
        $filename = 'bukti-perubahan-'.$item->id.($extension ? '.'.$extension : '');

        return Storage::disk('local')->download($item->evidence_path, $filename, [
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}

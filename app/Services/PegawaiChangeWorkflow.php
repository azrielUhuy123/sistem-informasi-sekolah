<?php

namespace App\Services;

use App\Models\DataKepegawaian;
use App\Models\PegawaiChangeItem;
use App\Models\PegawaiChangeRequest;
use App\Models\Penugasan;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class PegawaiChangeWorkflow
{
    public function fields(): array
    {
        return config('employee_profile_changes.fields', []);
    }

    public function employeeForTeacher(User $user): DataKepegawaian
    {
        $user = User::query()->find($user->getKey());

        abort_unless(
            $user
                && $user->role === 'guru'
                && $user->approval_status === 'approved'
                && $user->is_active
                && ! $user->must_change_password
                && $user->pegawai_id
                && $user->guru()->exists(),
            403
        );

        return $user->pegawai()->firstOrFail();
    }

    public function assertActiveOperator(User $user, string $menuKey = 'operator.employees'): void
    {
        $user = User::query()->find($user->getKey());

        abort_unless(
            $user
                && $user->role === 'guru'
                && $user->approval_status === 'approved'
                && $user->is_active
                && ! $user->must_change_password
                && $user->pegawai_id
                && $user->guru()->exists()
                && $this->hasActiveOperatorAssignment(
                    (int) $user->pegawai_id,
                    $menuKey,
                    DB::transactionLevel() > 0
                ),
            403,
            'Penugasan Operator aktif diperlukan.'
        );
    }

    public function assertKepsek(User $user): void
    {
        $user = User::query()->find($user->getKey());

        abort_unless(
            $user
                && $user->role === 'superadmin'
                && $user->approval_status === 'approved'
                && $user->is_active
                && ! $user->must_change_password,
            403
        );
    }

    public function submit(
        User $actor,
        array $changes,
        array $evidence,
        array $resubmits = []
    ): PegawaiChangeRequest {
        $fields = $this->fields();
        $this->employeeForTeacher($actor);

        return DB::transaction(function () use ($actor, $changes, $evidence, $resubmits, $fields) {
            $actor = User::query()->lockForUpdate()->findOrFail($actor->id);
            $employee = $this->employeeForTeacher($actor);
            $employee = DataKepegawaian::query()
                ->lockForUpdate()
                ->findOrFail($employee->id);

            $prepared = [];

            foreach ($changes as $field => $value) {
                abort_unless(isset($fields[$field]), 422, 'Field perubahan tidak diizinkan.');

                $newValue = $this->normalizeValue($field, $value);
                $oldValue = $this->storedValue($field, $employee->getAttribute($field));

                if ($newValue === $oldValue) {
                    continue;
                }

                if ($this->hasPendingFieldRequest($employee->id, $field)) {
                    throw ValidationException::withMessages([
                        'changes.'.$field => 'Masih ada perubahan field ini yang menunggu verifikasi.',
                    ]);
                }

                $file = $evidence[$field] ?? null;
                if (($fields[$field]['evidence'] ?? false) && ! ($file instanceof UploadedFile)) {
                    throw ValidationException::withMessages([
                        'evidence.'.$field => 'Dokumen bukti wajib dilampirkan untuk perubahan ini.',
                    ]);
                }

                $sourceItem = null;
                if (isset($resubmits[$field])) {
                    $sourceItem = PegawaiChangeItem::query()
                        ->whereKey($resubmits[$field])
                        ->where('field', $field)
                        ->where('status', 'rejected')
                        ->whereHas('changeRequest', fn ($query) => $query
                            ->where('data_kepegawaian_id', $employee->id))
                        ->lockForUpdate()
                        ->firstOrFail();

                    abort_if(
                        $sourceItem->resubmissions()->exists(),
                        409,
                        'Item yang ditolak sudah diajukan ulang.'
                    );
                }

                $prepared[$field] = [
                    'old_value' => $oldValue,
                    'new_value' => $newValue,
                    'evidence' => $file,
                    'resubmitted_from_id' => $sourceItem?->id,
                ];
            }

            if ($prepared === []) {
                throw ValidationException::withMessages([
                    'changes' => 'Tidak ada nilai yang berubah dari data resmi.',
                ]);
            }

            $changeRequest = PegawaiChangeRequest::create([
                'data_kepegawaian_id' => $employee->id,
                'submitted_by' => $actor->id,
                'submitted_by_name' => $actor->name,
                'status' => 'pending',
            ]);

            $storedPaths = [];

            try {
                foreach ($prepared as $field => $change) {
                    $path = null;
                    if ($change['evidence'] instanceof UploadedFile) {
                        $path = $change['evidence']->store('pegawai-change-evidence', 'local');
                        if (! $path) {
                            throw new RuntimeException('Dokumen bukti gagal disimpan.');
                        }
                        $storedPaths[] = $path;
                    }

                    $changeRequest->items()->create([
                        'field' => $field,
                        'old_value' => $change['old_value'],
                        'new_value' => $change['new_value'],
                        'evidence_path' => $path,
                        'status' => 'pending',
                        'resubmitted_from_id' => $change['resubmitted_from_id'],
                    ]);
                }
            } catch (\Throwable $exception) {
                foreach ($storedPaths as $path) {
                    Storage::disk('local')->delete($path);
                }

                throw $exception;
            }

            return $changeRequest->load('items');
        });
    }

    public function approve(PegawaiChangeItem $routeItem, User $actor): PegawaiChangeItem
    {
        $this->assertActiveOperator($actor);

        return DB::transaction(function () use ($routeItem, $actor) {
            $changeRequest = PegawaiChangeRequest::query()
                ->lockForUpdate()
                ->findOrFail($routeItem->pegawai_change_request_id);
            $item = PegawaiChangeItem::query()
                ->lockForUpdate()
                ->findOrFail($routeItem->id);
            $item->setRelation('changeRequest', $changeRequest);
            $actor = User::query()->lockForUpdate()->findOrFail($actor->id);
            abort_unless($item->status === 'pending', 409, 'Item perubahan sudah diproses.');
            abort_if(
                (int) $item->changeRequest->data_kepegawaian_id === (int) $actor->pegawai_id,
                403,
                'Operator tidak dapat memverifikasi pengajuan miliknya sendiri.'
            );

            $this->assertActiveOperator($actor);
            $field = $item->field;
            abort_unless(isset($this->fields()[$field]), 409, 'Field pengajuan tidak dikenal.');

            $employee = DataKepegawaian::query()
                ->lockForUpdate()
                ->findOrFail($item->changeRequest->data_kepegawaian_id);

            $currentValue = $this->storedValue($field, $employee->getAttribute($field));
            if ($currentValue !== $item->old_value) {
                return $this->rejectItem(
                    $item,
                    $actor,
                    $changeRequest,
                    'Data resmi berubah sejak pengajuan dibuat. Ajukan kembali dengan nilai terbaru.'
                );
            }

            if (
                $field === 'nip'
                && $item->new_value !== null
                && DataKepegawaian::query()
                    ->where('nip', $item->new_value)
                    ->whereKeyNot($employee->id)
                    ->exists()
            ) {
                return $this->rejectItem(
                    $item,
                    $actor,
                    $changeRequest,
                    'NIP sudah digunakan pada data pegawai lain.'
                );
            }

            $employee->setAttribute($field, $item->new_value);
            $employee->save();
            if ($field === 'nama') {
                $employee->user()->update(['name' => $item->new_value]);
            }

            $item->update([
                'status' => 'approved',
                'rejection_reason' => null,
                'verified_by' => $actor->id,
                'verified_at' => now(),
            ]);

            $item->decisions()->create([
                'actor_id' => $actor->id,
                'action' => 'approved',
                'acted_at' => now(),
            ]);

            $this->refreshAggregateStatus($changeRequest);

            return $item->fresh(['changeRequest', 'verifier', 'decisions.actor']);
        });
    }

    public function reject(PegawaiChangeItem $routeItem, User $actor, string $reason): PegawaiChangeItem
    {
        $this->assertActiveOperator($actor);

        return DB::transaction(function () use ($routeItem, $actor, $reason) {
            $changeRequest = PegawaiChangeRequest::query()
                ->lockForUpdate()
                ->findOrFail($routeItem->pegawai_change_request_id);
            $item = PegawaiChangeItem::query()
                ->lockForUpdate()
                ->findOrFail($routeItem->id);
            $item->setRelation('changeRequest', $changeRequest);
            $actor = User::query()->lockForUpdate()->findOrFail($actor->id);
            abort_unless($item->status === 'pending', 409, 'Item perubahan sudah diproses.');
            abort_if(
                (int) $item->changeRequest->data_kepegawaian_id === (int) $actor->pegawai_id,
                403,
                'Operator tidak dapat memverifikasi pengajuan miliknya sendiri.'
            );

            $this->assertActiveOperator($actor);
            $item->update([
                'status' => 'rejected',
                'rejection_reason' => $reason,
                'verified_by' => $actor->id,
                'verified_at' => now(),
            ]);

            $item->decisions()->create([
                'actor_id' => $actor->id,
                'action' => 'rejected',
                'reason' => $reason,
                'acted_at' => now(),
            ]);

            $this->refreshAggregateStatus($changeRequest);

            return $item->fresh(['changeRequest', 'verifier', 'decisions.actor']);
        });
    }

    public function authorizeEvidence(User $actor, PegawaiChangeItem $item): void
    {
        abort_unless($item->evidence_path, 404);
        $request = $item->changeRequest()->firstOrFail();
        $actor = User::query()->find($actor->getKey());
        abort_unless($actor, 403);

        if ($actor->role === 'superadmin') {
            $this->assertKepsek($actor);
            return;
        }

        if (
            $actor->role === 'guru'
            && $actor->approval_status === 'approved'
            && $actor->is_active
            && ! $actor->must_change_password
            && (int) $actor->pegawai_id === (int) $request->data_kepegawaian_id
            && $actor->guru()->exists()
        ) {
            return;
        }

        $this->assertActiveOperator($actor);
        abort_if(
            (int) $actor->pegawai_id === (int) $request->data_kepegawaian_id,
            403,
            'Operator tidak dapat membuka bukti pengajuan miliknya sendiri melalui antrean verifikasi.'
        );
    }

    private function refreshAggregateStatus(PegawaiChangeRequest $request): void
    {
        $statuses = $request->items()->pluck('status');

        if ($statuses->contains('pending')) {
            $status = 'pending';
        } elseif ($statuses->contains('approved') && $statuses->contains('rejected')) {
            $status = 'partially_processed';
        } elseif ($statuses->contains('approved')) {
            $status = 'approved';
        } else {
            $status = 'rejected';
        }

        $request->update(['status' => $status]);
    }

    private function rejectItem(
        PegawaiChangeItem $item,
        User $actor,
        PegawaiChangeRequest $request,
        string $reason
    ): PegawaiChangeItem {
        $item->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'verified_by' => $actor->id,
            'verified_at' => now(),
        ]);
        $item->decisions()->create([
            'actor_id' => $actor->id,
            'action' => 'rejected',
            'reason' => $reason,
            'acted_at' => now(),
        ]);
        $this->refreshAggregateStatus($request);

        return $item->fresh(['changeRequest', 'verifier', 'decisions.actor']);
    }

    private function hasActiveOperatorAssignment(int $employeeId, string $menuKey, bool $lock = false): bool
    {
        $query = Penugasan::query()
            ->where('status', 'active')
            ->whereNotNull('published_at')
            ->where('data_kepegawaian_id', $employeeId)
            ->whereHas('items', fn ($query) => $query
                ->where('type', 'operator')
                ->whereHas('menus', fn ($query) => $query
                    ->where('menu_key', $menuKey)));

        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->first() !== null;
    }

    private function hasPendingFieldRequest(int $employeeId, string $field): bool
    {
        return PegawaiChangeItem::query()
            ->where('field', $field)
            ->where('status', 'pending')
            ->whereHas('changeRequest', fn ($query) => $query
                ->where('data_kepegawaian_id', $employeeId))
            ->exists();
    }

    private function normalizeValue(string $field, mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $definition = $this->fields()[$field];

        return match ($definition['type']) {
            'date' => date('Y-m-d', strtotime((string) $value)),
            'number' => (string) (int) $value,
            default => trim((string) $value),
        };
    }

    private function storedValue(string $field, mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $definition = $this->fields()[$field];

        if ($definition['type'] === 'date') {
            return $value instanceof CarbonInterface
                ? $value->format('Y-m-d')
                : date('Y-m-d', strtotime((string) $value));
        }

        if ($definition['type'] === 'number') {
            return (string) (int) $value;
        }

        return trim((string) $value);
    }
}

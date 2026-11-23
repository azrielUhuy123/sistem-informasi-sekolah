<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\DataKepegawaian;
use App\Models\Penugasan;
use App\Models\PenugasanItem;
use App\Models\PembinaEkskul;
use App\Models\PengampuMapel;
use App\Models\TahunAjaran;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ManajemenPenugasanController extends Controller
{
    private const TYPES = ['operator', 'ppdb', 'wali', 'guru', 'ekskul'];

    public function index()
    {
        $employees = DataKepegawaian::query()
            ->whereHas('guru')
            ->whereHas('user', fn ($query) => $query->where('role', 'guru'))
            ->with(['user', 'guru'])
            ->orderBy('nama')
            ->get();

        $classes = \App\Models\Kelas::query()
            ->orderBy('nama_kelas')
            ->get();
        $subjects = \App\Models\MataPelajaran::orderBy('nama_mapel')->get();
        $extracurriculars = \App\Models\Ekskul::orderBy('nama')->get();

        $records = Penugasan::with([
            'pegawai.user',
            'items.detail',
            'items.details',
            'items.menus',
        ])
            ->latest()
            ->get()
            ->map(fn (Penugasan $penugasan) => $this->serializeRecord($penugasan));

        return view('superadmin.manajemen-penugasan.index', compact(
            'employees',
            'classes',
            'subjects',
            'extracurriculars',
            'records'
        ));
    }

    public function store(Request $request)
    {
        $data = $this->validateDraft($request);

        $penugasan = DB::transaction(function () use ($data, $request) {
            $penugasan = Penugasan::create([
                'data_kepegawaian_id' => $data['employee'],
                'assigned_by' => $request->user()->id,
                'status' => 'draft',
            ]);
            $this->saveItems($penugasan, $data);
            return $penugasan;
        });

        return response()->json([
            'message' => 'Draft penugasan berhasil disimpan.',
            'record' => $this->serializeRecord($penugasan->load('pegawai.user', 'items.detail', 'items.details', 'items.menus')),
        ], 201);
    }

    public function update(Request $request, Penugasan $penugasan)
    {
        abort_unless($penugasan->status === 'draft', 422, 'Hanya draft yang boleh diedit.');
        $data = $this->validateDraft($request);

        DB::transaction(function () use ($penugasan, $data) {
            $penugasan->update([
                'data_kepegawaian_id' => $data['employee'],
            ]);
            $penugasan->items()->delete();
            $this->saveItems($penugasan, $data);
        });

        return response()->json([
            'message' => 'Draft penugasan berhasil diperbarui.',
            'record' => $this->serializeRecord($penugasan->load('pegawai.user', 'items.detail', 'items.details', 'items.menus')),
        ]);
    }

    public function destroy(Penugasan $penugasan)
    {
        abort_unless($penugasan->status === 'draft', 422, 'Hanya draft yang boleh dihapus.');
        DB::transaction(fn () => $penugasan->delete());

        return response()->json(['message' => 'Draft penugasan berhasil dihapus.']);
    }

    public function publish(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array', 'min:1', 'distinct'],
            'ids.*' => ['integer', 'exists:penugasans,id'],
        ]);

        DB::transaction(function () use ($request) {
            $ids = array_map('intval', $request->input('ids'));
            $records = Penugasan::with('pegawai.user', 'items.detail', 'items.details', 'items.menus')
                ->whereIn('id', $ids)
                ->lockForUpdate()
                ->get();

            if ($records->count() !== count($ids) || $records->contains(fn ($record) => $record->status !== 'draft')) {
                abort(422, 'Semua penugasan yang dipilih harus berupa draft.');
            }

            $requiresActiveYear = $records->contains(fn (Penugasan $record) => $record->items
                ->contains(fn ($item) => in_array($item->type, ['wali', 'guru', 'ekskul'], true)));
            $activeYearIds = $requiresActiveYear
                ? TahunAjaran::query()->where('status', 'aktif')->orderBy('id')->pluck('id')
                : collect();
            if ($requiresActiveYear && $activeYearIds->count() !== 1) {
                abort(422, 'Tepat satu tahun ajaran aktif diperlukan untuk menerbitkan penugasan operasional.');
            }
            $activeYearId = $requiresActiveYear ? (int) $activeYearIds->first() : null;

            $uniqueTypeKeys = [];
            foreach ($records as $record) {
                foreach ($record->items as $item) {
                    if (! in_array($item->type, ['wali', 'operator', 'ppdb'], true)) {
                        continue;
                    }

                    $key = $record->data_kepegawaian_id . ':' . $item->type;
                    if (isset($uniqueTypeKeys[$key])) {
                        abort(422, 'Penugasan aktif ganda tidak diperbolehkan.');
                    }
                    $uniqueTypeKeys[$key] = true;
                }
            }

            foreach ($records as $penugasan) {
                $this->validatePublish($penugasan, $activeYearId);
            }

            foreach ($records as $penugasan) {
                $penugasan->update([
                    'status' => 'active',
                    'published_at' => now(),
                    'assigned_by' => $request->user()->id,
                ]);
                $this->syncLegacyModules($penugasan, $activeYearId);
            }
        });

        return response()->json(['message' => 'Penugasan terpilih berhasil dipublikasikan.']);
    }

    public function revoke(Request $request, Penugasan $penugasan)
    {
        abort_unless($penugasan->status === 'active', 422, 'Hanya penugasan aktif yang boleh dicabut.');

        DB::transaction(function () use ($request, $penugasan) {
            $penugasan->load('items.detail', 'items.details');
            $penugasan->update([
                'status' => 'revoked',
                'revoked_at' => now(),
                'revoked_by' => $request->user()->id,
            ]);
        });

        return response()->json(['message' => 'Penugasan berhasil dicabut.']);
    }

    private function validateDraft(Request $request): array
    {
        $validated = $request->validate([
            'employee' => ['required', 'integer', 'exists:data_kepegawaian,id'],
            'assignmentKeys' => ['nullable', 'array'],
            'assignmentKeys.*' => ['required', 'string', 'distinct', Rule::in(self::TYPES)],
            'details' => ['nullable', 'array'],
            'details.waliClass' => ['nullable', 'integer', Rule::exists('kelas', 'id')->whereNull('deleted_at')],
            'details.subjects' => ['nullable', 'array'],
            'details.subjects.*' => ['integer', 'exists:mata_pelajaran,id'],
            'details.guruClasses' => ['nullable', 'array'],
            'details.guruClasses.*' => ['integer', Rule::exists('kelas', 'id')->whereNull('deleted_at')],
            'details.extracurriculars' => ['nullable', 'array'],
            'details.extracurriculars.*' => ['integer', 'exists:ekskuls,id'],
            'menus' => ['nullable', 'array'],
            'menus.*' => ['nullable', 'array'],
            'menus.*.*' => ['required', 'string', 'distinct'],
        ]);

        $employeeIsEligible = DataKepegawaian::query()
            ->whereKey($validated['employee'])
            ->whereHas('guru')
            ->whereHas('user', fn ($query) => $query->where('role', 'guru'))
            ->exists();
        if (! $employeeIsEligible) {
            throw ValidationException::withMessages([
                'employee' => 'Pegawai harus memiliki akun Guru dan relasi data Guru yang aktif.',
            ]);
        }

        $assignmentKeys = array_values(array_unique($validated['assignmentKeys'] ?? []));
        $menus = $validated['menus'] ?? [];
        $menuDefinitions = config('assignment_menus.types', []);
        foreach ($menus as $type => $menuKeys) {
            if (! array_key_exists($type, $menuDefinitions)) {
                throw ValidationException::withMessages([
                    'menus' => 'Jenis penugasan untuk hak akses tidak dikenal.',
                ]);
            }
            if (! in_array($type, $assignmentKeys, true) && ! empty($menuKeys)) {
                throw ValidationException::withMessages([
                    'menus' => 'Hak akses hanya dapat dipilih untuk jenis penugasan yang diaktifkan.',
                ]);
            }

            $allowedKeys = array_keys($menuDefinitions[$type]['menus'] ?? []);
            foreach ($menuKeys ?? [] as $menuKey) {
                if (! in_array($menuKey, $allowedKeys, true)) {
                    throw ValidationException::withMessages([
                        'menus' => 'Hak akses menu tidak valid untuk jenis penugasan yang dipilih.',
                    ]);
                }
            }
        }

        return [
            'employee' => (int) $validated['employee'],
            'assignmentKeys' => $assignmentKeys,
            'details' => $validated['details'] ?? [],
            'menus' => $menus,
        ];
    }

    private function saveItems(Penugasan $penugasan, array $data): void
    {
        foreach (array_unique($data['assignmentKeys']) as $type) {
            $item = $penugasan->items()->create(['type' => $type]);

            if ($type === 'wali' && ! empty($data['details']['waliClass'])) {
                $item->details()->create(['kelas_id' => (int) $data['details']['waliClass']]);
            } elseif ($type === 'guru') {
                foreach ($data['details']['subjects'] ?? [] as $subjectId) {
                    foreach ($data['details']['guruClasses'] ?? [] as $classId) {
                        $item->details()->create([
                            'mata_pelajaran_id' => (int) $subjectId,
                            'kelas_id' => (int) $classId,
                        ]);
                    }
                }
            } elseif ($type === 'ekskul') {
                foreach ($data['details']['extracurriculars'] ?? [] as $ekskulId) {
                    $item->details()->create(['ekskul_id' => (int) $ekskulId]);
                }
            }

            foreach (array_unique($data['menus'][$type] ?? []) as $menuKey) {
                $item->menus()->create(['menu_key' => $menuKey]);
            }
        }
    }

    private function validatePublish(Penugasan $penugasan, ?int $activeYearId): void
    {
        $employeeIsEligible = DataKepegawaian::query()
            ->whereKey($penugasan->data_kepegawaian_id)
            ->whereHas('guru')
            ->whereHas('user', fn ($query) => $query->where('role', 'guru'))
            ->exists();
        if (! $employeeIsEligible) {
            abort(422, 'Penerima penugasan harus memiliki akun Guru dan relasi Guru yang valid.');
        }

        if ($penugasan->items->isEmpty()) {
            abort(422, 'Draft harus memiliki minimal satu penugasan.');
        }

        if (
            $penugasan->items->contains(fn ($item) => in_array($item->type, ['wali', 'guru', 'ekskul'], true))
            && ! $activeYearId
        ) {
            abort(422, 'Tahun ajaran aktif diperlukan untuk menerbitkan penugasan operasional.');
        }

        foreach ($penugasan->items as $item) {
            $allowedMenus = config("assignment_menus.types.{$item->type}.menus", []);
            if (! $allowedMenus || $item->menus->contains(
                fn ($menu) => ! array_key_exists($menu->menu_key, $allowedMenus)
            )) {
                abort(422, 'Draft berisi hak akses menu yang tidak valid.');
            }

            $hasValidDetails = match ($item->type) {
                'wali' => $item->details->contains(fn ($detail) => $detail->kelas_id !== null),
                'guru' => $item->details->contains(fn ($detail) => $detail->kelas_id !== null && $detail->mata_pelajaran_id !== null),
                'ekskul' => $item->details->contains(fn ($detail) => $detail->ekskul_id !== null),
                default => true,
            };
            if (! $hasValidDetails) {
                abort(422, 'Rincian penugasan wajib dilengkapi sebelum dipublikasikan.');
            }
            if ($item->menus->isEmpty()) {
                abort(422, 'Setiap penugasan harus memiliki minimal satu hak akses.');
            }
        }

        foreach ($penugasan->items as $item) {
            if (! in_array($item->type, ['wali', 'ppdb', 'operator'], true)) continue;
            $exists = PenugasanItem::query()
                ->where('type', $item->type)
                ->whereHas('penugasan', fn ($query) => $query
                    ->where('status', 'active')
                    ->where('data_kepegawaian_id', $penugasan->data_kepegawaian_id)
                    ->where('id', '!=', $penugasan->id))
                ->exists();
            if ($exists) abort(422, 'Penugasan aktif ganda tidak diperbolehkan.');
        }
    }

    private function syncLegacyModules(Penugasan $penugasan, ?int $activeYearId): void
    {
        $guru = $penugasan->pegawai?->guru;
        if (! $guru) return;

        foreach ($penugasan->items as $item) {
            if ($item->type === 'wali') {
                foreach ($item->details as $detail) {
                    WaliKelas::updateOrCreate([
                        'guru_id' => $guru->id,
                        'kelas_id' => $detail->kelas_id,
                        'tahun_ajaran_id' => $activeYearId,
                    ]);
                }
            } elseif ($item->type === 'guru') {
                foreach ($item->details as $detail) {
                    PengampuMapel::updateOrCreate([
                        'guru_id' => $guru->id,
                        'kelas_id' => $detail->kelas_id,
                        'mata_pelajaran_id' => $detail->mata_pelajaran_id,
                        'tahun_ajaran_id' => $activeYearId,
                    ]);
                }
            } elseif ($item->type === 'ekskul') {
                foreach ($item->details as $detail) {
                    PembinaEkskul::firstOrCreate([
                        'guru_id' => $guru->id,
                        'ekskul_id' => $detail->ekskul_id,
                        'tahun_ajaran_id' => $activeYearId,
                    ]);
                }
            }
        }
    }

    private function serializeRecord(Penugasan $penugasan): array
    {
        $statusMap = ['draft' => 'Draft', 'active' => 'Aktif', 'revoked' => 'Dicabut'];
        $assignmentKeys = $penugasan->items->pluck('type')->values()->all();

        $details = [
            'waliClass' => null,
            'subjects' => [],
            'guruClasses' => [],
            'extracurriculars' => [],
        ];
        $menus = [];

        foreach ($penugasan->items as $item) {
            $menus[$item->type] = $item->menus->pluck('menu_key')->values()->all();
            foreach ($item->details as $detail) {
                if ($item->type === 'wali' && $detail->kelas_id) {
                    $details['waliClass'] = (string) $detail->kelas_id;
                }
                if ($item->type === 'guru') {
                    if ($detail->mata_pelajaran_id) $details['subjects'][] = (string) $detail->mata_pelajaran_id;
                    if ($detail->kelas_id) $details['guruClasses'][] = (string) $detail->kelas_id;
                }
                if ($item->type === 'ekskul' && $detail->ekskul_id) {
                    $details['extracurriculars'][] = (string) $detail->ekskul_id;
                }
            }
            $details['subjects'] = array_values(array_unique($details['subjects']));
            $details['guruClasses'] = array_values(array_unique($details['guruClasses']));
            $details['extracurriculars'] = array_values(array_unique($details['extracurriculars']));
        }

        return [
            'id' => (string) $penugasan->id,
            'employee' => [
                'id' => (string) $penugasan->data_kepegawaian_id,
                'name' => $penugasan->pegawai?->nama,
                'nip' => $penugasan->pegawai?->nip,
            ],
            'assignmentKeys' => $assignmentKeys,
            'details' => $details,
            'menus' => $menus,
            'status' => $statusMap[$penugasan->status] ?? $penugasan->status,
        ];
    }
}

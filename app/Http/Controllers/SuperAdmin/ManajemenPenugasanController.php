<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\DataKepegawaian;
use App\Models\Penugasan;
use App\Models\PenugasanItem;
use App\Models\PembinaEkskul;
use App\Models\PengampuMapel;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ManajemenPenugasanController extends Controller
{
    private const TYPES = ['operator', 'ppdb', 'wali', 'guru', 'ekskul'];

    public function index()
    {
        $employees = DataKepegawaian::query()
            ->with(['user', 'guru'])
            ->orderBy('nama')
            ->get();

        $classes = \App\Models\Kelas::withTrashed()
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
        $penugasan->delete();

        return response()->json(['message' => 'Draft penugasan berhasil dihapus.']);
    }

    public function publish(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:penugasans,id'],
        ]);

        DB::transaction(function () use ($request) {
            $records = Penugasan::with('pegawai.user', 'items.detail', 'items.details', 'items.menus')
                ->whereIn('id', $request->input('ids'))
                ->where('status', 'draft')
                ->lockForUpdate()
                ->get();

            foreach ($records as $penugasan) {
                $this->validatePublish($penugasan);
                $penugasan->update([
                    'status' => 'active',
                    'published_at' => now(),
                    'assigned_by' => $request->user()->id,
                ]);
                $this->syncLegacyModules($penugasan);
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
            $this->syncLegacyModulesAfterRevoke($penugasan);
        });

        return response()->json(['message' => 'Penugasan berhasil dicabut.']);
    }

    private function validateDraft(Request $request): array
    {
        $validated = $request->validate([
            'employee' => ['required', 'integer', 'exists:data_kepegawaian,id'],
            'assignmentKeys' => ['nullable', 'array'],
            'assignmentKeys.*' => ['string', Rule::in(self::TYPES)],
            'details' => ['nullable', 'array'],
            'details.waliClass' => ['nullable', 'integer', 'exists:kelas,id'],
            'details.subjects' => ['nullable', 'array'],
            'details.subjects.*' => ['integer', 'exists:mata_pelajaran,id'],
            'details.guruClasses' => ['nullable', 'array'],
            'details.guruClasses.*' => ['integer', 'exists:kelas,id'],
            'details.extracurriculars' => ['nullable', 'array'],
            'details.extracurriculars.*' => ['integer', 'exists:ekskuls,id'],
            'menus' => ['nullable', 'array'],
            'menus.*' => ['nullable', 'array'],
            'menus.*.*' => ['string'],
        ]);

        return [
            'employee' => (int) $validated['employee'],
            'assignmentKeys' => $validated['assignmentKeys'] ?? [],
            'details' => $validated['details'] ?? [],
            'menus' => $validated['menus'] ?? [],
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
                $normalized = $this->normalizeMenuKey($type, $menuKey);
                if ($normalized) {
                    $item->menus()->create(['menu_key' => $normalized]);
                }
            }
        }
    }

    private function normalizeMenuKey(string $type, string $menuKey): ?string
    {
        if (str_starts_with($menuKey, 'tu.') || str_starts_with($menuKey, 'guru.') || str_starts_with($menuKey, 'ppdb.')) {
            return $menuKey;
        }

        $operatorMenus = [
            'Kelola Tahun Ajaran' => 'tu.academic',
            'Kelas dan Rombel' => 'tu.academic',
            'Data Siswa' => 'tu.academic',
            'Data Guru' => 'tu.academic',
            'Mata Pelajaran' => 'tu.academic',
            'Jadwal Kelas' => 'tu.academic',
            'Ekstrakurikuler' => 'tu.academic',
            'Arsip Dokumen dan Surat' => 'tu.documents',
            'Data Kepegawaian' => 'tu.academic',
            'Kelola Akun' => 'tu.academic',
        ];

        if ($type === 'operator') {
            return $operatorMenus[$menuKey] ?? null;
        }
        if ($type === 'ppdb') {
            return 'tu.ppdb';
        }
        if ($type === 'wali' || $type === 'guru') {
            return 'guru.academic';
        }
        if ($type === 'ekskul') {
            return 'guru.extracurricular';
        }

        return null;
    }

    private function validatePublish(Penugasan $penugasan): void
    {
        if ($penugasan->items->isEmpty()) {
            abort(422, 'Draft harus memiliki minimal satu penugasan.');
        }

        foreach ($penugasan->items as $item) {
            if (in_array($item->type, ['wali', 'guru', 'ekskul'], true)) {
                if ($item->details->isEmpty()) {
                    abort(422, 'Rincian penugasan wajib dilengkapi sebelum dipublikasikan.');
                }
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

    private function syncLegacyModules(Penugasan $penugasan): void
    {
        $guru = $penugasan->pegawai?->guru;
        if (! $guru) return;

        foreach ($penugasan->items as $item) {
            if ($item->type === 'wali') {
                foreach ($item->details as $detail) {
                    WaliKelas::updateOrCreate([
                        'guru_id' => $guru->id,
                        'kelas_id' => $detail->kelas_id,
                        'tahun_ajaran_id' => current_tahun_id(),
                    ]);
                }
            } elseif ($item->type === 'guru') {
                foreach ($item->details as $detail) {
                    PengampuMapel::updateOrCreate([
                        'guru_id' => $guru->id,
                        'kelas_id' => $detail->kelas_id,
                        'mata_pelajaran_id' => $detail->mata_pelajaran_id,
                        'tahun_ajaran_id' => current_tahun_id(),
                    ]);
                }
            } elseif ($item->type === 'ekskul') {
                foreach ($item->details as $detail) {
                    PembinaEkskul::firstOrCreate([
                        'guru_id' => $guru->id,
                        'ekskul_id' => $detail->ekskul_id,
                        'tahun_ajaran_id' => current_tahun_id(),
                    ]);
                }
            }
        }
    }

    private function syncLegacyModulesAfterRevoke(Penugasan $penugasan): void
    {
        $guru = $penugasan->pegawai?->guru;
        if (! $guru) return;

        foreach ($penugasan->items as $item) {
            foreach ($item->details as $detail) {
                $otherActive = PenugasanItem::query()
                    ->where('type', $item->type)
                    ->where('id', '!=', $item->id)
                    ->whereHas('penugasan', fn ($query) => $query
                        ->where('status', 'active')
                        ->where('data_kepegawaian_id', $penugasan->data_kepegawaian_id))
                    ->whereHas('details', function ($query) use ($detail) {
                        $query->when($detail->kelas_id, fn ($q) => $q->where('kelas_id', $detail->kelas_id))
                            ->when($detail->mata_pelajaran_id, fn ($q) => $q->where('mata_pelajaran_id', $detail->mata_pelajaran_id))
                            ->when($detail->ekskul_id, fn ($q) => $q->where('ekskul_id', $detail->ekskul_id));
                    })
                    ->exists();

                if ($otherActive) continue;

                if ($item->type === 'wali') {
                    WaliKelas::where('guru_id', $guru->id)
                        ->where('kelas_id', $detail->kelas_id)
                        ->where('tahun_ajaran_id', current_tahun_id())
                        ->delete();
                } elseif ($item->type === 'guru') {
                    PengampuMapel::where('guru_id', $guru->id)
                        ->where('kelas_id', $detail->kelas_id)
                        ->where('mata_pelajaran_id', $detail->mata_pelajaran_id)
                        ->where('tahun_ajaran_id', current_tahun_id())
                        ->delete();
                } elseif ($item->type === 'ekskul') {
                    PembinaEkskul::where('guru_id', $guru->id)
                        ->where('ekskul_id', $detail->ekskul_id)
                        ->where('tahun_ajaran_id', current_tahun_id())
                        ->delete();
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

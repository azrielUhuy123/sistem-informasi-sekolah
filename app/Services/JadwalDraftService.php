<?php

namespace App\Services;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\PengajuanJadwal;
use App\Models\PengampuMapel;
use App\Models\WaliKelas;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class JadwalDraftService
{
    private const MAPEL_INTI_WALI_KELAS = [
        'Bahasa Indonesia',
        'Matematika',
        'Pendidikan Pancasila',
        'Ilmu Pengetahuan Alam dan Sosial',
    ];

    public function optionsFor(Guru $guru, $tahunAjaranId = null): Collection
    {
        $tahunAjaranId = $tahunAjaranId ?? current_tahun_id();
        $options = collect();

        $pengampuRows = PengampuMapel::with([
            'kelas.kelasGrup.kelasMaster',
            'mapel',
        ])
            ->where('guru_id', $guru->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->get();

        foreach ($pengampuRows as $row) {
            $options->put($row->kelas_id.'-'.$row->mata_pelajaran_id, $row);
        }

        $waliKelas = WaliKelas::with('kelas.kelasGrup.kelasMaster')
            ->where('guru_id', $guru->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->first();

        if ($waliKelas?->kelas) {
            $mapelInti = MataPelajaran::whereIn(
                'nama_mapel',
                self::MAPEL_INTI_WALI_KELAS
            )->get();

            foreach ($mapelInti as $mapel) {
                $fakePengampu = new PengampuMapel();
                $fakePengampu->kelas_id = $waliKelas->kelas->id;
                $fakePengampu->mata_pelajaran_id = $mapel->id;
                $fakePengampu->sumber = 'wali_kelas';
                $fakePengampu->setRelation('kelas', $waliKelas->kelas);
                $fakePengampu->setRelation('mapel', $mapel);

                $options->put(
                    $waliKelas->kelas->id.'-'.$mapel->id,
                    $fakePengampu
                );
            }
        }

        return $options->values();
    }

    public function createDraft(Guru $guru, array $attributes, $tahunAjaranId = null): ?string
    {
        $tahunAjaranId = (int) ($tahunAjaranId ?? current_tahun_id());

        if (! $tahunAjaranId) {
            return 'Tahun ajaran aktif belum tersedia.';
        }

        return DB::transaction(function () use ($guru, $attributes, $tahunAjaranId) {
            Guru::whereKey($guru->id)->lockForUpdate()->firstOrFail();

            $kelas = Kelas::with('kelasGrup.kelasMaster')
                ->lockForUpdate()
                ->findOrFail((int) $attributes['kelas_id']);

            if (! $this->teacherCanAccessClass($guru->id, $kelas->id, $tahunAjaranId)) {
                return 'Anda tidak memiliki akses ke kelas ini.';
            }

            $mapel = MataPelajaran::findOrFail((int) $attributes['mapel_id']);

            if (! $this->teacherCanAccessSubject($guru->id, $kelas->id, $mapel, $tahunAjaranId)) {
                return 'Anda tidak memiliki assignment mapel ini.';
            }

            if ($this->classHasPublishedSchedule($kelas->id, $tahunAjaranId)) {
                return 'Jadwal sudah dipublish';
            }

            $slot = [
                'kelas_id' => $kelas->id,
                'hari_id' => (int) $attributes['hari_id'],
                'jam_pelajaran_id' => (int) $attributes['jam_pelajaran_id'],
            ];

            if ($this->classSlotIsOccupied($slot, $tahunAjaranId)) {
                return 'Slot sudah terisi';
            }

            if ($this->teacherSlotIsOccupied($guru->id, $slot, $tahunAjaranId)) {
                return 'Guru sudah memiliki jadwal pada slot ini.';
            }

            if ($mapel->jp_per_minggu === null) {
                return 'Kuota JP mapel belum ditetapkan.';
            }

            $currentJP = PengajuanJadwal::where('kelas_id', $kelas->id)
                ->where('mapel_id', $mapel->id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->count();

            if ($currentJP >= $mapel->jp_per_minggu) {
                return 'JP mapel sudah penuh';
            }

            $totalJP = PengajuanJadwal::where('kelas_id', $kelas->id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->count();
            $maxJP = max_jp_kelas($kelas->kelasGrup?->kelasMaster?->tingkat);

            if ($totalJP >= $maxJP) {
                return 'Total JP kelas sudah penuh';
            }

            PengajuanJadwal::create([
                'guru_id' => $guru->id,
                'kelas_id' => $kelas->id,
                'mapel_id' => $mapel->id,
                'hari_id' => $slot['hari_id'],
                'jam_pelajaran_id' => $slot['jam_pelajaran_id'],
                'tahun_ajaran_id' => $tahunAjaranId,
                'status' => 'draft',
            ]);

            return null;
        });
    }

    public function validateSubmission(
        Guru $guru,
        Kelas $kelas,
        Collection $drafts,
        int $tahunAjaranId
    ): ?string {
        foreach ($drafts as $draft) {
            if (
                (int) $draft->guru_id !== $guru->id ||
                (int) $draft->kelas_id !== $kelas->id ||
                (int) $draft->tahun_ajaran_id !== $tahunAjaranId
            ) {
                return 'Data pengajuan tidak sesuai dengan akun atau kelas.';
            }

            if (! $this->teacherCanAccessClass($guru->id, $kelas->id, $tahunAjaranId)) {
                return 'Anda tidak memiliki akses ke kelas ini.';
            }

            $mapel = $draft->mapel ?? MataPelajaran::findOrFail($draft->mapel_id);

            if (! $this->teacherCanAccessSubject($guru->id, $kelas->id, $mapel, $tahunAjaranId)) {
                return 'Pengajuan tidak lagi sesuai dengan assignment mapel.';
            }

            $slot = [
                'kelas_id' => $draft->kelas_id,
                'hari_id' => $draft->hari_id,
                'jam_pelajaran_id' => $draft->jam_pelajaran_id,
            ];

            if ($this->classSlotIsOccupied($slot, $tahunAjaranId, $draft->id)) {
                return 'Slot sudah terisi';
            }

            if ($this->teacherSlotIsOccupied($guru->id, $slot, $tahunAjaranId, $draft->id)) {
                return 'Guru sudah memiliki jadwal pada slot ini.';
            }
        }

        foreach ($drafts->unique('mapel_id') as $draft) {
            $mapel = $draft->mapel ?? MataPelajaran::findOrFail($draft->mapel_id);

            if ($mapel->jp_per_minggu === null) {
                return 'Kuota JP mapel belum ditetapkan.';
            }

            $currentJP = PengajuanJadwal::where('kelas_id', $kelas->id)
                ->where('mapel_id', $mapel->id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->count();

            if ($currentJP > $mapel->jp_per_minggu) {
                return 'JP mapel sudah penuh';
            }
        }

        $totalJP = PengajuanJadwal::where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->count();
        $maxJP = max_jp_kelas($kelas->kelasGrup?->kelasMaster?->tingkat);

        if ($totalJP > $maxJP) {
            return 'Total JP kelas sudah penuh';
        }

        return null;
    }

    public function teacherCanAccessClass(int $guruId, int $kelasId, ?int $tahunAjaranId = null): bool
    {
        $tahunAjaranId = $tahunAjaranId ?? (int) current_tahun_id();

        if (! $tahunAjaranId) {
            return false;
        }

        $waliKelasId = WaliKelas::where('guru_id', $guruId)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->value('kelas_id');

        if ((int) $waliKelasId === $kelasId) {
            return true;
        }

        return PengampuMapel::where('guru_id', $guruId)
            ->where('kelas_id', $kelasId)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->exists();
    }

    private function teacherCanAccessSubject(
        int $guruId,
        int $kelasId,
        MataPelajaran $mapel,
        int $tahunAjaranId
    ): bool {
        $assigned = PengampuMapel::where('guru_id', $guruId)
            ->where('kelas_id', $kelasId)
            ->where('mata_pelajaran_id', $mapel->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->exists();

        if ($assigned) {
            return true;
        }

        $isWaliKelas = WaliKelas::where('guru_id', $guruId)
            ->where('kelas_id', $kelasId)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->exists();

        return $isWaliKelas
            && in_array($mapel->nama_mapel, self::MAPEL_INTI_WALI_KELAS, true);
    }

    private function classHasPublishedSchedule(int $kelasId, int $tahunAjaranId): bool
    {
        return PengajuanJadwal::where('kelas_id', $kelasId)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('status', 'published')
            ->exists();
    }

    private function classSlotIsOccupied(array $slot, int $tahunAjaranId, ?int $exceptId = null): bool
    {
        return PengajuanJadwal::where('kelas_id', $slot['kelas_id'])
            ->where('hari_id', $slot['hari_id'])
            ->where('jam_pelajaran_id', $slot['jam_pelajaran_id'])
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->when($exceptId, fn ($query) => $query->where('id', '!=', $exceptId))
            ->exists();
    }

    private function teacherSlotIsOccupied(
        int $guruId,
        array $slot,
        int $tahunAjaranId,
        ?int $exceptId = null
    ): bool {
        return PengajuanJadwal::where('guru_id', $guruId)
            ->where('hari_id', $slot['hari_id'])
            ->where('jam_pelajaran_id', $slot['jam_pelajaran_id'])
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->when($exceptId, fn ($query) => $query->where('id', '!=', $exceptId))
            ->exists();
    }
}

<?php

namespace App\Actions\Jadwal;

use App\Models\Jadwal;
use App\Models\MataPelajaran;

class GetJPStatus
{
    public function execute(int $kelasId): array
    {
        // Ambil semua mapel, lalu group by kode_mapel
        $mapels = \App\Models\MataPelajaran::all()
            ->groupBy('kode_mapel');

        // Hitung jumlah slot terpakai per kode_mapel
        $jadwalCounts = \App\Models\Jadwal::with('mapel')
            ->where('kelas_id', $kelasId)
            ->get()
            ->groupBy(fn ($j) => $j->mapel->kode_mapel)
            ->map(fn ($items) => $items->count());

        $result = [];

        foreach ($mapels as $kode => $items) {
            // ambil JP target dari fase terendah (A)
            $target = $items->sortBy('fase')->first()->jp_per_minggu;

            $current = $jadwalCounts[$kode] ?? 0;

            $status = 'ok';
            if ($current < $target) $status = 'kurang';
            elseif ($current > $target) $status = 'lebih';

            $result[$kode] = [
                'nama' => $items->first()->nama_mapel,
                'current' => $current,
                'target' => $target,
                'status' => $status,
            ];
        }

        return $result;
    }
    public function executeByGuru(int $guruId): array
    {
        if (!$guruId) {
            throw new \InvalidArgumentException('guruId tidak boleh null');
        }
        $jadwals = \App\Models\Jadwal::with('mapel')
            ->where('guru_id', $guruId)
            ->get();

        $grouped = $jadwals->groupBy(fn ($j) => $j->mapel->kode_mapel);

        $result = [];

        foreach ($grouped as $kode => $items) {
            $target = \App\Models\MataPelajaran::where('kode_mapel', $kode)
                ->orderBy('fase')
                ->value('jp_per_minggu');

            $current = $items->count();

            $status = 'ok';
            if ($current > $target) $status = 'lebih';
            elseif ($current == $target) $status = 'full';

            $result[$kode] = [
                'current' => $current,
                'target' => $target,
                'status' => $status,
            ];
        }

        return $result;
    }
}
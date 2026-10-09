<?php

namespace App\Services;

use App\Models\PpdbPendaftar;
use Illuminate\Database\Eloquent\Builder;

class PpdbReportService
{
    /**
     * Query dasar laporan PPDB
     */
    protected function baseQuery(): Builder
    {
        return PpdbPendaftar::query()
            ->with([
                'tahunAjaran',
                'ayah',
                'ibu',
            ]);
    }

    /**
     * Terapkan filter
     */
    protected function applyFilters(Builder $query, array $filters): Builder
    {
        if (!empty($filters['tahun_ajaran_id'])) {
            $query->where('tahun_ajaran_id', $filters['tahun_ajaran_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['jenis_kelamin'])) {
            $query->where('jenis_kelamin', $filters['jenis_kelamin']);
        }

        if (!empty($filters['tanggal_mulai'])) {
            $query->whereDate(
                'tanggal_pendaftaran',
                '>=',
                $filters['tanggal_mulai']
            );
        }

        if (!empty($filters['tanggal_selesai'])) {
            $query->whereDate(
                'tanggal_pendaftaran',
                '<=',
                $filters['tanggal_selesai']
            );
        }

        if (!empty($filters['search'])) {

            $search = trim($filters['search']);

            $query->where(function ($q) use ($search) {

                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('nomor_kk', 'like', "%{$search}%")
                    ->orWhere('no_pendaftaran', 'like', "%{$search}%");

            });
        }

        return $query;
    }

    /**
     * Statistik Dashboard
     */
    public function statistics(array $filters = []): array
    {
        $query = $this->applyFilters(
            $this->baseQuery(),
            $filters
        );

        return [

            'total' => (clone $query)->count(),

            'pending' => (clone $query)
                ->whereIn('status', ['pending', 'verifikasi'])
                ->count(),

            'diterima' => (clone $query)
                ->where('status', 'diterima')
                ->count(),

            'ditolak' => (clone $query)
                ->where('status', 'ditolak')
                ->count(),

            'laki_laki' => (clone $query)
                ->where('jenis_kelamin', 'L')
                ->count(),

            'perempuan' => (clone $query)
                ->where('jenis_kelamin', 'P')
                ->count(),

        ];
    }

    /**
     * Data tabel (pagination)
     */
    public function paginate(array $filters = [], int $perPage = 15)
    {
        $query = $this->applyFilters(
            $this->baseQuery(),
            $filters
        );

        return $query
            ->latest('tanggal_pendaftaran')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Data Export
     */
    public function export(array $filters = [])
    {
        $query = $this->applyFilters(
            $this->baseQuery(),
            $filters
        );

        return $query
            ->orderBy('nama')
            ->get();
    }

    /**
     * Data untuk grafik (pengembangan berikutnya)
     */
    public function chart(array $filters = [])
    {
        $query = $this->applyFilters(
            $this->baseQuery(),
            $filters
        );

        return $query
            ->selectRaw('MONTH(tanggal_pendaftaran) as bulan, COUNT(*) as jumlah')
            ->groupByRaw('MONTH(tanggal_pendaftaran)')
            ->orderByRaw('MONTH(tanggal_pendaftaran)')
            ->get();
    }
}
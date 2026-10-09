<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $rows = TahunAjaran::query()
            ->orderByRaw("CASE WHEN status = 'aktif' THEN 1 ELSE 0 END DESC")
            ->orderByDesc('tahun')
            ->orderByDesc('semester')
            ->paginate(request('per_page', 10));

        return view('admin.tahun-ajaran.index', [
            'title'       => 'Tahun Ajaran',
            'layout'      => 'layouts.admin',
            'rows'        => $rows,
            'columns'     => [
                ['key' => 'tahun', 'label' => 'Tahun Ajaran'],
                ['key' => 'semester', 'label' => 'Semester'],
                [
                    'key' => 'status',
                    'label' => 'Status',
                    'format' => fn ($row) =>
                        $row->status === 'aktif'
                            ? '<span class="badge bg-success">Aktif</span>'
                            : '<span class="badge bg-secondary">' . e(ucfirst($row->status ?? 'draft')) . '</span>',
                ],
            ],
            'actions' => [
                'activate' => 'admin.tahun-ajaran.activate',
            ],
            'crudMode'    => 'internal_user',
            'routeCreate' => route('admin.tahun-ajaran.create'),
        ]);
    }

    public function create()
    {
        return view('admin.tahun-ajaran.create', [
            'title'  => 'Tambah Tahun Ajaran',
            'layout' => 'layouts.admin',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun'    => 'required|string|max:20',
            'semester' => 'nullable|string|max:10',
        ]);

        \App\Models\TahunAjaran::create([
            'tahun'    => $validated['tahun'],
            'semester' => $validated['semester'] ?? null,
            'status'   => 'draft',
        ]);

        return redirect()
            ->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran berhasil ditambahkan');
    }

    public function activate($id)
    {
        $tahun = TahunAjaran::findOrFail($id);

        if ($tahun->status !== 'draft') {
            return redirect()
                ->route('admin.tahun-ajaran.index')
                ->with('error', 'Hanya Tahun Ajaran berstatus draft yang dapat diaktifkan.');
        }

        if (TahunAjaran::where('status', 'aktif')->exists()) {
            return redirect()
                ->route('admin.tahun-ajaran.index')
                ->with('error', 'Masih ada Tahun Ajaran berstatus aktif.');
        }

        if (
            $tahun->semester === 'Ganjil' &&
            preg_match('/^(\d{4})\/(\d{4})$/', $tahun->tahun, $parts)
        ) {
            $tahunSumber = ((int) $parts[1] - 1) . '/' . ((int) $parts[2] - 1);

            if (TahunAjaran::where('tahun', $tahunSumber)->where('semester', 'Genap')->exists()) {
                return redirect()
                    ->route('admin.tahun-ajaran.index')
                    ->with('error', 'Aktivasi Semester Ganjil tahunan harus melalui proses Penutupan Tahun Ajaran dan Kenaikan Kelas agar penempatan siswa mengikuti kenaikan tahun ajaran.');
            }
        }

        DB::transaction(function () use ($tahun) {
            $tahun->update(['status' => 'aktif']);
        });

        return redirect()
            ->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran berhasil diaktifkan');
    }
}

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
            ->orderByDesc('aktif')
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
                    'key' => 'aktif',
                    'label' => 'Status',
                    'format' => fn ($row) =>
                        $row->aktif
                            ? '<span class="badge bg-success">Aktif</span>'
                            : '<span class="badge bg-secondary">Arsip</span>',
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
            'aktif'    => false, // PENTING: default nonaktif
        ]);

        return redirect()
            ->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran berhasil ditambahkan');
    }

    public function activate($id)
    {
        DB::transaction(function () use ($id) {
            // nonaktifkan semua
            \App\Models\TahunAjaran::where('aktif', true)->update(['aktif' => false]);

            // aktifkan yang dipilih
            \App\Models\TahunAjaran::where('id', $id)->update(['aktif' => true]);
        });

        return redirect()
            ->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran berhasil diaktifkan');
    }
}

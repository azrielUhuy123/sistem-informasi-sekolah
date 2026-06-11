<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PpdbPendaftar;
use App\Models\PpdbSetting;
use App\Models\TahunAjaran;
use App\Models\PpdbOrangtua;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PublicController extends Controller
{

    public function alur()
    {
        return view('ppdb.public.alur');
    }

    public function create()
    {
        $setting = PpdbSetting::first();

        if (
            !$setting ||
            $setting->status != 'dibuka'
        )
        {
            abort(
                403,
                'PPDB sedang ditutup'
            );
        }

        return view(
                'ppdb.public.create',
                compact('setting')
        );
    }
    public function store(Request $request)
    {
        $request->validate([

            'nama'              => 'required',
            'jenis_kelamin'     => 'required',
            'nik'               => 'required',
            'nomor_kk'          => 'required',

            'tempat_lahir'      => 'required',
            'tanggal_lahir'     => 'required',

            'agama'             => 'required',

            'alamat'            => 'required',

            'desa'              => 'required',
            'kecamatan'         => 'required',
            'kabupaten'         => 'required',
            'provinsi'          => 'required',

            'nama_ayah'         => 'required',
            'nama_ibu'          => 'required',
        ]);

        $pendaftar = DB::transaction(function () use ($request) {

            $foto = null;
            $akta = null;
            $kk = null;

            if ($request->hasFile('foto')) {

                $foto = $request
                    ->file('foto')
                    ->store(
                        'ppdb/foto',
                        'public'
                    );
            }

            if ($request->hasFile('akta_lahir')) {

                $akta = $request
                    ->file('akta_lahir')
                    ->store(
                        'ppdb/akta',
                        'public'
                    );
            }

            if ($request->hasFile('kartu_keluarga')) {

                $kk = $request
                    ->file('kartu_keluarga')
                    ->store(
                        'ppdb/kk',
                        'public'
                    );
            }

            $pendaftar = PpdbPendaftar::create([

                'no_pendaftaran'
                    => $this->generateNoPendaftaran(),

                'tanggal_pendaftaran'
                    => now(),

                'status'
                    => 'pending',

                'tahun_ajaran_id'
                    => current_tahun_id(),

                'nama'
                    => $request->nama,

                'jenis_kelamin'
                    => $request->jenis_kelamin,

                'nomor_kk'
                    => $request->nomor_kk,

                'tempat_lahir'
                    => $request->tempat_lahir,

                'tanggal_lahir'
                    => $request->tanggal_lahir,

                'nomor_akta'
                    => $request->nomor_akta,

                'agama'
                    => $request->agama,

                'kewarganegaraan'
                    => $request->kewarganegaraan
                        ?? 'Indonesia',

                'nik'
                    => $request->nik,

                'alamat'
                    => $request->alamat,

                'rt'
                    => $request->rt,

                'rw'
                    => $request->rw,

                'desa'
                    => $request->desa,

                'kecamatan'
                    => $request->kecamatan,

                'kabupaten'
                    => $request->kabupaten,

                'provinsi'
                    => $request->provinsi,

                'telepon_rumah'
                    => $request->telepon_rumah,

                'hp_ayah'
                    => $request->hp_ayah,

                'hp_ibu'
                    => $request->hp_ibu,

                'tinggi_badan'
                    => $request->tinggi_badan,

                'berat_badan'
                    => $request->berat_badan,

                'anak_ke'
                    => $request->anak_ke,

                'jumlah_saudara'
                    => $request->jumlah_saudara,

                'foto'
                    => $foto,

                'akta_lahir'
                    => $akta,

                'kartu_keluarga'
                    => $kk,
            ]);

            PpdbOrangtua::create([

                'ppdb_pendaftar_id'
                    => $pendaftar->id,

                'jenis'
                    => 'ayah',

                'nama'
                    => $request->nama_ayah,

                'nik'
                    => $request->nik_ayah,

                'tempat_lahir'
                    => $request->tempat_lahir_ayah,

                'tanggal_lahir'
                    => $request->tanggal_lahir_ayah,

                'pendidikan'
                    => $request->pendidikan_ayah,

                'pekerjaan'
                    => $request->pekerjaan_ayah,

                'penghasilan'
                    => $request->penghasilan_ayah,
            ]);

            PpdbOrangtua::create([

                'ppdb_pendaftar_id'
                    => $pendaftar->id,

                'jenis'
                    => 'ibu',

                'nama'
                    => $request->nama_ibu,

                'nik'
                    => $request->nik_ibu,

                'tempat_lahir'
                    => $request->tempat_lahir_ibu,

                'tanggal_lahir'
                    => $request->tanggal_lahir_ibu,

                'pendidikan'
                    => $request->pendidikan_ibu,

                'pekerjaan'
                    => $request->pekerjaan_ibu,

                'penghasilan'
                    => $request->penghasilan_ibu,
            ]);

            return $pendaftar;

        });

        return redirect()->route(
            'ppdb.bukti',
            $pendaftar->no_pendaftaran
        );
    }


    public function bukti($no)
    {
        $data = PpdbPendaftar::where(
            'no_pendaftaran',
            $no
        )->firstOrFail();

        return view(
            'ppdb.public.bukti',
            compact('data')
        );
    }
    public function statusForm()
    {
        return view(
            'ppdb.public.status'
        );
    }

    public function cekStatus(Request $request)
    {
        $request->validate([
            'keyword' => 'required'
        ]);

        $data = PpdbPendaftar::where(
                'no_pendaftaran',
                $request->keyword
            )
            ->orWhere(
                'nik',
                $request->keyword
            )
            ->first();

        return view(
            'ppdb.public.status',
            compact('data')
        );
    }

    private function generateNoPendaftaran()
    {
        $last = PpdbPendaftar::max('id') + 1;

        return 'PPDB-'
            . now()->year
            . '-'
            . str_pad(
                $last,
                5,
                '0',
                STR_PAD_LEFT
            );
    }
    public function informasi()
    {
        $setting = PpdbSetting::with(
            'tahunAjaran'
        )->first();

        return view(
            'ppdb.public.informasi',
            compact('setting')
        );
    }

    public function formDaftarUlang()
    {
        return view(
            'ppdb.public.daftar-ulang'
        );
    }

    public function simpanDaftarUlang(
        Request $request
    )
    {
        $request->validate([
            'no_pendaftaran'
                => 'required'
        ]);

        $pendaftar = PpdbPendaftar::where(
            'no_pendaftaran',
            $request->no_pendaftaran
        )
        ->where(
            'status',
            'diterima'
        )
        ->first();

        if (!$pendaftar) {

            return back()->with(
                'error',
                'Data tidak ditemukan'
            );
        }

        $pendaftar->update([

            'status_daftar_ulang'
                => 'sudah',

            'tanggal_daftar_ulang'
                => now(),
        ]);

        return back()->with(
            'success',
            'Daftar ulang berhasil'
        );
    }
}
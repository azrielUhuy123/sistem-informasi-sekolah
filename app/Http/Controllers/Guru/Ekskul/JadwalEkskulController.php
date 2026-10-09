<?php

namespace App\Http\Controllers\Guru\Ekskul;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PembinaEkskul;
use App\Models\HariSekolah;
use App\Models\PengajuanJadwalEkskul;
use App\Services\BusinessNotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JadwalEkskulController extends Controller
{
    public function index()
    {
        $guru = Auth::user()->guru;

        $pembina = PembinaEkskul::with(
                'ekskul'
            )
            ->where(
                'guru_id',
                $guru->id
            )
            ->firstOrFail();

        $hari = HariSekolah::all();

        $jadwal = PengajuanJadwalEkskul::with(
                'hari'
            )
            ->where(
                'ekskul_id',
                $pembina->ekskul_id
            )
            ->where(
                'tahun_ajaran_id',
                current_tahun_id()
            )
            ->orderBy('hari_id')
            ->orderBy('jam_mulai')
            ->get();

        return view(
            'guru.ekskul.jadwal.index',
            compact(
                'pembina',
                'hari',
                'jadwal'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([

            'hari_id'     => 'required|exists:hari_sekolah,id',

            'jam_mulai'   => 'required',

            'jam_selesai' => 'required',

        ]);

        $guru = Auth::user()->guru;

        $pembina = PembinaEkskul::where(
                'guru_id',
                $guru->id
            )
            ->firstOrFail();

        PengajuanJadwalEkskul::create([

            'ekskul_id'       => $pembina->ekskul_id,

            'guru_id'         => $guru->id,

            'hari_id'         => $request->hari_id,

            'tahun_ajaran_id' => current_tahun_id(),

            'jam_mulai'       => $request->jam_mulai,

            'jam_selesai'     => $request->jam_selesai,

            'status'          => 'draft',

        ]);

        return back()->with(
            'success',
            'Jadwal berhasil ditambahkan'
        );
    }

    public function edit(
        PengajuanJadwalEkskul $jadwal
    )
    {
        $guru = Auth::user()->guru;

        $hari = HariSekolah::orderBy(
            'id'
        )->get();

        /*
        |--------------------------------------------------------------------------
        | CEK KEPEMILIKAN DATA
        |--------------------------------------------------------------------------
        */

        if (
            $jadwal->guru_id != $guru->id
        ) {
            abort(403);
        }

        return view(
            'guru.ekskul.jadwal.edit',
            compact(
                'jadwal',
                'hari'
            )
        );
    }

    public function update(
        Request $request,
        PengajuanJadwalEkskul $jadwal
    )
    {
        $request->validate([

            'hari_id'     => 'required|exists:hari_sekolah,id',

            'jam_mulai'   => 'required',

            'jam_selesai' => 'required',

        ]);

        $guru = Auth::user()->guru;

        /*
        |--------------------------------------------------------------------------
        | CEK KEPEMILIKAN DATA
        |--------------------------------------------------------------------------
        */

        $submission = DB::transaction(function () use ($jadwal, $guru, $request) {
            $jadwal = PengajuanJadwalEkskul::whereKey($jadwal->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($jadwal->guru_id != $guru->id) {
                abort(403);
            }

            if (! in_array($jadwal->status, ['draft', 'rejected', 'submitted'], true)) {
                return false;
            }

            $isNewSubmission = in_array($jadwal->status, ['draft', 'rejected'], true);

            $jadwal->update([
                'hari_id' => $request->hari_id,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'status' => 'submitted',
                'catatan' => null,
            ]);

            return $isNewSubmission ? $jadwal : null;
        });

        if ($submission === false) {
            return back()->with(
                'error',
                'Jadwal tidak dapat diperbarui pada status saat ini.'
            );
        }

        if ($submission instanceof PengajuanJadwalEkskul) {
            app(BusinessNotificationService::class)
                ->notifyExtracurricularScheduleSubmitted($submission->ekskul);
        }

        return redirect()
            ->route(
                'guru.ekskul.validasi.index'
            )
            ->with(
                'success',
                'Jadwal berhasil diperbarui dan dikirim ulang untuk validasi.'
            );
    }

    public function destroy(
        PengajuanJadwalEkskul $jadwal
    )
    {
        $guru = Auth::user()->guru;

        if (
            $jadwal->guru_id != $guru->id
        ) {
            abort(403);
        }

        $jadwal->delete();

        return back()->with(
            'success',
            'Jadwal berhasil dihapus'
        );
    }
}
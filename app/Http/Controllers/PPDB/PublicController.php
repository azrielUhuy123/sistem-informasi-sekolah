<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PpdbPendaftar;
use App\Models\PpdbSetting;
use App\Models\TahunAjaran;
use App\Models\PpdbOrangtua;
use App\Services\BusinessNotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Mail\PpdbPendaftaranMail;
use Illuminate\Support\Facades\URL;


class PublicController extends Controller
{

    public function alur()
    {
        return view('ppdb.public.alur');
    }

    public function create()
    {
        $tahunAjaranId = current_tahun_id();
        $setting = PpdbSetting::where('tahun_ajaran_id', $tahunAjaranId)
            ->orderBy('id')
            ->first();

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
                'ppdb.public.ppdb.form',
                compact('setting')
        );
    }
    public function store(Request $request)
    {
        $schoolRequired = $request->input('jenis_pendidikan_asal') !== 'Belum Pernah Sekolah';

        $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'nik' => ['required', 'digits:16'],
            'nomor_kk' => ['required', 'digits:16'],
            'tempat_lahir' => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],
            'nomor_akta' => ['nullable', 'string', 'max:30'],
            'agama' => ['required', 'in:Islam,Kristen Protestan,Kristen Katolik,Hindu,Buddha,Konghucu'],
            'kewarganegaraan' => ['required', 'in:Indonesia,WNA'],

            'provinsi' => ['required', 'string', 'max:100'],
            'kabupaten' => ['required', 'string', 'max:100'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'desa' => ['required', 'string', 'max:100'],
            'rt' => ['required', 'digits_between:1,3'],
            'rw' => ['required', 'digits_between:1,3'],
            'alamat' => ['required', 'string', 'max:255'],

            'jenis_pendidikan_asal' => ['required', 'in:TK,RA,SPS,KB,TPA,Lainnya,Belum Pernah Sekolah'],
            'nama_sekolah_asal' => [$schoolRequired ? 'required' : 'nullable', 'string', 'max:150'],
            'nisn_sekolah_asal' => [$schoolRequired ? 'required' : 'nullable', 'digits:10'],

            'nama_ayah' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\'.-]+$/u'],
            'nik_ayah' => ['nullable', 'digits:16'],
            'tempat_lahir_ayah' => ['required', 'string', 'max:100'],
            'tanggal_lahir_ayah' => ['required', 'date'],
            'agama_ayah' => ['required', 'in:Islam,Kristen Protestan,Kristen Katolik,Hindu,Buddha,Konghucu'],
            'pendidikan_ayah' => ['required', 'in:Tidak/Belum Sekolah,SD,SMP,SMA/SMK,Diploma I,Diploma II,Diploma III,Diploma IV,Sarjana (S1),Magister (S2),Doktor (S3)'],
            'pekerjaan_ayah' => ['required', 'in:Tidak/Belum Bekerja,Mengurus Rumah Tangga (IRT),Petani,Nelayan,Buruh,Pedagang,Wiraswasta,Pegawai Swasta,Pegawai BUMN,PNS,TNI,POLRI,Guru,Dosen,Tenaga Kesehatan,Pengemudi/Ojek Online,Pensiunan,Lainnya'],
            'penghasilan_ayah' => ['nullable', 'in:<500.000,500.000-999.999,1.000.000-1.999.999,2.000.000-4.999.999,>5.000.000'],

            'nama_ibu' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\'.-]+$/u'],
            'nik_ibu' => ['nullable', 'digits:16'],
            'tempat_lahir_ibu' => ['required', 'string', 'max:100'],
            'tanggal_lahir_ibu' => ['required', 'date'],
            'agama_ibu' => ['required', 'in:Islam,Kristen Protestan,Kristen Katolik,Hindu,Buddha,Konghucu'],
            'pendidikan_ibu' => ['required', 'in:Tidak/Belum Sekolah,SD,SMP,SMA/SMK,Diploma I,Diploma II,Diploma III,Diploma IV,Sarjana (S1),Magister (S2),Doktor (S3)'],
            'pekerjaan_ibu' => ['required', 'in:Tidak/Belum Bekerja,Mengurus Rumah Tangga (IRT),Petani,Nelayan,Buruh,Pedagang,Wiraswasta,Pegawai Swasta,Pegawai BUMN,PNS,TNI,POLRI,Guru,Dosen,Tenaga Kesehatan,Pengemudi/Ojek Online,Pensiunan,Lainnya'],
            'penghasilan_ibu' => ['nullable', 'in:<500.000,500.000-999.999,1.000.000-1.999.999,2.000.000-4.999.999,>5.000.000'],

            'nomor_hp' => ['required', 'regex:/^08\d{8,11}$/'],
            'email' => ['required', 'email', 'max:255'],
            'email_confirmation' => ['required', 'same:email'],

            'tinggi_badan' => ['required', 'numeric', 'decimal:0,1', 'min:30', 'max:250'],
            'berat_badan' => ['required', 'numeric', 'decimal:0,1', 'min:5', 'max:150'],
            'anak_ke' => ['required', 'integer', 'min:1', 'max:20'],
            'jumlah_saudara' => ['required', 'integer', 'min:0', 'max:20'],

            'kartu_keluarga' => ['required', 'file', 'mimes:pdf,jpg,jpeg', 'max:2048'],
            'ktp' => ['required', 'file', 'mimes:pdf,jpg,jpeg', 'max:2048'],
            'ijazah' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg', 'max:2048'],
            'akta_lahir' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg', 'max:2048'],
            'kartu_kip' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg', 'max:2048'],
            'foto' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'declaration' => ['accepted'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
            'nomor_kk.required' => 'Nomor KK wajib diisi.',
            'nomor_kk.digits' => 'Nomor KK harus terdiri dari 16 digit angka.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'agama.required' => 'Agama wajib dipilih.',
            'kewarganegaraan.required' => 'Kewarganegaraan wajib dipilih.',
            'provinsi.required' => 'Provinsi wajib dipilih.',
            'kabupaten.required' => 'Kabupaten/Kota wajib dipilih.',
            'kecamatan.required' => 'Kecamatan wajib dipilih.',
            'desa.required' => 'Kelurahan/Desa wajib dipilih.',
            'rt.required' => 'RT wajib diisi.',
            'rw.required' => 'RW wajib diisi.',
            'alamat.required' => 'Alamat lengkap wajib diisi.',
            'jenis_pendidikan_asal.required' => 'Jenis pendidikan asal wajib dipilih.',
            'nama_sekolah_asal.required' => 'Nama sekolah asal wajib diisi.',
            'nisn_sekolah_asal.required' => 'NISN sekolah asal wajib diisi.',
            'nisn_sekolah_asal.digits' => 'NISN harus terdiri dari 10 digit angka.',
            'nama_ayah.required' => 'Nama ayah wajib diisi.',
            'nama_ibu.required' => 'Nama ibu wajib diisi.',
            'tempat_lahir_ayah.required' => 'Tempat lahir ayah wajib diisi.',
            'tanggal_lahir_ayah.required' => 'Tanggal lahir ayah wajib diisi.',
            'agama_ayah.required' => 'Agama ayah wajib dipilih.',
            'pendidikan_ayah.required' => 'Pendidikan ayah wajib dipilih.',
            'pekerjaan_ayah.required' => 'Pekerjaan ayah wajib dipilih.',
            'tempat_lahir_ibu.required' => 'Tempat lahir ibu wajib diisi.',
            'tanggal_lahir_ibu.required' => 'Tanggal lahir ibu wajib diisi.',
            'agama_ibu.required' => 'Agama ibu wajib dipilih.',
            'pendidikan_ibu.required' => 'Pendidikan ibu wajib dipilih.',
            'pekerjaan_ibu.required' => 'Pekerjaan ibu wajib dipilih.',
            'nomor_hp.required' => 'Nomor HP/WhatsApp wajib diisi.',
            'nomor_hp.regex' => 'Nomor HP/WhatsApp harus diawali 08 dan terdiri dari 10-13 digit.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email_confirmation.required' => 'Konfirmasi email wajib diisi.',
            'email_confirmation.same' => 'Konfirmasi email tidak sama dengan email.',
            'anak_ke.required' => 'Anak ke wajib diisi.',
            'jumlah_saudara.required' => 'Jumlah saudara wajib diisi.',
            'tinggi_badan.required' => 'Tinggi badan wajib diisi.',
            'berat_badan.required' => 'Berat badan wajib diisi.',
            'kartu_keluarga.required' => 'Kartu keluarga wajib diunggah.',
            'ktp.required' => 'KTP orang tua wajib diunggah.',
            'foto.required' => 'Foto wajib diunggah.',
            'declaration.accepted' => 'Pernyataan wajib disetujui.',
        ]);

        $pendaftar = DB::transaction(function () use ($request) {

            $tahunAjaran = TahunAjaran::whereKey(current_tahun_id())
                ->lockForUpdate()
                ->firstOrFail();

            $setting = PpdbSetting::where('tahun_ajaran_id', $tahunAjaran->id)
                ->lockForUpdate()
                ->first();

            if (!$setting || $setting->status !== 'dibuka') {
                abort(403, 'PPDB sedang ditutup');
            }

            $uploadedFiles = [];
            foreach ([
                'kartu_keluarga' => 'ppdb/kk',
                'ktp' => 'ppdb/ktp',
                'ijazah' => 'ppdb/ijazah',
                'akta_lahir' => 'ppdb/akta',
                'kartu_kip' => 'ppdb/kip',
                'foto' => 'ppdb/foto',
            ] as $field => $directory) {
                if ($request->hasFile($field)) {
                    $uploadedFiles[$field] = $request->file($field)->store($directory, 'public');
                }
            }

            $pendaftar = PpdbPendaftar::create([

                'no_pendaftaran'
                    => $this->generateNoPendaftaran($tahunAjaran),

                'tanggal_pendaftaran'
                    => now(),

                'status'
                    => 'pending',

                'tahun_ajaran_id'
                    => $tahunAjaran->id,

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

                'jenis_pendidikan_asal'
                    => $request->jenis_pendidikan_asal,

                'nama_sekolah_asal'
                    => $request->nama_sekolah_asal,

                'nisn_sekolah_asal'
                    => $request->nisn_sekolah_asal,

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

                'nomor_hp'
                    => $request->nomor_hp,

                'email'
                    => $request->email,

                'tinggi_badan'
                    => $request->tinggi_badan,

                'berat_badan'
                    => $request->berat_badan,

                'anak_ke'
                    => $request->anak_ke,

                'jumlah_saudara'
                    => $request->jumlah_saudara,

                'foto'
                    => $uploadedFiles['foto'] ?? null,

                'akta_lahir'
                    => $uploadedFiles['akta_lahir'] ?? null,

                'kartu_keluarga'
                    => $uploadedFiles['kartu_keluarga'] ?? null,

                'ktp'
                    => $uploadedFiles['ktp'] ?? null,

                'ijazah'
                    => $uploadedFiles['ijazah'] ?? null,

                'kartu_kip'
                    => $uploadedFiles['kartu_kip'] ?? null,
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

                'agama'
                    => $request->agama_ayah,

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

                'agama'
                    => $request->agama_ibu,

                'pendidikan'
                    => $request->pendidikan_ibu,

                'pekerjaan'
                    => $request->pekerjaan_ibu,

                'penghasilan'
                    => $request->penghasilan_ibu,
            ]);

            return $pendaftar;

        });

        $statusUrl = URL::temporarySignedRoute(
            'ppdb.status.link',
            now()->addDays(7),
            [
                'pendaftar' => $pendaftar->id,
            ]
        );

        $emailSent = $this->sendPpdbEmail($pendaftar, $statusUrl);

        if ($pendaftar->status === 'pending') {
            try {
                app(BusinessNotificationService::class)
                    ->notifyPendingPpdbRegistration($pendaftar);
            } catch (\Throwable $exception) {
                Log::error('Notifikasi internal PPDB gagal dibuat.', [
                    'pendaftar_id' => $pendaftar->id,
                    'no_pendaftaran' => $pendaftar->no_pendaftaran,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        $response = redirect()->route(
            'ppdb.bukti',
            $pendaftar->no_pendaftaran
        );

        if (!$emailSent) {
            $response->with(
                'warning',
                'Pendaftaran berhasil disimpan, tetapi email konfirmasi tidak dapat dikirim.'
            );
        }

        return $response;
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
            'keyword' => 'required|string',
            'tanggal_lahir' => 'required|date',
        ]);

        $data = PpdbPendaftar::where(
            'no_pendaftaran',
            $request->keyword
        )->whereDate(
            'tanggal_lahir',
            $request->tanggal_lahir
        )->first();

        if (!$data) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Nomor pendaftaran atau tanggal lahir tidak sesuai.'
                );
        }

        return view(
            'ppdb.public.status',
            [
                'data' => $data,
                'viaLink' => false,
            ]
        );
    }

    private function generateNoPendaftaran(TahunAjaran $tahunAjaran)
    {
        $prefix = 'PPDB-' . $tahunAjaran->tahun . '-';
        $lastSequence = PpdbPendaftar::query()
            ->where('tahun_ajaran_id', $tahunAjaran->id)
            ->where('no_pendaftaran', 'like', $prefix . '%')
            ->pluck('no_pendaftaran')
            ->map(function ($registrationNumber) use ($prefix) {
                $sequence = substr($registrationNumber, strlen($prefix));

                return ctype_digit($sequence) ? (int) $sequence : 0;
            })
            ->max() ?? 0;

        return $prefix . str_pad($lastSequence + 1, 3, '0', STR_PAD_LEFT);
    }
    public function informasi()
    {
        $tahunAjaranId = current_tahun_id();
        $setting = PpdbSetting::with(
            'tahunAjaran'
        )
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->orderBy('id')
            ->first();

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
                'no_pendaftaran' => ['required', 'string'],
                'tanggal_lahir' => ['required', 'date'],
        ]);

            $noPendaftaran = $request->input('no_pendaftaran');
            $tanggalLahir = $request->input('tanggal_lahir');

            $updated = DB::transaction(function () use ($noPendaftaran, $tanggalLahir) {
            $updated = PpdbPendaftar::where(
                'no_pendaftaran',
                    $noPendaftaran
                )
                ->whereDate(
                    'tanggal_lahir',
                    $tanggalLahir
            )
            ->where(
                'status',
                'diterima'
            )
            ->where(function ($query) {
                $query
                    ->whereNull('status_daftar_ulang')
                    ->orWhere('status_daftar_ulang', '!=', 'sudah');
            })
            ->update([
                'status_daftar_ulang' => 'sudah',
                'tanggal_daftar_ulang' => now(),
            ]);

            if ($updated > 0) {
                $pendaftar = PpdbPendaftar::where(
                    'no_pendaftaran',
                    $noPendaftaran
                )
                ->whereDate(
                    'tanggal_lahir',
                    $tanggalLahir
                )
                ->where('status', 'diterima')
                ->where('status_daftar_ulang', 'sudah')
                ->firstOrFail();

                app(BusinessNotificationService::class)
                    ->notifyPpdbDaftarUlangSubmitted($pendaftar);
            }

            return $updated;
        });

        if ($updated === 0) {
            $alreadyConfirmed = PpdbPendaftar::where(
                'no_pendaftaran',
                $noPendaftaran
            )
            ->whereDate(
                'tanggal_lahir',
                $tanggalLahir
            )
            ->where(
                'status',
                'diterima'
            )
            ->where(
                'status_daftar_ulang',
                'sudah'
            )
            ->exists();

            if ($alreadyConfirmed) {
                return back()->with(
                    'error',
                    'Daftar ulang sudah pernah dikonfirmasi.'
                );
            }

            return back()->withInput()->with(
                'error',
                'Nomor pendaftaran atau tanggal lahir tidak sesuai, atau pendaftar belum memenuhi syarat daftar ulang.'
            );
        }

        return back()->with(
            'success',
            'Daftar ulang berhasil'
        );
    }

    public function statusByLink(PpdbPendaftar $pendaftar)
    {
        return view(
            'ppdb.public.status',
            [
                'data' => $pendaftar,
                'viaLink' => true,
            ]
        );
    }

    private function sendPpdbEmail(
        PpdbPendaftar $pendaftar,
        string $statusUrl
    ): bool
    {
        if (empty($pendaftar->email)) {
            return false;
        }

        try {
            Mail::to($pendaftar->email)
                ->send(new PpdbPendaftaranMail($pendaftar, $statusUrl));

            return true;
        } catch (\Throwable $exception) {
            Log::error('Email PPDB gagal dikirim.', [
                'pendaftar_id' => $pendaftar->id,
                'email' => $pendaftar->email,
                'status' => $pendaftar->status,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }
}
<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\KelasGrup;
use App\Models\KelasMaster;
use App\Models\TahunAjaran;
use App\Models\PenempatanSiswa;
use App\Models\Angkatan;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use PhpOffice\PhpSpreadsheet\Shared\Date;

class SiswaSheetImport implements ToCollection, WithHeadingRow
{
    protected $tahunAjaran;

    public function __construct()
    {
        /*
        |--------------------------------------------------------------------------
        | AMBIL TAHUN AJARAN TERBARU
        |--------------------------------------------------------------------------
        */

        $this->tahunAjaran = TahunAjaran::latest()->first();

        if (!$this->tahunAjaran) {
            throw new \Exception("Data tahun ajaran belum tersedia.");
        }
    }

    public function collection(Collection $rows)
    {
        if (!$rows->count()) {
            throw new \Exception("File kosong atau header tidak sesuai.");
        }

        DB::transaction(function () use ($rows) {

            foreach ($rows as $row) {

                /*
                |--------------------------------------------------------------------------
                | SKIP ROW KOSONG
                |--------------------------------------------------------------------------
                */

                if (empty($row['nis'])) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | BASIC DATA
                |--------------------------------------------------------------------------
                */

                $nis     = trim((string) $row['nis']);
                $nisn    = trim((string) $row['nisn']);
                $nama    = trim((string) $row['nama']);
                $tingkat = trim((string) $row['tingkat']);
                $rombel  = strtoupper(trim((string) $row['rombel']));

                /*
                |--------------------------------------------------------------------------
                | VALIDASI NISN
                |--------------------------------------------------------------------------
                */

                if (!is_numeric($nisn) || strlen($nisn) != 10) {

                    throw new \Exception(
                        "NISN harus 10 digit angka pada NIS {$nis}"
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | RESOLVE ANGKATAN
                |--------------------------------------------------------------------------
                */

                $angkatan = $this->resolveAngkatanByTingkat($tingkat);

                /*
                |--------------------------------------------------------------------------
                | VALIDASI KELAS MASTER
                |--------------------------------------------------------------------------
                */

                $kelasMaster = KelasMaster::where('tingkat', $tingkat)->first();

                if (!$kelasMaster) {

                    throw new \Exception(
                        "Kelas master tingkat {$tingkat} tidak ditemukan."
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | RESOLVE KELAS GRUP
                |--------------------------------------------------------------------------
                */

                $kelasGrup = KelasGrup::where('kelas_master_id', $kelasMaster->id)
                    ->where('angkatan_id', $angkatan->id)
                    ->first();

                if (!$kelasGrup) {

                    throw new \Exception(
                        "Kelas grup tingkat {$tingkat} untuk angkatan {$angkatan->tahun} tidak ditemukan."
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | RESOLVE KELAS
                |--------------------------------------------------------------------------
                */

                $namaKelas = $tingkat . $rombel;

                $kelas = Kelas::firstOrCreate([
                    'nama_kelas'    => $namaKelas,
                    'kelas_grup_id' => $kelasGrup->id
                ]);

                /*
                |--------------------------------------------------------------------------
                | VALIDASI JENIS KELAMIN
                |--------------------------------------------------------------------------
                */

                $jk = $this->normalizeJK($row['jenis_kelamin']);

                /*
                |--------------------------------------------------------------------------
                | HANDLE TANGGAL LAHIR
                |--------------------------------------------------------------------------
                */

                $tanggalLahir = null;

                if (!empty($row['tanggal_lahir'])) {

                    $raw = $row['tanggal_lahir'];

                    // Format excel serial number
                    if (is_numeric($raw)) {

                        $tanggalLahir = Date::excelToDateTimeObject($raw)
                            ->format('Y-m-d');

                    } else {

                        // Format m/d/Y
                        try {

                            $tanggalLahir = Carbon::createFromFormat('m/d/Y', $raw)
                                ->format('Y-m-d');

                        } catch (\Exception $e) {

                            // Format Y-m-d
                            try {

                                $tanggalLahir = Carbon::createFromFormat('Y-m-d', $raw)
                                    ->format('Y-m-d');

                            } catch (\Exception $e) {

                                throw new \Exception(
                                    "Tanggal lahir tidak valid: {$raw}"
                                );
                            }
                        }
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | CREATE / GET SISWA
                |--------------------------------------------------------------------------
                */

                $siswa = Siswa::firstOrCreate(
                    ['nis' => $nis],
                    [
                        'nisn'          => $nisn,
                        'nama'          => $nama,
                        'jenis_kelamin' => $jk,
                        'tanggal_lahir' => $tanggalLahir,
                        'status'        => 'aktif'
                    ]
                );

                /*
                |--------------------------------------------------------------------------
                | CEK PENEMPATAN AKTIF
                |--------------------------------------------------------------------------
                */

                $penempatanAktif = PenempatanSiswa::where('siswa_id', $siswa->id)
                    ->where('tahun_ajaran_id', $this->tahunAjaran->id)
                    ->whereNull('tanggal_keluar')
                    ->first();

                /*
                |--------------------------------------------------------------------------
                | BELUM ADA PENEMPATAN
                |--------------------------------------------------------------------------
                */

                if (!$penempatanAktif) {

                    PenempatanSiswa::create([
                        'siswa_id'        => $siswa->id,
                        'kelas_id'        => $kelas->id,
                        'tahun_ajaran_id' => $this->tahunAjaran->id,
                        'tanggal_masuk'   => now(),
                        'tanggal_keluar'  => null
                    ]);

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | JIKA PINDAH KELAS
                |--------------------------------------------------------------------------
                */

                if ($penempatanAktif->kelas_id != $kelas->id) {

                    // Tutup penempatan lama
                    $penempatanAktif->update([
                        'tanggal_keluar' => now()
                    ]);

                    // Buat penempatan baru
                    PenempatanSiswa::create([
                        'siswa_id'        => $siswa->id,
                        'kelas_id'        => $kelas->id,
                        'tahun_ajaran_id' => $this->tahunAjaran->id,
                        'tanggal_masuk'   => now(),
                        'tanggal_keluar'  => null
                    ]);
                }
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RESOLVE ANGKATAN BERDASARKAN TINGKAT
    |--------------------------------------------------------------------------
    */

    private function resolveAngkatanByTingkat($tingkat)
    {
        $tahunAwal = (int) explode('/', $this->tahunAjaran->tahun)[0];

        $tahunAngkatan = $tahunAwal - ((int)$tingkat - 1);

        $angkatan = Angkatan::where('tahun', $tahunAngkatan)->first();

        if (!$angkatan) {

            throw new \Exception(
                "Angkatan {$tahunAngkatan} tidak ditemukan."
            );
        }

        return $angkatan;
    }

    /*
    |--------------------------------------------------------------------------
    | NORMALIZE JENIS KELAMIN
    |--------------------------------------------------------------------------
    */

    private function normalizeJK($value)
    {
        $v = strtoupper(trim($value));

        if (in_array($v, ['L', 'LAKI-LAKI'])) {
            return 'L';
        }

        if (in_array($v, ['P', 'PEREMPUAN'])) {
            return 'P';
        }

        throw new \Exception(
            "Jenis kelamin tidak valid: {$value}"
        );
    }
}
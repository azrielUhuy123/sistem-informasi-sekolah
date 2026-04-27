<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\KelasGrup;
use App\Models\TahunAjaran;
use App\Models\PenempatanSiswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SiswaImport implements ToCollection, WithHeadingRow
{
    protected $tahunAjaran;

    public function __construct()
    {
        $this->tahunAjaran = TahunAjaran::aktif();

        if (!$this->tahunAjaran) {
            throw new \Exception("Tidak ada tahun ajaran aktif.");
        }

        if ($this->tahunAjaran->status !== 'aktif') {
            throw new \Exception("Import hanya boleh saat tahun ajaran AKTIF (awal tahun).");
        }
    }

    public function collection(Collection $rows)
    {
        
        if (!$rows->count()) {
            throw new \Exception("File kosong atau header tidak sesuai.");
        }

        $angkatan = \App\Models\Angkatan::where('aktif', 1)->first();

        if (!$angkatan) {
            throw new \Exception("Angkatan untuk tahun ajaran aktif tidak ditemukan.");
        }


        DB::transaction(function () use ($rows, $angkatan) {
            $total = 0;
            $created = 0;
            $updated = 0;

            foreach ($rows as $row) {
            
                if (empty($row['nis'])) {
                    continue;
                }
                $total++;

                $nis     = trim((string) $row['nis']);
                $nisn    = trim((string) $row['nisn']);
                $tingkat = trim($row['tingkat']);
                $rombel  = trim($row['rombel']);

                $namaKelas = $tingkat . $rombel;

                // Cari kelas_master berdasarkan tingkat
                $kelasMaster = \App\Models\KelasMaster::where('tingkat', $tingkat)->first();

                if (!$kelasMaster) {
                    throw new \Exception("Tingkat {$tingkat} tidak ditemukan di kelas_master.");
                }
                // Cari kelas_grup berdasarkan kelas_master + angkatan
                $kelasGrup = \App\Models\KelasGrup::where('kelas_master_id', $kelasMaster->id)
                    ->where('angkatan_id', $angkatan->id)
                    ->first();

                if (!$kelasGrup) {
                    throw new \Exception("KelasGrup untuk tingkat {$tingkat} tidak ditemukan.");
                }

                // Auto create rombel jika belum ada
                $kelas = Kelas::firstOrCreate([
                    'nama_kelas'   => $namaKelas,
                    'kelas_grup_id'=> $kelasGrup->id
                ]);

                // validasi isi baris
                $jk = $this->normalizeJK($row['jenis_kelamin']);

                if (!is_numeric($nisn) || strlen($nisn) != 10) {
                    throw new \Exception("NISN harus 10 digit angka pada NIS {$nis}");
                }
                
                //handle tanggal lahir yang bisa berupa string atau excel date
                $tanggalLahir = null;

                if (!empty($row['tanggal_lahir'])) {

                    $raw = $row['tanggal_lahir'];

                    // Jika Excel numeric date (serial)
                    if (is_numeric($raw)) {
                        $tanggalLahir = Date::excelToDateTimeObject($raw)
                                            ->format('Y-m-d');
                    } 
                    else {
                        // Coba format US: m/d/Y (karena Excel Anda US style)
                        try {
                            $tanggalLahir = Carbon::createFromFormat('m/d/Y', $raw)
                                                ->format('Y-m-d');
                        } catch (\Exception $e) {

                            // Fallback jika memang sudah Y-m-d
                            try {
                                $tanggalLahir = Carbon::createFromFormat('Y-m-d', $raw)
                                                    ->format('Y-m-d');
                            } catch (\Exception $e) {
                                throw new \Exception("Format tanggal_lahir tidak valid: {$raw}");
                            }
                        }
                    }
                }
                
                // Buat atau ambil siswa
                $siswa = Siswa::firstOrCreate(
                    ['nis' => $nis],
                    [
                        'nisn'          => $nisn,
                        'nama'          => $row['nama'],
                        'jenis_kelamin' => $jk,
                        'tanggal_lahir' => $tanggalLahir,
                    ]
                );

                $penempatanAktif = PenempatanSiswa::where('siswa_id', $siswa->id)
                    ->where('tahun_ajaran_id', $this->tahunAjaran->id)
                    ->whereNull('tanggal_keluar')
                    ->first();

                if (!$penempatanAktif) {

                    PenempatanSiswa::create([
                        'siswa_id'        => $siswa->id,
                        'kelas_id'        => $kelas->id,
                        'tahun_ajaran_id' => $this->tahunAjaran->id,
                        'tanggal_masuk'   => now(),
                        'tanggal_keluar'  => null
                    ]);
                    $created++;
                } else {

                    if ($penempatanAktif->kelas_id != $kelas->id) {

                        $penempatanAktif->update([
                            'tanggal_keluar' => now()
                        ]);

                        PenempatanSiswa::create([
                            'siswa_id'        => $siswa->id,
                            'kelas_id'        => $kelas->id,
                            'tahun_ajaran_id' => $this->tahunAjaran->id,
                            'tanggal_masuk'   => now(),
                            'tanggal_keluar'  => null
                        ]);
                        $updated++;
                    }
                }
            }
            session()->flash('import_report', [
            'total'   => $total,
            'created' => $created,
            'updated' => $updated
            ]);
        });
    }
    private function normalizeJK($value)
    {
        $v = strtoupper(trim($value));

        if (in_array($v, ['L', 'LAKI-LAKI'])) return 'L';
        if (in_array($v, ['P', 'PEREMPUAN'])) return 'P';

        throw new \Exception("Jenis kelamin tidak valid: {$value}");
    }
}
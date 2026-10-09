<?php

namespace Tests\Feature;

use App\Models\DataKepegawaian;
use App\Models\Ekskul;
use App\Models\EkskulSiswa;
use App\Models\Guru;
use App\Models\PembinaEkskul;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EkskulAbsensiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_absensi_page_handles_deleted_member_records(): void
    {
        $pegawai = DataKepegawaian::create([
            'nama' => 'Guru Test',
            'nip' => '123456',
        ]);

        $guru = Guru::create([
            'data_kepegawaian_id' => $pegawai->id,
        ]);

        $user = User::create([
            'pegawai_id' => $pegawai->id,
            'role' => 'guru',
            'name' => 'Guru Test',
            'email' => 'guru@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
            'approval_status' => 'approved',
        ]);

        $tahun = TahunAjaran::create([
            'tahun' => '2026/2027',
            'semester' => 'ganjil',
            'status' => 'aktif',
        ]);

        $ekskul = Ekskul::create([
            'nama' => 'Basket',
            'hari' => 'Senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        PembinaEkskul::create([
            'guru_id' => $guru->id,
            'ekskul_id' => $ekskul->id,
            'tahun_ajaran_id' => $tahun->id,
        ]);

        $siswa = Siswa::create([
            'nama' => 'Siswa Hilang',
            'nis' => '10001',
        ]);

        EkskulSiswa::create([
            'ekskul_id' => $ekskul->id,
            'siswa_id' => $siswa->id,
            'tahun_ajaran_id' => $tahun->id,
            'status' => 'aktif',
        ]);

        $siswa->delete();

        $response = $this->actingAs($user)->get('/guru/ekskul/absensi');

        $response->assertOk();
        $response->assertSeeText('Absensi Ekstrakurikuler');
    }

    public function test_guru_catatan_pembinaan_page_handles_deleted_member_records(): void
    {
        $pegawai = DataKepegawaian::create([
            'nama' => 'Guru Test',
            'nip' => '123456',
        ]);

        $guru = Guru::create([
            'data_kepegawaian_id' => $pegawai->id,
        ]);

        $user = User::create([
            'pegawai_id' => $pegawai->id,
            'role' => 'guru',
            'name' => 'Guru Test',
            'email' => 'guru-catatan@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
            'approval_status' => 'approved',
        ]);

        $tahun = TahunAjaran::create([
            'tahun' => '2026/2027',
            'semester' => 'ganjil',
            'status' => 'aktif',
        ]);

        $ekskul = Ekskul::create([
            'nama' => 'Basket',
            'hari' => 'Senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        PembinaEkskul::create([
            'guru_id' => $guru->id,
            'ekskul_id' => $ekskul->id,
            'tahun_ajaran_id' => $tahun->id,
        ]);

        $siswa = Siswa::create([
            'nama' => 'Siswa Hilang',
            'nis' => '10002',
        ]);

        EkskulSiswa::create([
            'ekskul_id' => $ekskul->id,
            'siswa_id' => $siswa->id,
            'tahun_ajaran_id' => $tahun->id,
            'status' => 'aktif',
        ]);

        $siswa->delete();

        $response = $this->actingAs($user)->get('/guru/ekskul/catatan-pembinaan');

        $response->assertOk();
        $response->assertSeeText('Data Siswa Hilang');
    }
}

<?php

namespace Tests\Feature;

use App\Exports\PpdbDiterimaExport;
use App\Models\PpdbPendaftar;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class PpdbDiterimaExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_export_only_contains_accepted_registrants_from_the_selected_year(): void
    {
        $tahunA = $this->makeTahunAjaran('2026/2027', 'aktif');
        $tahunB = $this->makeTahunAjaran('2027/2028', 'draft');
        $acceptedA = $this->makePendaftar($tahunA, 'diterima', 'sudah');
        $this->makePendaftar($tahunB, 'diterima');
        $this->makePendaftar($tahunA, 'pending');

        $export = new PpdbDiterimaExport($tahunA->id);

        $this->assertSame([$acceptedA->id], $export->collection()->modelKeys());
    }

    public function test_export_has_only_the_ten_approved_headings_and_expected_mapping(): void
    {
        $tahunAjaran = $this->makeTahunAjaran('2026/2027', 'aktif');
        $pendaftar = $this->makePendaftar($tahunAjaran, 'diterima', 'sudah');
        $belumDaftarUlang = $this->makePendaftar($tahunAjaran, 'diterima', 'belum');
        $export = new PpdbDiterimaExport($tahunAjaran->id);

        $this->assertSame([
            'No',
            'No. Pendaftaran',
            'Nama Lengkap',
            'NIK',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Asal Sekolah',
            'Alamat',
            'Status Daftar Ulang',
        ], $export->headings());

        $this->assertSame([
            1,
            $pendaftar->no_pendaftaran,
            $pendaftar->nama,
            "'{$pendaftar->nik}",
            $pendaftar->jenis_kelamin,
            $pendaftar->tempat_lahir,
            '01-01-2018',
            $pendaftar->nama_sekolah_asal,
            $pendaftar->alamat,
            'Sudah Daftar Ulang',
        ], $export->map($pendaftar));

        $this->assertNotContains('NISN', $export->headings());
        $this->assertNotContains('Jalur', $export->headings());
        $this->assertSame(
            'Belum Daftar Ulang',
            (new PpdbDiterimaExport($tahunAjaran->id))->map($belumDaftarUlang)[9]
        );
    }

    public function test_export_endpoint_downloads_for_a_selected_year(): void
    {
        $tahunAjaran = $this->makeTahunAjaran('2026/2027', 'aktif');
        $this->makePendaftar($tahunAjaran, 'diterima');
        Excel::fake();

        $response = $this->actingAs($this->makeTu())
            ->get(route('tu.ppdb.diterima.export', [
                'tahun_ajaran_id' => $tahunAjaran->id,
            ]));

        $response->assertOk();
        Excel::assertDownloaded('data-diterima-2026-2027.xlsx');
    }

    public function test_export_requires_a_valid_selected_academic_year(): void
    {
        $this->actingAs($this->makeTu())
            ->get(route('tu.ppdb.diterima.export'))
            ->assertRedirect()
            ->assertSessionHasErrors('tahun_ajaran_id');
    }

    public function test_accepted_page_filters_by_year_and_keeps_the_filter_in_pagination_links(): void
    {
        $tahunA = $this->makeTahunAjaran('2026/2027', 'aktif');
        $tahunB = $this->makeTahunAjaran('2027/2028', 'draft');
        $acceptedA = $this->makePendaftar($tahunA, 'diterima');
        $this->makePendaftar($tahunB, 'diterima');

        $this->actingAs($this->makeTu())
            ->get(route('tu.ppdb.diterima.index', [
                'tahun_ajaran_id' => $tahunA->id,
            ]))
            ->assertOk()
            ->assertViewHas('data', function ($data) use ($acceptedA) {
                return $data->total() === 1 && $data->getCollection()->first()->is($acceptedA);
            })
            ->assertSee('tahun_ajaran_id=' . $tahunA->id, false);
    }

    private function makeTahunAjaran(string $tahun, string $status): TahunAjaran
    {
        return TahunAjaran::create([
            'tahun' => $tahun,
            'semester' => 'Ganjil',
            'status' => $status,
        ]);
    }

    private function makePendaftar(
        TahunAjaran $tahunAjaran,
        string $status,
        string $statusDaftarUlang = 'belum'
    ): PpdbPendaftar {
        return PpdbPendaftar::create([
            'no_pendaftaran' => 'PPDB-EXPORT-' . bin2hex(random_bytes(6)),
            'tanggal_pendaftaran' => now()->toDateString(),
            'status' => $status,
            'status_daftar_ulang' => $statusDaftarUlang,
            'tahun_ajaran_id' => $tahunAjaran->id,
            'nama' => 'Peserta Export PPDB',
            'jenis_kelamin' => 'L',
            'nomor_kk' => '1234567890123456',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2018-01-01',
            'agama' => 'Islam',
            'kewarganegaraan' => 'Indonesia',
            'nik' => (string) random_int(1000000000000000, 9999999999999999),
            'alamat' => 'Alamat peserta',
            'desa' => 'Desa',
            'kecamatan' => 'Kecamatan',
            'kabupaten' => 'Kabupaten',
            'provinsi' => 'Provinsi',
            'nama_sekolah_asal' => 'TK Contoh',
        ]);
    }

    private function makeTu(): User
    {
        return User::forceCreate([
            'name' => 'PPDB export test operator',
            'email' => 'ppdb-export-' . bin2hex(random_bytes(4)) . '@example.test',
            'username' => 'ppdb-export-tu-' . bin2hex(random_bytes(4)),
            'password' => 'test-password',
            'role' => 'tu',
            'is_active' => true,
            'approval_status' => 'approved',
            'approved_at' => now(),
        ]);
    }
}
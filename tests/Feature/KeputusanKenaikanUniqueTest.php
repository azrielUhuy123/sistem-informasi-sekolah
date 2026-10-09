<?php

namespace Tests\Feature;

use App\Models\Angkatan;
use App\Models\DataKepegawaian;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\KelasGrup;
use App\Models\KelasMaster;
use App\Models\KeputusanKenaikan;
use App\Models\PenempatanSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class KeputusanKenaikanUniqueTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware();
        TahunAjaran::create([
            'tahun' => '2026/2027',
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);
    }

    public function test_one_student_can_save_one_decision_and_resave_updates_the_same_record(): void
    {
        $fixture = $this->makeWriterFixture();
        $url = route('guru.kenaikan.store');

        $this->actingAs($fixture['user'])
            ->post($url, ['keputusan' => [
                $fixture['siswa']->id => ['status' => 'naik', 'catatan' => 'Pertama'],
            ]])
            ->assertRedirect();

        $decision = KeputusanKenaikan::where('siswa_id', $fixture['siswa']->id)
            ->where('tahun_ajaran_id', $fixture['tahun']->id)
            ->firstOrFail();

        $this->assertSame('naik', $decision->status);
        $this->assertSame('draft', $decision->approval_status);

        $this->actingAs($fixture['user'])
            ->post($url, ['keputusan' => [
                $fixture['siswa']->id => ['status' => 'tinggal', 'catatan' => 'Diperbarui'],
            ]])
            ->assertRedirect();

        $this->assertSame(1, KeputusanKenaikan::where('siswa_id', $fixture['siswa']->id)
            ->where('tahun_ajaran_id', $fixture['tahun']->id)
            ->count());
        $this->assertSame($decision->id, KeputusanKenaikan::where('siswa_id', $fixture['siswa']->id)
            ->where('tahun_ajaran_id', $fixture['tahun']->id)
            ->value('id'));
        $this->assertDatabaseHas('keputusan_kenaikan', [
            'id' => $decision->id,
            'kelas_id' => $fixture['kelas']->id,
            'status' => 'tinggal',
            'approval_status' => 'draft',
            'catatan' => 'Diperbarui',
        ]);
    }

    public function test_store_rejects_student_without_an_active_placement(): void
    {
        $fixture = $this->makeWriterFixture();
        PenempatanSiswa::where('siswa_id', $fixture['siswa']->id)
            ->where('tahun_ajaran_id', $fixture['tahun']->id)
            ->delete();

        $this->actingAs($fixture['user'])
            ->from('/guru/kenaikan-kelas')
            ->post(route('guru.kenaikan.store'), ['keputusan' => [
                $fixture['siswa']->id => ['status' => 'naik', 'catatan' => null],
            ]])
            ->assertRedirect('/guru/kenaikan-kelas')
            ->assertSessionHasErrors('keputusan');

        $this->assertSame(0, KeputusanKenaikan::where('siswa_id', $fixture['siswa']->id)
            ->where('tahun_ajaran_id', $fixture['tahun']->id)
            ->count());
    }

    public function test_store_rejects_active_placement_in_another_class(): void
    {
        $fixture = $this->makeWriterFixture();
        $otherClass = Kelas::create([
            'kelas_grup_id' => $fixture['kelas']->kelas_grup_id,
            'nama_kelas' => 'IB',
        ]);
        PenempatanSiswa::where('siswa_id', $fixture['siswa']->id)
            ->where('tahun_ajaran_id', $fixture['tahun']->id)
            ->update(['kelas_id' => $otherClass->id]);

        $this->actingAs($fixture['user'])
            ->from('/guru/kenaikan-kelas')
            ->post(route('guru.kenaikan.store'), ['keputusan' => [
                $fixture['siswa']->id => ['status' => 'naik', 'catatan' => null],
            ]])
            ->assertRedirect('/guru/kenaikan-kelas')
            ->assertSessionHasErrors('keputusan');

        $this->assertSame(0, KeputusanKenaikan::where('siswa_id', $fixture['siswa']->id)
            ->where('tahun_ajaran_id', $fixture['tahun']->id)
            ->count());
    }

    public function test_store_keeps_approved_decision_unchanged_without_active_placement(): void
    {
        $fixture = $this->makeWriterFixture();
        PenempatanSiswa::where('siswa_id', $fixture['siswa']->id)
            ->where('tahun_ajaran_id', $fixture['tahun']->id)
            ->delete();
        $decision = KeputusanKenaikan::create([
            'siswa_id' => $fixture['siswa']->id,
            'kelas_id' => $fixture['kelas']->id,
            'guru_id' => $fixture['guru']->id,
            'tahun_ajaran_id' => $fixture['tahun']->id,
            'status' => 'naik',
            'approval_status' => 'approved',
        ]);

        $this->actingAs($fixture['user'])
            ->post(route('guru.kenaikan.store'), ['keputusan' => [
                $fixture['siswa']->id => ['status' => 'tinggal', 'catatan' => 'Tidak boleh mengubah final'],
            ]])
            ->assertRedirect();

        $this->assertSame('approved', $decision->fresh()->approval_status);
        $this->assertSame('naik', $decision->fresh()->status);
    }

    public function test_submit_rejects_mismatch_without_partially_submitting_batch(): void
    {
        $fixture = $this->makeWriterFixture();
        $superAdmin = $this->makeNotificationUser('superadmin');
        $secondStudent = Siswa::create([
            'nama' => 'Second decision test student',
            'nis' => (string) random_int(1000000, 9999999),
            'jenis_kelamin' => 'P',
            'angkatan_id' => $fixture['angkatan']->id,
        ]);
        $otherClass = Kelas::create([
            'kelas_grup_id' => $fixture['kelas']->kelas_grup_id,
            'nama_kelas' => 'IC',
        ]);

        $decisions = collect([$fixture['siswa'], $secondStudent])->map(function (Siswa $siswa) use ($fixture) {
            return KeputusanKenaikan::create([
                'siswa_id' => $siswa->id,
                'kelas_id' => $fixture['kelas']->id,
                'guru_id' => $fixture['guru']->id,
                'tahun_ajaran_id' => $fixture['tahun']->id,
                'status' => 'naik',
                'approval_status' => 'draft',
            ]);
        });

        PenempatanSiswa::create([
            'siswa_id' => $secondStudent->id,
            'kelas_id' => $otherClass->id,
            'tahun_ajaran_id' => $fixture['tahun']->id,
            'tanggal_masuk' => now()->toDateString(),
            'tanggal_keluar' => null,
        ]);

        $this->actingAs($fixture['user'])
            ->from('/guru/kenaikan-kelas')
            ->patch(route('guru.kenaikan.submit'))
            ->assertRedirect('/guru/kenaikan-kelas')
            ->assertSessionHasErrors('keputusan');

        foreach ($decisions as $decision) {
            $this->assertSame('draft', $decision->fresh()->approval_status);
        }
        $this->assertSame(0, $superAdmin->notifications()->count());
    }

    public function test_matching_active_placement_allows_decision_submission(): void
    {
        $fixture = $this->makeWriterFixture();
        $superAdmin = $this->makeNotificationUser('superadmin');
        $otherSuperAdmin = $this->makeNotificationUser('superadmin');
        $inactiveSuperAdmin = $this->makeNotificationUser('superadmin', false);
        $admin = $this->makeNotificationUser('admin');
        $decision = KeputusanKenaikan::create([
            'siswa_id' => $fixture['siswa']->id,
            'kelas_id' => $fixture['kelas']->id,
            'guru_id' => $fixture['guru']->id,
            'tahun_ajaran_id' => $fixture['tahun']->id,
            'status' => 'naik',
            'approval_status' => 'draft',
        ]);

        $this->actingAs($fixture['user'])
            ->patch(route('guru.kenaikan.submit'))
            ->assertRedirect();

        $this->assertSame('pending', $decision->fresh()->approval_status);
        $this->assertSame(1, $superAdmin->notifications()->count());
        $this->assertSame(1, $otherSuperAdmin->notifications()->count());
        $this->assertSame(0, $inactiveSuperAdmin->notifications()->count());
        $this->assertSame(0, $admin->notifications()->count());
        $this->assertSame(0, $fixture['user']->notifications()->count());

        $notification = $superAdmin->notifications()->sole();
        $this->assertSame('Pengajuan kenaikan kelas menunggu persetujuan', $notification->data['title']);
        $this->assertSame(
            'Keputusan kenaikan kelas telah diajukan dan menunggu persetujuan SuperAdmin.',
            $notification->data['message']
        );
        $this->assertSame('akademik', $notification->data['category']);
        $this->assertSame('action_required', $notification->data['priority']);
        $this->assertSame(
            route('superadmin.monitoring-kenaikan.index'),
            $notification->data['action_url']
        );
        $this->assertSame(KeputusanKenaikan::class, $notification->data['entity_type']);
        $this->assertSame($decision->id, $notification->data['entity_id']);

        $this->actingAs($fixture['user'])
            ->patch(route('guru.kenaikan.submit'))
            ->assertRedirect();

        $this->assertSame('pending', $decision->fresh()->approval_status);
        $this->assertSame(1, $superAdmin->notifications()->count());
        $this->assertSame(1, $otherSuperAdmin->notifications()->count());
    }

    public function test_rejected_decision_resubmission_notifies_active_superadmins_once(): void
    {
        $fixture = $this->makeWriterFixture();
        $superAdmin = $this->makeNotificationUser('superadmin');
        $otherSuperAdmin = $this->makeNotificationUser('superadmin');
        $inactiveSuperAdmin = $this->makeNotificationUser('superadmin', false);
        $admin = $this->makeNotificationUser('admin');
        $decision = KeputusanKenaikan::create([
            'siswa_id' => $fixture['siswa']->id,
            'kelas_id' => $fixture['kelas']->id,
            'guru_id' => $fixture['guru']->id,
            'tahun_ajaran_id' => $fixture['tahun']->id,
            'status' => 'naik',
            'approval_status' => 'rejected',
        ]);

        $this->actingAs($fixture['user'])
            ->patch(route('guru.kenaikan.submit'))
            ->assertRedirect();

        $this->assertSame('pending', $decision->fresh()->approval_status);
        $this->assertSame(1, $superAdmin->notifications()->count());
        $this->assertSame(1, $otherSuperAdmin->notifications()->count());
        $this->assertSame(0, $inactiveSuperAdmin->notifications()->count());
        $this->assertSame(0, $admin->notifications()->count());

        $notification = $superAdmin->notifications()->sole();
        $this->assertSame('Pengajuan kenaikan kelas menunggu persetujuan', $notification->data['title']);
        $this->assertSame(
            'Keputusan kenaikan kelas telah diajukan dan menunggu persetujuan SuperAdmin.',
            $notification->data['message']
        );
        $this->assertSame('akademik', $notification->data['category']);
        $this->assertSame('action_required', $notification->data['priority']);
        $this->assertSame(
            route('superadmin.monitoring-kenaikan.index'),
            $notification->data['action_url']
        );
        $this->assertSame(KeputusanKenaikan::class, $notification->data['entity_type']);
        $this->assertSame($decision->id, $notification->data['entity_id']);

        $this->actingAs($fixture['user'])
            ->patch(route('guru.kenaikan.submit'))
            ->assertRedirect();

        $this->assertSame('pending', $decision->fresh()->approval_status);
        $this->assertSame(1, $superAdmin->notifications()->count());
        $this->assertSame(1, $otherSuperAdmin->notifications()->count());
    }

    public function test_mixed_draft_and_rejected_submission_sends_one_notification_per_superadmin(): void
    {
        $fixture = $this->makeWriterFixture();
        $superAdmin = $this->makeNotificationUser('superadmin');
        $rejectedStudent = Siswa::create([
            'nama' => 'Rejected decision test student',
            'nis' => (string) random_int(1000000, 9999999),
            'jenis_kelamin' => 'P',
            'angkatan_id' => $fixture['angkatan']->id,
        ]);
        PenempatanSiswa::create([
            'siswa_id' => $rejectedStudent->id,
            'kelas_id' => $fixture['kelas']->id,
            'tahun_ajaran_id' => $fixture['tahun']->id,
            'tanggal_masuk' => now()->toDateString(),
            'tanggal_keluar' => null,
        ]);
        $draft = KeputusanKenaikan::create([
            'siswa_id' => $fixture['siswa']->id,
            'kelas_id' => $fixture['kelas']->id,
            'guru_id' => $fixture['guru']->id,
            'tahun_ajaran_id' => $fixture['tahun']->id,
            'status' => 'naik',
            'approval_status' => 'draft',
        ]);
        $rejected = KeputusanKenaikan::create([
            'siswa_id' => $rejectedStudent->id,
            'kelas_id' => $fixture['kelas']->id,
            'guru_id' => $fixture['guru']->id,
            'tahun_ajaran_id' => $fixture['tahun']->id,
            'status' => 'tinggal',
            'approval_status' => 'rejected',
        ]);

        $this->actingAs($fixture['user'])
            ->patch(route('guru.kenaikan.submit'))
            ->assertRedirect();

        $this->assertSame('pending', $draft->fresh()->approval_status);
        $this->assertSame('pending', $rejected->fresh()->approval_status);
        $this->assertSame(1, $superAdmin->notifications()->count());
    }

    public function test_approved_decision_does_not_block_submission_of_valid_draft(): void
    {
        $fixture = $this->makeWriterFixture();
        $draft = KeputusanKenaikan::create([
            'siswa_id' => $fixture['siswa']->id,
            'kelas_id' => $fixture['kelas']->id,
            'guru_id' => $fixture['guru']->id,
            'tahun_ajaran_id' => $fixture['tahun']->id,
            'status' => 'naik',
            'approval_status' => 'draft',
        ]);
        $approvedStudent = Siswa::create([
            'nama' => 'Previously approved student',
            'nis' => (string) random_int(1000000, 9999999),
            'jenis_kelamin' => 'P',
            'angkatan_id' => $fixture['angkatan']->id,
        ]);
        $approved = KeputusanKenaikan::create([
            'siswa_id' => $approvedStudent->id,
            'kelas_id' => $fixture['kelas']->id,
            'guru_id' => $fixture['guru']->id,
            'tahun_ajaran_id' => $fixture['tahun']->id,
            'status' => 'naik',
            'approval_status' => 'approved',
        ]);

        $this->actingAs($fixture['user'])
            ->patch(route('guru.kenaikan.submit'))
            ->assertRedirect();

        $this->assertSame('pending', $draft->fresh()->approval_status);
        $this->assertSame('approved', $approved->fresh()->approval_status);
    }

    public function test_database_unique_key_rejects_second_class_for_same_student_period(): void
    {
        $fixture = $this->makeWriterFixture();
        $secondClass = Kelas::create([
            'kelas_grup_id' => $fixture['kelas']->kelas_grup_id,
            'nama_kelas' => 'IB',
        ]);

        KeputusanKenaikan::create([
            'siswa_id' => $fixture['siswa']->id,
            'kelas_id' => $fixture['kelas']->id,
            'guru_id' => $fixture['guru']->id,
            'tahun_ajaran_id' => $fixture['tahun']->id,
            'status' => 'naik',
            'approval_status' => 'draft',
        ]);

        try {
            KeputusanKenaikan::create([
                'siswa_id' => $fixture['siswa']->id,
                'kelas_id' => $secondClass->id,
                'guru_id' => $fixture['guru']->id,
                'tahun_ajaran_id' => $fixture['tahun']->id,
                'status' => 'tinggal',
                'approval_status' => 'draft',
            ]);
            $this->fail('The unique business key should reject the second decision.');
        } catch (QueryException $exception) {
            $this->assertSame(1062, (int) ($exception->errorInfo[1] ?? 0));
            $this->assertStringContainsString(
                'keputusan_kenaikan_siswa_tahun_unique',
                $exception->getMessage()
            );
        }

        $this->assertSame(1, KeputusanKenaikan::where('siswa_id', $fixture['siswa']->id)
            ->where('tahun_ajaran_id', $fixture['tahun']->id)
            ->count());
    }

    public function test_writer_handles_a_concurrent_duplicate_insert_without_generic_error(): void
    {
        $fixture = $this->makeWriterFixture();
        $raceInserted = false;
        $siswaId = $fixture['siswa']->id;
        $tahunId = $fixture['tahun']->id;
        $guruId = $fixture['guru']->id;
        $kelasId = $fixture['kelas']->id;

        DB::listen(function (QueryExecuted $query) use (
            &$raceInserted,
            $siswaId,
            $tahunId,
            $guruId,
            $kelasId
        ): void {
            if (
                $raceInserted ||
                !str_starts_with(strtolower($query->sql), 'select') ||
                !str_contains(strtolower($query->sql), 'from `keputusan_kenaikan`') ||
                !in_array($siswaId, array_map('intval', $query->bindings), true) ||
                !in_array($tahunId, array_map('intval', $query->bindings), true)
            ) {
                return;
            }

            $raceInserted = true;

            DB::table('keputusan_kenaikan')->insert([
                'siswa_id' => $siswaId,
                'kelas_id' => $kelasId,
                'guru_id' => $guruId,
                'tahun_ajaran_id' => $tahunId,
                'status' => 'tinggal',
                'approval_status' => 'draft',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        $response = $this->actingAs($fixture['user'])
            ->post(route('guru.kenaikan.store'), ['keputusan' => [
                $siswaId => ['status' => 'naik', 'catatan' => 'Request pemenang'],
            ]]);

        $response->assertRedirect();
        $this->assertTrue($raceInserted, 'The test must inject a competing insert after firstOrNew lookup.');
        $this->assertSame(1, KeputusanKenaikan::where('siswa_id', $siswaId)
            ->where('tahun_ajaran_id', $tahunId)
            ->count());
        $this->assertDatabaseHas('keputusan_kenaikan', [
            'siswa_id' => $siswaId,
            'tahun_ajaran_id' => $tahunId,
            'status' => 'naik',
            'approval_status' => 'draft',
            'catatan' => 'Request pemenang',
        ]);
    }

    private function makeWriterFixture(): array
    {
        $tahun = TahunAjaran::where('status', 'aktif')->firstOrFail();
        $cohortYear = random_int(2090, 2100);
        $angkatan = Angkatan::create(['tahun' => $cohortYear]);
        $master = KelasMaster::firstOrCreate(['tingkat' => 'I']);
        $group = KelasGrup::create([
            'angkatan_id' => $angkatan->id,
            'kelas_master_id' => $master->id,
        ]);
        $kelas = Kelas::create([
            'kelas_grup_id' => $group->id,
            'nama_kelas' => 'IA',
        ]);
        $siswa = Siswa::create([
            'nama' => 'Decision unique test',
            'nis' => (string) random_int(1000000, 9999999),
            'jenis_kelamin' => 'L',
            'angkatan_id' => $angkatan->id,
        ]);
        PenempatanSiswa::create([
            'siswa_id' => $siswa->id,
            'kelas_id' => $kelas->id,
            'tahun_ajaran_id' => $tahun->id,
            'tanggal_masuk' => now()->toDateString(),
            'tanggal_keluar' => null,
        ]);
        $pegawai = DataKepegawaian::create(['nama' => 'Guru decision test']);
        $guru = Guru::create(['data_kepegawaian_id' => $pegawai->id]);
        $user = User::create([
            'name' => 'Guru decision test',
            'email' => 'guru-' . random_int(100000, 999999) . '@example.test',
            'pegawai_id' => $pegawai->id,
            'username' => 'guru-' . random_int(100000, 999999),
            'password' => 'test-password',
            'role' => 'guru',
            'is_active' => true,
            'approval_status' => 'approved',
            'approved_at' => now(),
        ]);

        WaliKelas::create([
            'guru_id' => $guru->id,
            'kelas_id' => $kelas->id,
            'tahun_ajaran_id' => $tahun->id,
        ]);

        return compact('angkatan', 'guru', 'kelas', 'siswa', 'tahun', 'user');
    }

    private function makeNotificationUser(string $role, bool $active = true): User
    {
        $unique = uniqid();

        return User::create([
            'name' => ucfirst($role).' kenaikan notification test',
            'email' => $role.'-'.$unique.'@example.test',
            'username' => $role.'-'.$unique,
            'password' => 'test-password',
            'role' => $role,
            'is_active' => $active,
            'approval_status' => 'approved',
            'approved_at' => now(),
        ]);
    }
}
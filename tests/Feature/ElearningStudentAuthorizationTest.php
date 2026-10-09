<?php

namespace Tests\Feature;

use App\Models\Angkatan;
use App\Models\DataKepegawaian;
use App\Models\Elearning;
use App\Models\ElearningKomentar;
use App\Models\ElearningKomentarReply;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\KelasGrup;
use App\Models\KelasMaster;
use App\Models\MataPelajaran;
use App\Models\PenempatanSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElearningStudentAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_comment_on_forum_in_their_active_class(): void
    {
        $world = $this->makeTwoClassWorld();

        $this->actingAs($world['studentA'])
            ->post(route('siswa.e-learning.komentar.store', $world['forumA']->id), [
                'isi' => 'Comment for class A',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('elearning_komentar', [
            'elearning_id' => $world['forumA']->id,
            'user_id' => $world['studentA']->id,
            'isi' => 'Comment for class A',
        ]);
    }

    public function test_student_cannot_comment_on_forum_in_another_class(): void
    {
        $world = $this->makeTwoClassWorld();

        $this->actingAs($world['studentA'])
            ->post(route('siswa.e-learning.komentar.store', $world['forumB']->id), [
                'isi' => 'Cross-class comment',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('elearning_komentar', [
            'elearning_id' => $world['forumB']->id,
            'user_id' => $world['studentA']->id,
            'isi' => 'Cross-class comment',
        ]);
    }

    public function test_student_can_comment_on_forum_in_their_own_other_class(): void
    {
        $world = $this->makeTwoClassWorld();

        $this->actingAs($world['studentB'])
            ->post(route('siswa.e-learning.komentar.store', $world['forumB']->id), [
                'isi' => 'Comment for class B',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('elearning_komentar', [
            'elearning_id' => $world['forumB']->id,
            'user_id' => $world['studentB']->id,
            'isi' => 'Comment for class B',
        ]);
    }

    public function test_student_can_reply_to_comment_in_their_active_class(): void
    {
        $world = $this->makeTwoClassWorld();
        $comment = $this->makeComment($world['forumA'], $world['studentB']);

        $this->actingAs($world['studentA'])
            ->post(route('siswa.e-learning.reply.store', $comment->id), [
                'isi' => 'Reply in class A',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('elearning_komentar_replies', [
            'komentar_id' => $comment->id,
            'user_id' => $world['studentA']->id,
            'isi' => 'Reply in class A',
        ]);
    }

    public function test_student_cannot_reply_to_comment_in_another_class(): void
    {
        $world = $this->makeTwoClassWorld();
        $comment = $this->makeComment($world['forumB'], $world['studentB']);

        $this->actingAs($world['studentA'])
            ->post(route('siswa.e-learning.reply.store', $comment->id), [
                'isi' => 'Cross-class reply',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('elearning_komentar_replies', [
            'komentar_id' => $comment->id,
            'user_id' => $world['studentA']->id,
            'isi' => 'Cross-class reply',
        ]);
    }

    public function test_student_can_reply_to_comment_in_their_own_other_class(): void
    {
        $world = $this->makeTwoClassWorld();
        $comment = $this->makeComment($world['forumB'], $world['studentB']);

        $this->actingAs($world['studentB'])
            ->post(route('siswa.e-learning.reply.store', $comment->id), [
                'isi' => 'Reply in class B',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('elearning_komentar_replies', [
            'komentar_id' => $comment->id,
            'user_id' => $world['studentB']->id,
            'isi' => 'Reply in class B',
        ]);
    }

    private function makeTwoClassWorld(): array
    {
        $tahunAjaran = TahunAjaran::create([
            'tahun' => '2026/2027',
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);
        $angkatan = Angkatan::create(['tahun' => 2026]);
        $kelasMaster = KelasMaster::create(['tingkat' => 'I']);
        $kelasGrup = KelasGrup::create([
            'angkatan_id' => $angkatan->id,
            'kelas_master_id' => $kelasMaster->id,
        ]);
        $kelasA = Kelas::create([
            'kelas_grup_id' => $kelasGrup->id,
            'nama_kelas' => 'IA',
        ]);
        $kelasB = Kelas::create([
            'kelas_grup_id' => $kelasGrup->id,
            'nama_kelas' => 'IB',
        ]);

        $pegawai = DataKepegawaian::create([
            'nama' => 'E-learning test teacher',
            'nip' => 'TEST-'.bin2hex(random_bytes(4)),
        ]);
        $guru = Guru::create(['data_kepegawaian_id' => $pegawai->id]);
        $mapel = MataPelajaran::create(['nama_mapel' => 'Matematika']);

        $forumA = Elearning::create([
            'guru_id' => $guru->id,
            'kelas_id' => $kelasA->id,
            'mapel_id' => $mapel->id,
            'judul' => 'Forum class A',
        ]);
        $forumB = Elearning::create([
            'guru_id' => $guru->id,
            'kelas_id' => $kelasB->id,
            'mapel_id' => $mapel->id,
            'judul' => 'Forum class B',
        ]);

        $studentA = $this->makeStudent('student-a', $angkatan, $kelasA, $tahunAjaran);
        $studentB = $this->makeStudent('student-b', $angkatan, $kelasB, $tahunAjaran);

        return compact('studentA', 'studentB', 'forumA', 'forumB');
    }

    private function makeStudent(
        string $identifier,
        Angkatan $angkatan,
        Kelas $kelas,
        TahunAjaran $tahunAjaran
    ): User {
        $user = User::factory()->create([
            'role' => 'siswa',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $siswa = Siswa::create([
            'user_id' => $user->id,
            'angkatan_id' => $angkatan->id,
            'nama' => $identifier,
            'nis' => (string) random_int(10000000, 99999999),
            'jenis_kelamin' => 'L',
        ]);
        $user->forceFill(['siswa_id' => $siswa->id])->save();

        PenempatanSiswa::create([
            'siswa_id' => $siswa->id,
            'kelas_id' => $kelas->id,
            'tahun_ajaran_id' => $tahunAjaran->id,
            'tanggal_masuk' => now()->toDateString(),
        ]);

        return $user->fresh();
    }

    private function makeComment(Elearning $forum, User $author): ElearningKomentar
    {
        return ElearningKomentar::create([
            'elearning_id' => $forum->id,
            'user_id' => $author->id,
            'isi' => 'Existing comment',
        ]);
    }
}
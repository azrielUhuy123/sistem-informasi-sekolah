<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\Ekskul;
use App\Models\Kelas;
use App\Models\KeputusanKenaikan;
use App\Models\PpdbPendaftar;
use App\Models\User;
use App\Notifications\SinergiNotification;
use Illuminate\Support\Collection;

class BusinessNotificationService
{
    public function notifyPendingAccount(User $pendingUser): void
    {
        $superAdmins = User::query()
            ->where('role', UserRole::SUPERADMIN)
            ->where('is_active', true)
            ->get();

        foreach ($superAdmins as $superAdmin) {
            $superAdmin->notify(new SinergiNotification(
                title: 'Akun menunggu persetujuan',
                message: "Akun {$pendingUser->name} menunggu persetujuan.",
                category: 'user',
                priority: 'action_required',
                actionUrl: route('superadmin.users.pending'),
                actionLabel: 'Tinjau akun',
                entityType: User::class,
                entityId: $pendingUser->getKey(),
            ));
        }
    }

    public function notifyAccountApproved(User $user): void
    {
        $user->notify(new SinergiNotification(
            title: 'Akun disetujui',
            message: 'Akun Anda telah disetujui dan aktif.',
            category: 'user',
            priority: 'success',
            actionUrl: route('profile.edit'),
            actionLabel: 'Lihat profil',
            entityType: User::class,
            entityId: $user->getKey(),
        ));
    }

    public function notifyScheduleSubmitted(Kelas $kelas, string $academicYear, int $slotCount): void
    {
        foreach ($this->activeSuperAdmins() as $superAdmin) {
            $superAdmin->notify(new SinergiNotification(
                title: 'Pengajuan jadwal menunggu persetujuan',
                message: "Jadwal kelas {$kelas->nama_kelas} Tahun Ajaran {$academicYear} menunggu persetujuan ({$slotCount} slot).",
                category: 'jadwal',
                priority: 'action_required',
                actionUrl: route('superadmin.monitoring-jadwal.show', $kelas),
                actionLabel: 'Tinjau jadwal',
                entityType: Kelas::class,
                entityId: $kelas->getKey(),
            ));
        }
    }

    public function notifyExtracurricularScheduleSubmitted(Ekskul $ekskul): void
    {
        foreach ($this->activeSuperAdmins() as $superAdmin) {
            $superAdmin->notify(new SinergiNotification(
                title: 'Pengajuan jadwal ekstrakurikuler menunggu pemeriksaan',
                message: "Pengajuan jadwal ekstrakurikuler {$ekskul->nama} menunggu pemeriksaan SuperAdmin.",
                category: 'jadwal',
                priority: 'action_required',
                actionUrl: route('superadmin.monitoring-ekskul.show', $ekskul),
                actionLabel: 'Lihat Pengajuan',
                entityType: Ekskul::class,
                entityId: $ekskul->getKey(),
            ));
        }
    }

    public function notifyPromotionSubmitted(KeputusanKenaikan $keputusan): void
    {
        foreach ($this->activeSuperAdmins() as $superAdmin) {
            $superAdmin->notify(new SinergiNotification(
                title: 'Pengajuan kenaikan kelas menunggu persetujuan',
                message: 'Keputusan kenaikan kelas telah diajukan dan menunggu persetujuan SuperAdmin.',
                category: 'akademik',
                priority: 'action_required',
                actionUrl: route('superadmin.monitoring-kenaikan.index'),
                actionLabel: 'Tinjau pengajuan',
                entityType: KeputusanKenaikan::class,
                entityId: $keputusan->getKey(),
            ));
        }
    }

    public function notifyScheduleApproved(Kelas $kelas, string $academicYear, Collection $teacherUsers): void
    {
        foreach ($teacherUsers->unique('id') as $teacherUser) {
            $teacherUser->notify(new SinergiNotification(
                title: 'Pengajuan jadwal disetujui',
                message: "Pengajuan jadwal kelas {$kelas->nama_kelas} Tahun Ajaran {$academicYear} telah disetujui.",
                category: 'jadwal',
                priority: 'success',
                actionUrl: route('guru.status-validasi.index'),
                actionLabel: 'Lihat status jadwal',
                entityType: Kelas::class,
                entityId: $kelas->getKey(),
            ));
        }
    }

    public function notifyScheduleRejected(Kelas $kelas, User $teacherUser, int $scheduleId, string $rejectionDetails): void
    {
        $teacherUser->notify(new SinergiNotification(
            title: 'Pengajuan jadwal ditolak',
            message: "Pengajuan jadwal kelas {$kelas->nama_kelas} ditolak.\n{$rejectionDetails}",
            category: 'jadwal',
            priority: 'warning',
            actionUrl: route('guru.status-validasi.index'),
            actionLabel: 'Lihat catatan penolakan',
            entityType: 'PengajuanJadwal',
            entityId: $scheduleId,
        ));
    }

    public function notifyPendingPpdbRegistration(PpdbPendaftar $pendaftar): void
    {
        foreach ($this->activeApprovedTuUsers() as $tuUser) {
            $tuUser->notify(new SinergiNotification(
                title: 'Pendaftaran PPDB menunggu verifikasi',
                message: "Pendaftar {$pendaftar->no_pendaftaran} ({$pendaftar->nama}) menunggu verifikasi.",
                category: 'ppdb',
                priority: 'action_required',
                actionUrl: route('tu.ppdb.verifikasi.index'),
                actionLabel: 'Verifikasi pendaftar',
                entityType: PpdbPendaftar::class,
                entityId: $pendaftar->getKey(),
            ));
        }
    }

    public function notifyPpdbDaftarUlangSubmitted(PpdbPendaftar $pendaftar): void
    {
        foreach ($this->activeApprovedTuUsers() as $tuUser) {
            $tuUser->notify(new SinergiNotification(
                title: 'Daftar ulang PPDB perlu dikonfirmasi',
                message: "Pendaftar {$pendaftar->no_pendaftaran} ({$pendaftar->nama}) telah menyelesaikan daftar ulang.",
                category: 'ppdb',
                priority: 'action_required',
                actionUrl: route('tu.ppdb.daftar-ulang.index'),
                actionLabel: 'Konfirmasi daftar ulang',
                entityType: PpdbPendaftar::class,
                entityId: $pendaftar->getKey(),
            ));
        }
    }

    private function activeApprovedTuUsers(): \Illuminate\Database\Eloquent\Collection
    {
        return User::query()
            ->where('role', UserRole::TU)
            ->where('is_active', true)
            ->where('approval_status', 'approved')
            ->get();
    }

    private function activeSuperAdmins(): Collection
    {
        return User::query()
            ->where('role', UserRole::SUPERADMIN)
            ->where('is_active', true)
            ->get();
    }
}
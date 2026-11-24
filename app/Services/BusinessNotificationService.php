<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\Ekskul;
use App\Models\Kelas;
use App\Models\KeputusanKenaikan;
use App\Models\PpdbPendaftar;
use App\Models\PegawaiChangeItem;
use App\Models\PegawaiChangeRequest;
use App\Models\User;
use App\Notifications\SinergiNotification;
use Illuminate\Support\Collection;

class BusinessNotificationService
{
    public function notifyPegawaiChangeSubmitted(PegawaiChangeRequest $changeRequest): void
    {
        $changeRequest->loadMissing(['pegawai', 'items']);
        $operators = User::query()
            ->where('role', UserRole::GURU)
            ->where('approval_status', 'approved')
            ->where('is_active', true)
            ->whereHas('pegawai.penugasans', fn ($query) => $query
                ->where('status', 'active')
                ->whereHas('items', fn ($itemQuery) => $itemQuery
                    ->where('type', 'operator')
                    ->whereHas('menus', fn ($menuQuery) => $menuQuery
                        ->where('menu_key', 'operator.employees'))))
            ->where('pegawai_id', '!=', $changeRequest->data_kepegawaian_id)
            ->get();

        foreach ($operators as $operator) {
            $operator->notify(new SinergiNotification(
                title: 'Pengajuan perubahan data pegawai',
                message: "{$changeRequest->pegawai->nama} mengajukan {$changeRequest->items->count()} perubahan data pegawai.",
                category: 'user',
                priority: 'action_required',
                actionUrl: route('tu.pegawai-changes.show', $changeRequest),
                actionLabel: 'Tinjau pengajuan',
                entityType: PegawaiChangeRequest::class,
                entityId: $changeRequest->getKey(),
            ));
        }
    }

    public function notifyPegawaiChangeDecision(PegawaiChangeItem $item): void
    {
        $item->loadMissing(['changeRequest.submittedBy']);
        $user = $item->changeRequest->submittedBy;
        if (! $user) {
            return;
        }

        $label = config('employee_profile_changes.fields.'.$item->field.'.label', $item->field);
        $approved = $item->status === 'approved';

        $user->notify(new SinergiNotification(
            title: $approved ? 'Perubahan data pegawai disetujui' : 'Perubahan data pegawai ditolak',
            message: $approved
                ? "Perubahan {$label} telah disetujui dan diterapkan."
                : "Perubahan {$label} ditolak. Alasan: {$item->rejection_reason}",
            category: 'user',
            priority: $approved ? 'success' : 'warning',
            actionUrl: route('guru.pegawai-changes.show', $item->changeRequest),
            actionLabel: 'Lihat status pengajuan',
            entityType: PegawaiChangeItem::class,
            entityId: $item->getKey(),
        ));
    }

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
        foreach ($this->activeApprovedUsersWithPpdbMenu('ppdb.verification') as $user) {
            $user->notify(new SinergiNotification(
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
        foreach ($this->activeApprovedUsersWithPpdbMenu('ppdb.reenrollment') as $user) {
            $user->notify(new SinergiNotification(
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

    private function activeApprovedUsersWithPpdbMenu(string $menuKey): \Illuminate\Database\Eloquent\Collection
    {
        return User::query()
            ->where('role', UserRole::GURU)
            ->where('is_active', true)
            ->where('approval_status', 'approved')
            ->whereHas('pegawai.penugasans', fn ($query) => $query
                ->where('status', 'active')
                ->whereHas('items.menus', fn ($menuQuery) => $menuQuery
                    ->where('menu_key', $menuKey)))
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
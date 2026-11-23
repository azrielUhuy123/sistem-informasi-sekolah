<?php

namespace Tests;

use App\Models\DataKepegawaian;
use App\Models\Guru;
use App\Models\Penugasan;
use App\Models\PenugasanItem;
use App\Models\PenugasanMenu;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function createAssignedTeacher(string $type, array $menuKeys, array $attributes = []): User
    {
        $unique = uniqid();
        $employee = DataKepegawaian::create([
            'nama' => 'Assigned Teacher '.$unique,
        ]);
        Guru::create(['data_kepegawaian_id' => $employee->id]);

        $approvalStatus = $attributes['approval_status'] ?? 'approved';
        $user = User::create(array_merge([
            'name' => 'Assigned Teacher '.$unique,
            'email' => 'assigned-teacher-'.$unique.'@example.test',
            'username' => 'assigned-teacher-'.$unique,
            'password' => 'test-password',
            'role' => 'guru',
            'pegawai_id' => $employee->id,
            'approval_status' => $approvalStatus,
            'approved_at' => $approvalStatus === 'approved' ? now() : null,
            'is_active' => true,
        ], $attributes));

        $assignment = Penugasan::create([
            'data_kepegawaian_id' => $employee->id,
            'status' => 'active',
            'published_at' => now(),
        ]);
        $item = PenugasanItem::create([
            'penugasan_id' => $assignment->id,
            'type' => $type,
        ]);
        foreach (array_unique($menuKeys) as $menuKey) {
            PenugasanMenu::create([
                'penugasan_item_id' => $item->id,
                'menu_key' => $menuKey,
            ]);
        }

        return $user;
    }

    protected function assignTeacherMenus(User $user, string $type, array $menuKeys): void
    {
        if (! $user->pegawai_id) {
            $employee = DataKepegawaian::create([
                'nama' => $user->name ?? 'Assigned Teacher',
            ]);
            $user->pegawai_id = $employee->id;
        }

        $user->role = 'guru';
        $user->save();
        Guru::firstOrCreate(['data_kepegawaian_id' => $user->pegawai_id]);

        $assignment = Penugasan::create([
            'data_kepegawaian_id' => $user->pegawai_id,
            'status' => 'active',
            'published_at' => now(),
        ]);
        $item = PenugasanItem::create([
            'penugasan_id' => $assignment->id,
            'type' => $type,
        ]);
        foreach (array_unique($menuKeys) as $menuKey) {
            PenugasanMenu::create([
                'penugasan_item_id' => $item->id,
                'menu_key' => $menuKey,
            ]);
        }
    }
}

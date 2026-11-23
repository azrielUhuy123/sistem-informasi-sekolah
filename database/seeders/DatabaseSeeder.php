<?php

namespace Database\Seeders;

use App\Models\DataKepegawaian;
use App\Models\Guru;
use App\Models\Penugasan;
use App\Models\PenugasanItem;
use App\Models\PenugasanMenu;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'approval_status' => 'approved',
            'role' => 'admin',
            'approved_at' => now(),
        ]);

        User::create([
            'name' => 'Kepala Sekolah',
            'email' => 'kepsek@example.com',
            'password' => Hash::make('kepsek123'),
            'approval_status' => 'approved',
            'role' => 'superadmin',
            'approved_at' => now(),
        ]);

        $this->seedTeacher('Operator Sekolah', 'tu@example.com', 'tu123', '9000000000000011', 'operator', 'operator.dashboard');
        $this->seedTeacher('Guru', 'guru@example.com', 'guru123', '9000000000000012', 'guru', 'guru.dashboard');

        $this->call(KelasMasterSeeder::class);

        $this->call(HariJamSeeder::class);

        $this->call(MataPelajaranSeeder::class);
    }

    private function seedTeacher(
        string $name,
        string $email,
        string $password,
        string $nip,
        string $assignmentType,
        string $menuKey
    ): void {
        $employee = DataKepegawaian::create([
            'nama' => $name,
            'nip' => $nip,
            'jabatan' => $assignmentType === 'operator' ? 'Operator Sekolah' : 'Guru',
            'approval_status' => 'approved',
        ]);
        Guru::create(['data_kepegawaian_id' => $employee->id]);
        User::create([
            'name' => $name,
            'email' => $email,
            'pegawai_id' => $employee->id,
            'password' => Hash::make($password),
            'approval_status' => 'approved',
            'approved_at' => now(),
            'is_active' => true,
            'role' => 'guru',
        ]);

        $assignment = Penugasan::create([
            'data_kepegawaian_id' => $employee->id,
            'assigned_by' => User::where('role', 'superadmin')->value('id'),
            'status' => 'active',
            'published_at' => now(),
        ]);
        $item = PenugasanItem::create([
            'penugasan_id' => $assignment->id,
            'type' => $assignmentType,
        ]);
        PenugasanMenu::create([
            'penugasan_item_id' => $item->id,
            'menu_key' => $menuKey,
        ]);
    }

}

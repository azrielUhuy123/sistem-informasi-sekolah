<?php

namespace Database\Seeders;

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

        User::create([
            'name' => 'Tata Usaha',
            'email' => 'tu@example.com',
            'password' => Hash::make('tu123'),
            'approval_status' => 'approved',
            'role' => 'tu',
            'approved_at' => now(),
        ]);
        
        User::create([
            'name' => 'Guru',
            'email' => 'guru@example.com',
            'password' => Hash::make('guru123'),
            'role' => 'guru',
        ]);

        $this->call(KelasMasterSeeder::class);

        $this->call(HariJamSeeder::class);

        $this->call(MataPelajaranSeeder::class);
    }

}

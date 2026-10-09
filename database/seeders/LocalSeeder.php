<?php

namespace Database\Seeders;

use App\Models\DataKepegawaian;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LocalSeeder extends Seeder
{
    public function run(): void
    {
        $pegawai = DataKepegawaian::query()->updateOrCreate(
            [
                'nip' => '9000000000000001',
            ],
            [
                'nama' => 'QA PPDB Admin',
                'status_kepegawaian' => 'NON_PNS',
                'approval_status' => 'approved',
            ]
        );

        User::query()->updateOrCreate(
            [
                'username' => 'qa_admin',
            ],
            [
                'name' => 'QA PPDB Admin',
                'email' => 'qa_admin@sdnmekarjaya.test',
                'username' => 'qa_admin',
                'pegawai_id' => $pegawai->id,
                'password' => Hash::make('qa_admin_123'),
                'role' => 'tu',
                'is_active' => true,
                'approval_status' => 'approved',
                'approved_at' => now(),
            ]
        );
    }
}
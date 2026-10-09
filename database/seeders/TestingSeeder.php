<?php

namespace Database\Seeders;

use App\Models\DataKepegawaian;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class TestingSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('testing')) {
            throw new \RuntimeException(
                'TestingSeeder hanya boleh dijalankan dengan APP_ENV=testing.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Data Kepegawaian Testing
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | User Testing
        |--------------------------------------------------------------------------
        */

        $admin = User::query()->firstOrNew([
            'username' => 'qa_admin',
        ]);

        $adminValues = [
            'pegawai_id' => $pegawai->id,
            'password' => Hash::make('qa_admin_123'),
            'role' => 'tu',
            'is_active' => true,
            'approval_status' => 'approved',
            'approved_at' => now(),
        ];

        if (Schema::hasColumn('users', 'name')) {
            $adminValues['name'] = 'QA PPDB Admin';
        }

        if (Schema::hasColumn('users', 'email')) {
            $adminValues['email'] = 'qa_admin@sdnmekarjaya.test';
        }

        $admin->forceFill($adminValues)->save();
    }
}
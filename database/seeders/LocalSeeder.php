<?php

namespace Database\Seeders;

use App\Models\DataKepegawaian;
use App\Models\Guru;
use App\Models\Penugasan;
use App\Models\PenugasanItem;
use App\Models\PenugasanMenu;
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
                'nama' => 'QA Guru PPDB',
                'status_kepegawaian' => 'NON_PNS',
                'approval_status' => 'approved',
            ]
        );

        User::query()->updateOrCreate(
            [
                'username' => 'qa_admin',
            ],
            [
                'name' => 'QA Guru PPDB',
                'email' => 'qa_admin@sdnmekarjaya.test',
                'username' => 'qa_admin',
                'pegawai_id' => $pegawai->id,
                'password' => Hash::make('qa_admin_123'),
                'role' => 'guru',
                'is_active' => true,
                'approval_status' => 'approved',
                'approved_at' => now(),
            ]
        );

        Guru::firstOrCreate(['data_kepegawaian_id' => $pegawai->id]);

        $assignment = Penugasan::firstOrCreate(
            ['data_kepegawaian_id' => $pegawai->id, 'status' => 'active'],
            ['assigned_by' => null, 'published_at' => now()]
        );
        $item = PenugasanItem::firstOrCreate([
            'penugasan_id' => $assignment->id,
            'type' => 'ppdb',
        ]);
        foreach (['ppdb.dashboard', 'ppdb.applicants', 'ppdb.verification', 'ppdb.reenrollment'] as $menuKey) {
            PenugasanMenu::firstOrCreate([
                'penugasan_item_id' => $item->id,
                'menu_key' => $menuKey,
            ]);
        }
    }
}
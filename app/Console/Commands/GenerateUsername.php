<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Console\Command;

class GenerateUsername extends Command
{
    protected $signature = 'generate:username';

    protected $description = 'Generate username dari NIP/NIS';

    public function handle()
    {
        foreach (User::all() as $user) {

            $guru = Guru::where(
                'user_id',
                $user->id
            )->first();

            if ($guru) {

                $user->update([
                    'username' => $guru->nip
                ]);

                continue;
            }

            $siswa = Siswa::where(
                'user_id',
                $user->id
            )->first();

            if ($siswa) {

                $user->update([
                    'username' => $siswa->nis
                ]);

                continue;
            }
        }

        $this->info(
            'Username berhasil dibuat.'
        );
    }
}
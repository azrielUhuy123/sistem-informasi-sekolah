<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SinkronGuruPegawai extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sinkron-guru-pegawai';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $gurus = \App\Models\Guru::all();

        foreach ($gurus as $guru) {

            $pegawai =
                \App\Models\DataKepegawaian::where(
                    'nip',
                    $guru->nip
                )->first();

            if ($pegawai) {

                $guru->update([
                    'data_kepegawaian_id' =>
                        $pegawai->id
                ]);
            }
        }

        $this->info('Sinkron selesai');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_orangtua', function (Blueprint $table) {

            $table->id();

            $table->foreignId('ppdb_pendaftar_id')
                ->constrained('ppdb_pendaftar')
                ->cascadeOnDelete();

            $table->enum('jenis', [
                'ayah',
                'ibu'
            ]);

            $table->string('nama');

            $table->string('nik', 20)->nullable();

            $table->string('tempat_lahir')->nullable();

            $table->date('tanggal_lahir')->nullable();

            $table->string('pendidikan')->nullable();

            $table->string('pekerjaan')->nullable();

            $table->enum('penghasilan', [
                '<500.000',
                '500.000-999.999',
                '1.000.000-2.000.000',
                '2.000.000-4.999.999',
                '>5.000.000'
            ])->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_orangtua');
    }
};
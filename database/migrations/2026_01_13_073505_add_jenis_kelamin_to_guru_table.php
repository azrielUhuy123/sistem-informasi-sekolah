<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('guru', function (Blueprint $table) {
        $table->enum('jenis_kelamin', ['L', 'P'])->after('nip');
    });
}

public function down()
{
    Schema::table('guru', function (Blueprint $table) {
        $table->dropColumn('jenis_kelamin');
    });
}

};

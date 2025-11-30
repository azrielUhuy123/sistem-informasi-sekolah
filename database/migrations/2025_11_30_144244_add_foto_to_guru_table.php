<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('guru', function (Blueprint $table) {
        if (!Schema::hasColumn('guru', 'foto')) {
            $table->string('foto')->nullable()->after('telepon');
        }
    });
}

public function down()
{
    Schema::table('guru', function (Blueprint $table) {
        $table->dropColumn('foto');
    });
}

};

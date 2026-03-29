<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('knockout_matches', function (Blueprint $table) {
            $table->integer('table_number')->default(1)->after('match_number')->comment('Asztal száma');
        });
    }

    public function down(): void
    {
        Schema::table('knockout_matches', function (Blueprint $table) {
            $table->dropColumn('table_number');
        });
    }
};

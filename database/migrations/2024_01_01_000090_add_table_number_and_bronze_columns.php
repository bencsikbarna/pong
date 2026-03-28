<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->integer('table_number')->default(1)->after('round_id')->comment('Asztal száma');
        });

        Schema::table('knockout_matches', function (Blueprint $table) {
            $table->boolean('is_bronze')->default(false)->after('is_played')->comment('Bronz mérkőzés (3. helyért)');
        });
    }

    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn('table_number');
        });
        Schema::table('knockout_matches', function (Blueprint $table) {
            $table->dropColumn('is_bronze');
        });
    }
};

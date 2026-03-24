<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->integer('round_number');
            $table->timestamps();
        });

        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('round_id')->constrained()->onDelete('cascade');
            $table->foreignId('home_registration_id')->constrained('event_registrations')->onDelete('cascade');
            $table->foreignId('away_registration_id')->constrained('event_registrations')->onDelete('cascade');
            $table->integer('home_score')->nullable();
            $table->integer('away_score')->nullable();
            // Result: null (nem játszott), home_win, away_win, draw
            $table->string('result')->nullable();
            $table->boolean('is_played')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
        Schema::dropIfExists('rounds');
    }
};

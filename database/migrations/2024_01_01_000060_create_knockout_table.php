<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('knockout_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            // Forduló: 1=döntő, 2=elődöntő, 4=negyeddöntő, stb.
            $table->integer('round'); // hány csapat marad ennél a körben
            $table->integer('match_number'); // sorszám az adott körön belül
            $table->foreignId('home_registration_id')->nullable()->constrained('event_registrations')->onDelete('set null');
            $table->foreignId('away_registration_id')->nullable()->constrained('event_registrations')->onDelete('set null');
            $table->integer('home_score')->nullable();
            $table->integer('away_score')->nullable();
            $table->foreignId('winner_registration_id')->nullable()->constrained('event_registrations')->onDelete('set null');
            $table->boolean('is_played')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knockout_matches');
    }
};

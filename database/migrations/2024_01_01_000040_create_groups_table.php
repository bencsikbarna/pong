<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('name'); // pl. "A csoport", "B csoport"
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('group_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->foreignId('registration_id')->constrained('event_registrations')->onDelete('cascade');
            // Csoport állás
            $table->integer('points')->default(0);
            $table->integer('wins')->default(0);
            $table->integer('draws')->default(0);
            $table->integer('losses')->default(0);
            $table->integer('cups_scored')->default(0);
            $table->integer('cups_conceded')->default(0);
            $table->integer('cup_diff')->default(0);
            $table->integer('played')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_teams');
        Schema::dropIfExists('groups');
    }
};

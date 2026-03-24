<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('contact_name')->nullable();
            $table->string('contact_phone')->nullable();
            // Összesített statisztikák
            $table->integer('total_events')->default(0);
            $table->integer('total_wins')->default(0);
            $table->integer('total_losses')->default(0);
            $table->integer('total_draws')->default(0);
            $table->integer('total_cups_scored')->default(0);
            $table->integer('total_cups_conceded')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->dateTime('event_date');
            $table->dateTime('registration_deadline')->nullable();
            $table->integer('max_teams');
            $table->integer('tables_count')->default(1);
            // Status: registration_open, registration_closed, group_stage, knockout_stage, finished
            $table->string('status')->default('registration_open');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

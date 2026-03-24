<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            // Regisztrált csapat (nullable ha vendégként nevez)
            $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('set null');
            // Vendég adatok (ha nincs team_id)
            $table->string('guest_team_name')->nullable();
            $table->string('guest_contact_name')->nullable();
            $table->string('guest_contact_email')->nullable();
            $table->string('guest_contact_phone')->nullable();
            // Status: pending, confirmed, rejected
            $table->string('status')->default('confirmed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};

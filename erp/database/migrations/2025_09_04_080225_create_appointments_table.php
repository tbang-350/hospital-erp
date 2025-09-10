<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained()->nullOnDelete(); // clinician
            $table->dateTime('scheduled_at');
            $table->enum('status', ['scheduled', 'checked_in', 'completed', 'cancelled'])->default('scheduled');
            $table->string('location')->nullable();
            $table->string('reason')->nullable();
            $table->json('reminders')->nullable(); // SMS/WhatsApp metadata
            $table->timestamps();
            $table->index(['patient_id', 'scheduled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Type of notification
            $table->string('title'); // Notification title
            $table->text('message'); // Notification message
            $table->json('data')->nullable(); // Additional data
            $table->timestamp('read_at')->nullable(); // When notification was read
            $table->string('notifiable_type'); // Polymorphic relation (usually User)
            $table->unsignedBigInteger('notifiable_id'); // ID of the notifiable entity
            $table->string('related_type')->nullable(); // Type of related entity (Patient, Appointment, etc.)
            $table->unsignedBigInteger('related_id')->nullable(); // ID of related entity
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->string('action_url')->nullable(); // URL to redirect when clicked
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['notifiable_type', 'notifiable_id']);
            $table->index(['related_type', 'related_id']);
            $table->index(['type', 'read_at']);
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Émargement et assiduité (espace coachs).
 *   presence_sessions : une séance = un créneau du planning à une date, ou une séance supplémentaire (sans créneau)
 *   presence_records  : présence d'un nageur à une séance (present | absent | excuse) et heure d'arrivée
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presence_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planning_slot_id')->nullable()->constrained('planning_slots')->nullOnDelete();
            $table->foreignId('planning_group_id')->nullable()->constrained('planning_groups')->nullOnDelete();
            $table->date('date');
            $table->time('starts_at')->nullable();
            $table->time('ends_at')->nullable();
            $table->string('title')->nullable();          // séance supplémentaire : « Stage », « Rattrapage »…
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['planning_slot_id', 'date']);
            $table->index('date');
        });

        Schema::create('presence_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presence_session_id')->constrained('presence_sessions')->cascadeOnDelete();
            $table->foreignId('planning_swimmer_id')->constrained('planning_swimmers')->cascadeOnDelete();
            $table->string('status', 10);                 // present | absent | excuse
            $table->time('arrived_at')->nullable();
            $table->string('comment', 255)->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['presence_session_id', 'planning_swimmer_id']);
            $table->index(['planning_swimmer_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presence_records');
        Schema::dropIfExists('presence_sessions');
    }
};

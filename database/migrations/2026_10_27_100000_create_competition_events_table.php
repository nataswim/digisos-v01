<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Module Compétitions — programme structuré : épreuves de chaque réunion
 * (épreuve, Dames / Messieurs / Mixte, séries ou finale A / B / C, ordre).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competition_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('swim_event_id')->constrained()->restrictOnDelete();
            $table->char('gender', 1);                         // F (Dames) | M (Messieurs) | X (Mixte)
            $table->string('round', 20)->default('series');    // series | finale-c | finale-b | finale-a
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['competition_session_id', 'swim_event_id', 'gender', 'round'], 'competition_event_unique');
            $table->index(['competition_session_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_events');
    }
};

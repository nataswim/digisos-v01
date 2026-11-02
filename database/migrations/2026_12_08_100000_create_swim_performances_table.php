<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Suivi des performances : temps réalisés par les nageurs du club, en compétition du calendrier,
 * en meeting extérieur ou en contre-la-montre à l'entraînement.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('swim_performances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planning_swimmer_id')->constrained('planning_swimmers')->cascadeOnDelete();
            $table->foreignId('competition_id')->nullable()->constrained('competitions')->nullOnDelete();
            $table->string('context', 15)->default('competition'); // competition | meeting | training
            $table->string('meet_name')->nullable();       // meeting extérieur ou séance (si pas de compétition du calendrier)
            $table->string('location')->nullable();
            $table->date('performed_on');
            $table->unsignedTinyInteger('pool');           // 25 | 50
            $table->string('event', 20);                   // « 25 NL », « 100 Dos »…
            $table->string('round', 12)->default('serie'); // serie | demi | finale | finale_a | finale_b | finale_c | clm
            $table->unsignedInteger('time_cs')->nullable(); // centièmes ; vide si DSQ / abandon / forfait
            $table->string('status', 10)->default('valid'); // valid | dsq | dnf | dns
            $table->string('note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['planning_swimmer_id', 'event', 'pool', 'performed_on'], 'perf_swimmer_event');
            $table->index(['competition_id']);
            $table->index(['performed_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('swim_performances');
    }
};

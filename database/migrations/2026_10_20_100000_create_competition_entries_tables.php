<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Module Compétitions — V2 : convocations, réponses des familles, covoiturage et engagements.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Nageurs : informations utiles aux engagements
        Schema::table('planning_swimmers', function (Blueprint $table) {
            $table->char('gender', 1)->nullable()->after('birth_year');              // F | M
            $table->string('license_number', 20)->nullable()->after('gender');       // n° de licence FFN
        });

        // Compétitions : date limite de réponse et affichage public des engagés
        Schema::table('competitions', function (Blueprint $table) {
            $table->date('response_deadline')->nullable()->after('logistics_notes');
            $table->boolean('entries_visible')->default(false)->after('response_deadline');
        });

        // Référentiel des épreuves (50 NL, 100 Dos, 200 4 N…)
        Schema::create('swim_events', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();               // ex. « 100-dos »
            $table->string('stroke', 10);                       // nl | dos | brasse | pap | 4n
            $table->unsignedSmallInteger('distance');
            $table->boolean('is_relay')->default(false);
            $table->string('label', 50);                        // ex. « 100 Dos »
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Convocation d'un nageur à une compétition et réponse de sa famille
        Schema::create('competition_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->foreignId('planning_swimmer_id')->constrained('planning_swimmers')->cascadeOnDelete();
            $table->string('status', 20)->default('convoque');  // convoque | confirme | absent | forfait
            $table->boolean('needs_seat')->default(false);      // cherche une place en voiture
            $table->unsignedTinyInteger('seats_offered')->default(0);
            $table->string('family_comment', 500)->nullable();
            $table->string('staff_note', 500)->nullable();      // note interne
            $table->timestamp('notified_at')->nullable();       // e-mail de convocation envoyé
            $table->timestamp('responded_at')->nullable();
            $table->foreignId('responded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['competition_id', 'planning_swimmer_id'], 'competition_attendance_unique');
            $table->index(['competition_id', 'status']);
        });

        // Engagements : épreuves nagées par un nageur convoqué
        Schema::create('competition_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_attendance_id')->constrained()->cascadeOnDelete();
            $table->foreignId('swim_event_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('entry_time_cs')->nullable(); // temps d'engagement en centièmes
            $table->timestamps();

            $table->unique(['competition_attendance_id', 'swim_event_id'], 'competition_entry_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_entries');
        Schema::dropIfExists('competition_attendances');
        Schema::dropIfExists('swim_events');

        Schema::table('competitions', function (Blueprint $table) {
            $table->dropColumn(['response_deadline', 'entries_visible']);
        });
        Schema::table('planning_swimmers', function (Blueprint $table) {
            $table->dropColumn(['gender', 'license_number']);
        });
    }
};

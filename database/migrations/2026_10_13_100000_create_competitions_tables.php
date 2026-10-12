<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Module « Compétitions » — V1 : calendrier, logistique, encadrement, programme, documents.
 * Les engagements et résultats (V2, V3) viendront dans des tables séparées.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();

            // En-tête
            $table->string('title', 150);
            $table->string('slug', 170)->unique();
            $table->string('season', 9)->index();              // « 2026-2027 », calculée depuis starts_on
            $table->string('level', 30);                       // intraclub, departemental, regional…
            $table->string('kind', 30)->default('individuel'); // individuel, interclubs, eau-libre
            $table->date('starts_on');
            $table->date('ends_on');
            $table->string('venue', 150);                      // ex. « Complexe aquatique de Chambly »
            $table->string('city', 100);
            $table->string('address', 255)->nullable();
            $table->unsignedTinyInteger('pool_length')->nullable();   // 25 ou 50
            $table->unsignedTinyInteger('lanes')->nullable();
            $table->string('organizer', 150)->nullable();
            $table->text('description')->nullable();           // présentation publique
            $table->string('extranat_url', 255)->nullable();
            $table->string('live_url', 255)->nullable();
            $table->string('state_override', 20)->nullable();  // annulee | reportee (sinon calculé)

            // Logistique (réservée aux adhérents connectés)
            $table->string('meeting_point', 255)->nullable();
            $table->dateTime('departure_at')->nullable();
            $table->dateTime('return_at')->nullable();
            $table->string('transport', 30)->nullable();       // bus, minibus, covoiturage, train, individuel
            $table->text('accommodation')->nullable();
            $table->text('meals')->nullable();
            $table->text('logistics_notes')->nullable();

            // Publication
            $table->boolean('is_published')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['is_published', 'starts_on']);
        });

        // Groupes du planning concernés (Relève, Ligue…)
        Schema::create('competition_planning_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->foreignId('planning_group_id')->constrained('planning_groups')->cascadeOnDelete();
            $table->unique(['competition_id', 'planning_group_id'], 'competition_group_unique');
        });

        // Encadrement : entraîneurs, officiels, accompagnateurs, chauffeurs
        Schema::create('competition_staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->string('name', 120);
            $table->string('role', 30);                        // entraineur, officiel, accompagnateur, chauffeur
            $table->string('phone', 30)->nullable();           // visible des adhérents connectés uniquement
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Programme : journées et réunions
        Schema::create('competition_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->date('day');
            $table->string('label', 100);                      // ex. « Réunion 1 — séries »
            $table->time('doors_open_at')->nullable();
            $table->time('warmup_at')->nullable();
            $table->time('starts_at')->nullable();
            $table->text('events')->nullable();                // une épreuve par ligne (V1)
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Documents (convocation, programme officiel…)
        Schema::create('competition_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->cascadeOnDelete();
            $table->string('title', 150);
            $table->string('file_path', 255);                  // disque « local » (privé)
            $table->string('original_name', 255);
            $table->unsignedInteger('file_size')->default(0);
            $table->string('visibility', 20)->default('members'); // public | members
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_documents');
        Schema::dropIfExists('competition_sessions');
        Schema::dropIfExists('competition_staff');
        Schema::dropIfExists('competition_planning_group');
        Schema::dropIfExists('competitions');
    }
};

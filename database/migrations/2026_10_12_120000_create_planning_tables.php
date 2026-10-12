<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Module « Planning » : groupes d'entraînement, nageurs, composition des groupes
 * et créneaux hebdomadaires (période scolaire / vacances scolaires).
 */
return new class extends Migration
{
    public function up(): void
    {
        // Groupes d'entraînement (Initiation Bleu, Relève 1, Maîtres…)
        Schema::create('planning_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('slug', 120)->unique();
            $table->string('section', 50);                  // École de natation, Jeunesse, Relève, Ligue, Maîtres
            $table->string('color', 7)->nullable();         // #RRGGBB ; vide = couleur de la section
            $table->text('description')->nullable();
            $table->string('page_url', 255)->nullable();    // lien vers la page de présentation du groupe
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        // Nageurs (saisis dans le module, compte du site facultatif)
        Schema::create('planning_swimmers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->unsignedSmallInteger('birth_year')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();              // note interne, jamais affichée sur le site
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['last_name', 'first_name']);
        });

        // Composition des groupes
        Schema::create('planning_group_swimmer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planning_group_id')->constrained('planning_groups')->cascadeOnDelete();
            $table->foreignId('planning_swimmer_id')->constrained('planning_swimmers')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['planning_group_id', 'planning_swimmer_id'], 'planning_group_swimmer_unique');
        });

        // Créneaux hebdomadaires
        Schema::create('planning_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planning_group_id')->constrained('planning_groups')->cascadeOnDelete();
            $table->string('period', 20);                   // scolaire | vacances
            $table->unsignedTinyInteger('day_of_week');     // 1 = lundi … 7 = dimanche
            $table->time('starts_at');
            $table->time('ends_at');
            $table->string('location', 100)->default('Centre aquatique Cœur d\'O');
            $table->string('lanes', 50)->nullable();        // ex. « 2 couloirs », « couloirs 4 et 5 »
            $table->string('notes', 255)->nullable();       // ex. « sur convocation »
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['period', 'is_active', 'day_of_week', 'starts_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planning_slots');
        Schema::dropIfExists('planning_group_swimmer');
        Schema::dropIfExists('planning_swimmers');
        Schema::dropIfExists('planning_groups');
    }
};

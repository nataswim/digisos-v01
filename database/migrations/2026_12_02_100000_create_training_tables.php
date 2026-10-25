<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Séances & Entraînements (espace coachs → espace adhérent).
 *   training_exercises       : bibliothèque d'exercices (PPG, maison) avec image
 *   training_sessions        : séance (natation | ppg | maison), contenu en JSON (blocs natation ou exercices)
 *   training_session_group   : groupes ciblés
 *   training_session_swimmer : nageurs qui voient la séance (choix individuel du coach)
 *   training_lists           : listes modifiables (nages, matériel, types de blocs, catégories d'exercices)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category', 100)->nullable()->index();
            $table->string('image', 500)->nullable();
            $table->string('video_url', 500)->nullable();
            $table->text('description')->nullable();
            $table->text('tips')->nullable();             // conseils de posture
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('type', 10)->index();         // natation | ppg | maison
            $table->string('title');
            $table->string('objective')->nullable();
            $table->date('session_date')->index();        // date de la séance (maison : à faire avant le)
            $table->time('starts_at')->nullable();
            $table->time('ends_at')->nullable();
            $table->foreignId('planning_slot_id')->nullable()->constrained('planning_slots')->nullOnDelete();
            $table->string('location')->nullable();
            $table->json('equipment')->nullable();
            $table->text('instructions')->nullable();
            $table->json('content')->nullable();          // blocs natation ou exercices
            $table->unsignedSmallInteger('rounds')->default(1);   // PPG / maison : nombre de tours du circuit
            $table->unsignedInteger('total_distance')->nullable();  // mètres (natation)
            $table->unsignedSmallInteger('total_duration')->nullable(); // minutes (estimée ou saisie)
            $table->string('status', 10)->default('draft')->index(); // draft | published
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('training_session_group', function (Blueprint $table) {
            $table->foreignId('training_session_id')->constrained('training_sessions')->cascadeOnDelete();
            $table->foreignId('planning_group_id')->constrained('planning_groups')->cascadeOnDelete();
            $table->primary(['training_session_id', 'planning_group_id']);
        });

        Schema::create('training_session_swimmer', function (Blueprint $table) {
            $table->foreignId('training_session_id')->constrained('training_sessions')->cascadeOnDelete();
            $table->foreignId('planning_swimmer_id')->constrained('planning_swimmers')->cascadeOnDelete();
            $table->primary(['training_session_id', 'planning_swimmer_id']);
        });

        Schema::create('training_lists', function (Blueprint $table) {
            $table->id();
            $table->string('list', 30)->index();          // strokes | equipment | blocks | categories
            $table->string('label', 100);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $listes = [
            'blocks' => ['Échauffement', 'Éducatifs', 'Corps de séance', 'Série principale', 'Retour au calme'],
            'strokes' => ['Crawl', 'Dos', 'Brasse', 'Papillon', '4 nages', 'Nage au choix', 'Jambes', 'Bras', 'Éducatifs', 'Coulées'],
            'equipment' => ['Plaquettes', 'Pull-buoy', 'Palmes', 'Tuba frontal', 'Planche', 'Élastique', 'Tapis', 'Haltères', 'Médecine-ball', 'Corde à sauter'],
            'categories' => ['Gainage', 'Haut du corps', 'Bas du corps', 'Mobilité', 'Cardio', 'Étirements'],
        ];
        $now = now();
        foreach ($listes as $list => $labels) {
            foreach ($labels as $i => $label) {
                DB::table('training_lists')->insert(['list' => $list, 'label' => $label, 'sort_order' => $i, 'created_at' => $now, 'updated_at' => $now]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('training_lists');
        Schema::dropIfExists('training_session_swimmer');
        Schema::dropIfExists('training_session_group');
        Schema::dropIfExists('training_sessions');
        Schema::dropIfExists('training_exercises');
    }
};

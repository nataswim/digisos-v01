<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Module « Grilles de qualification » : une grille, ses catégories d'âge et ses temps
 * (épreuve × Dames / Messieurs × bassin de 25 m / 50 m), en centièmes de seconde.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qualification_grids', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->date('period_start')->nullable();          // performances prises en compte à partir du…
            $table->date('period_end')->nullable();            // … jusqu'au
            $table->boolean('is_published')->default(false);   // visible sur la page publique
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('qualification_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qualification_grid_id')->constrained()->cascadeOnDelete();
            $table->string('label', 60);                       // ex. « U12 »
            $table->unsignedSmallInteger('birth_year_from')->nullable();   // vide = « et avant »
            $table->unsignedSmallInteger('birth_year_to')->nullable();     // vide = « et après »
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('qualification_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qualification_grid_id')->constrained()->cascadeOnDelete();
            $table->foreignId('qualification_category_id')->constrained()->cascadeOnDelete();
            $table->string('event', 20);                        // libellé du module Performances : « 50 NL »
            $table->char('gender', 1);                          // F | M
            $table->unsignedTinyInteger('pool');                // 25 | 50
            $table->unsignedInteger('time_cs');
            $table->timestamps();

            $table->unique(['qualification_category_id', 'event', 'gender', 'pool'], 'qualification_times_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qualification_times');
        Schema::dropIfExists('qualification_categories');
        Schema::dropIfExists('qualification_grids');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crée la table des éléments de contenu des fiches utilisateurs.
     *
     * Relation One-to-Many avec user_profiles.
     * Chaque item contient un titre et une description.
     * Seul l'administrateur peut gérer ces entrées.
     * Le champ `sort_order` permet de trier l'affichage sans migration supplémentaire.
     */
    public function up(): void
    {
        Schema::create('profile_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_profile_id')
                  ->constrained('user_profiles')
                  ->cascadeOnDelete();

            // Contenu géré par l'admin
            $table->string('title', 200);
            $table->text('description');

            // Ordre d'affichage personnalisable
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();

            // Index pour accélérer le tri
            $table->index(['user_profile_id', 'sort_order']);
        });
    }

    /**
     * Supprime la table des éléments de contenu.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_items');
    }
};
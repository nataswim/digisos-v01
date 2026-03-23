<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crée la table des fiches utilisateurs.
     *
     * Relation One-to-One avec users.
     * Ne duplique pas les champs déjà présents sur users
     * (bio, phone, avatar, date_of_birth).
     */
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->unique()
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Informations professionnelles
            $table->string('job_title', 150)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('website', 255)->nullable();

            // Note interne réservée à l'admin
            $table->text('admin_notes')->nullable();

            // Visibilité de la fiche (activée/désactivée par l'admin)
            $table->boolean('is_visible')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Supprime la table des fiches utilisateurs.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
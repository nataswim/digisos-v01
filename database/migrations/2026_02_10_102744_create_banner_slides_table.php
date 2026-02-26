<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 🇬🇧 Run the migrations - Create banner_slides table
     * 🇫🇷 Exécuter les migrations - Créer la table des slides de bannière
     */
    public function up(): void
    {
        Schema::create('banner_slides', function (Blueprint $table) {
            $table->id();

            // 🇬🇧 Parent banner / 🇫🇷 Bannière parente
            $table->foreignId('banner_id')
                  ->constrained('banners')
                  ->cascadeOnDelete();

            // 🇬🇧 Media / 🇫🇷 Média
            $table->string('image', 500)->nullable();             // Chemin image (storage)
            $table->string('image_alt', 255)->nullable();         // Texte alternatif SEO

            // 🇬🇧 Content / 🇫🇷 Contenu texte
            $table->string('title', 255)->nullable();             // Titre principal
            $table->string('subtitle', 255)->nullable();          // Sous-titre
            $table->text('body')->nullable();                     // Texte descriptif

            // 🇬🇧 Call to action / 🇫🇷 Bouton d'action
            $table->string('cta_label', 100)->nullable();         // Libellé bouton
            $table->string('cta_url', 500)->nullable();           // URL cible
            $table->string('cta_target', 20)->default('_self');   // _self | _blank
            $table->string('cta_style', 50)->default('btn-primary'); // Classes Bootstrap

            // 🇬🇧 Visual settings / 🇫🇷 Paramètres visuels
            $table->string('text_color', 20)->default('#ffffff');  // Couleur du texte
            $table->string('text_position', 30)->default('center'); // left | center | right
            $table->unsignedTinyInteger('overlay_opacity')->default(30); // Opacité overlay 0-100

            // 🇬🇧 Order and status / 🇫🇷 Ordre et statut
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            // 🇬🇧 Audit trail / 🇫🇷 Traçabilité
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            // 🇬🇧 Indexes / 🇫🇷 Index
            $table->index('banner_id');
            $table->index(['banner_id', 'is_active', 'sort_order']);
        });
    }

    /**
     * 🇬🇧 Reverse the migrations
     * 🇫🇷 Annuler les migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_slides');
    }
};
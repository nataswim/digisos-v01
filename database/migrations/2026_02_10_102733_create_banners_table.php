<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 🇬🇧 Run the migrations - Create banners table
     * 🇫🇷 Exécuter les migrations - Créer la table des bannières
     */
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();

            // 🇬🇧 Identification / 🇫🇷 Identification
            $table->string('name', 191);                          // Nom interne (admin)
            $table->string('slug', 191)->unique();                // Clé d'intégration : @include('components.banner', ['slug' => 'mon-slug'])

            // 🇬🇧 Display settings / 🇫🇷 Paramètres d'affichage
            $table->unsignedSmallInteger('height')->default(500); // Hauteur en pixels
            $table->boolean('autoplay')->default(true);           // Défilement automatique
            $table->unsignedSmallInteger('autoplay_delay')->default(5000); // Délai en ms
            $table->boolean('show_indicators')->default(true);    // Afficher les points
            $table->boolean('show_controls')->default(true);      // Afficher les flèches
            $table->boolean('pause_on_hover')->default(true);     // Pause au survol
            $table->string('transition', 50)->default('slide');   // slide | fade

            // 🇬🇧 Status / 🇫🇷 Statut
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();              // Note interne

            // 🇬🇧 Audit trail / 🇫🇷 Traçabilité
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // 🇬🇧 Indexes / 🇫🇷 Index
            $table->index('is_active');
            $table->index(['is_active', 'deleted_at']);
        });
    }

    /**
     * 🇬🇧 Reverse the migrations
     * 🇫🇷 Annuler les migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 🇬🇧 Run the migrations - Create gallery_media pivot table
     * 🇫🇷 Exécuter les migrations - Créer la table pivot gallery_media
     */
    public function up(): void
    {
        Schema::create('gallery_media', function (Blueprint $table) {
            $table->id();
            
            // 🇬🇧 Foreign keys / 🇫🇷 Clés étrangères
            $table->foreignId('photo_gallery_id')->constrained('photo_galleries')->onDelete('cascade');
            $table->foreignId('media_id')->constrained('media')->onDelete('cascade');
            
            // 🇬🇧 Sort order for photos in gallery / 🇫🇷 Ordre des photos dans la galerie
            $table->integer('sort_order')->default(0);
            
            // 🇬🇧 Optional caption for each photo / 🇫🇷 Légende optionnelle pour chaque photo
            $table->text('caption')->nullable();
            
            $table->timestamps();
            
            // 🇬🇧 Indexes for performance / 🇫🇷 Index pour les performances
            $table->index('photo_gallery_id');
            $table->index('media_id');
            $table->index('sort_order');
            
            // 🇬🇧 Unique constraint to prevent duplicates / 🇫🇷 Contrainte unique pour éviter les doublons
            $table->unique(['photo_gallery_id', 'media_id'], 'gallery_media_unique');
        });
    }

    /**
     * 🇬🇧 Reverse the migrations
     * 🇫🇷 Annuler les migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_media');
    }
};
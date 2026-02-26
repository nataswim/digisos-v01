<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 🇬🇧 Run the migrations - Create photo_galleries table
     * 🇫🇷 Exécuter les migrations - Créer la table des galeries photos
     */
    public function up(): void
    {
        Schema::create('photo_galleries', function (Blueprint $table) {
            $table->id();
            
            // 🇬🇧 Basic information / 🇫🇷 Informations de base
            $table->string('title', 191);
            $table->string('slug', 191)->unique();
            $table->text('description')->nullable();
            
            // 🇬🇧 Cover image / 🇫🇷 Image de couverture
            $table->foreignId('cover_image_id')->nullable()->constrained('media')->nullOnDelete();
            
            // 🇬🇧 Visibility and publication / 🇫🇷 Visibilité et publication
            $table->enum('visibility', ['public', 'authenticated'])->default('public');
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            
            // 🇬🇧 SEO fields / 🇫🇷 Champs SEO
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            
            // 🇬🇧 Publication date / 🇫🇷 Date de publication
            $table->timestamp('published_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // 🇬🇧 Indexes for performance / 🇫🇷 Index pour les performances
            $table->index('is_published');
            $table->index('is_featured');
            $table->index('visibility');
            $table->index('sort_order');
            $table->index('published_at');
            $table->index(['is_published', 'published_at', 'deleted_at']);
            $table->index(['visibility', 'is_published', 'deleted_at']);
            $table->unique(['slug', 'deleted_at']);
        });
    }

    /**
     * 🇬🇧 Reverse the migrations
     * 🇫🇷 Annuler les migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_galleries');
    }
};
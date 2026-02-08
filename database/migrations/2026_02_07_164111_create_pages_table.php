<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 🇬🇧 Run the migrations - Create pages table
     * 🇫🇷 Exécuter les migrations - Créer la table des pages
     */
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            
            // 🇬🇧 Basic information / 🇫🇷 Informations de base
            $table->string('title', 191);
            $table->string('slug', 191)->unique();
            $table->text('short_description');
            $table->longText('long_description')->nullable();
            $table->string('image')->nullable();
            
            // 🇬🇧 Category relationship / 🇫🇷 Relation avec catégorie
            $table->foreignId('pages_category_id')->nullable()->constrained('pages_categories')->nullOnDelete();
            
            // 🇬🇧 Status and visibility / 🇫🇷 Statut et visibilité
            $table->string('visibility', 50)->default('public');
            $table->boolean('is_published')->default(false);
            $table->integer('sort_order')->default(0);
            
            // 🇬🇧 SEO fields / 🇫🇷 Champs SEO
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_og_image', 255)->nullable();
            $table->string('meta_og_url', 255)->nullable();
            
            // 🇬🇧 Audit trail / 🇫🇷 Traçabilité
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('created_by_name', 150)->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // 🇬🇧 Indexes for performance / 🇫🇷 Index pour les performances
            $table->index('is_published');
            $table->index('visibility');
            $table->index('sort_order');
            $table->index('published_at');
            $table->index('pages_category_id');
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
        Schema::dropIfExists('pages');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Module « Liens utiles » : sites internet classés par catégorie (gestion par les administrateurs).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('link_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('slug', 140)->unique();
            $table->string('description', 500)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('link_category_id')->constrained('link_categories')->restrictOnDelete();
            $table->string('title', 150);
            $table->string('url', 500);
            $table->string('image', 500)->nullable();
            $table->string('description', 1000)->nullable();
            $table->string('keywords', 500)->nullable();   // mots-clés séparés par des virgules
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['link_category_id', 'is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('links');
        Schema::dropIfExists('link_categories');
    }
};

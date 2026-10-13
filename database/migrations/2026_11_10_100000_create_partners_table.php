<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Module Partenaires : une ligne par partenaire du club.
 * Le logo est un fichier du disque « public » (storage/app/public/partners).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 170)->unique();
            $table->string('level', 20)->default('bronze');        // principal | or | argent | bronze (Partner::LEVELS)
            $table->string('category', 30)->nullable();             // sponsor, mécène… (Partner::CATEGORIES)
            $table->string('summary', 255)->nullable();             // phrase courte affichée sous le logo
            $table->text('presentation')->nullable();
            $table->text('actions')->nullable();                    // actions du partenaire vis-à-vis du club
            $table->string('website', 255)->nullable();
            $table->unsignedSmallInteger('partner_since')->nullable(); // année
            $table->string('logo_path', 255)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'level', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};

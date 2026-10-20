<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Module « L'équipe du club » : sections (Comité directeur, Entraîneurs…) et membres.
 * Une personne peut figurer dans plusieurs sections, avec une fonction propre à chacune.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('slug', 140)->unique();
            $table->string('description', 500)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 80);
            $table->string('last_name', 80);
            $table->string('photo', 500)->nullable();
            $table->string('facebook_url', 500)->nullable();
            $table->string('instagram_url', 500)->nullable();
            $table->string('linkedin_url', 500)->nullable();
            $table->string('website_url', 500)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('team_member_section', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_member_id')->constrained('team_members')->cascadeOnDelete();
            $table->foreignId('team_section_id')->constrained('team_sections')->cascadeOnDelete();
            $table->string('position', 150)->nullable();   // fonction : « Président », « Entraîneur Avenirs »…
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->unique(['team_member_id', 'team_section_id']);
        });

        // Sections de départ (modifiables ensuite dans l'administration)
        $now = now();
        DB::table('team_sections')->insert([
            ['name' => 'Comité directeur', 'slug' => 'comite-directeur', 'description' => null, 'sort_order' => 1, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Entraîneurs',      'slug' => 'entraineurs',      'description' => null, 'sort_order' => 2, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bénévoles',        'slug' => 'benevoles',        'description' => null, 'sort_order' => 3, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Officiels',        'slug' => 'officiels',        'description' => null, 'sort_order' => 4, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('team_member_section');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('team_sections');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Licences FFN importées depuis l'extraction extraNat (réservé aux administrateurs).
 * Données personnelles : consultation limitée à l'administration, suppression possible par saison.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Historique des imports
        Schema::create('ffn_license_imports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('original_name', 255);
            $table->unsignedSmallInteger('season')->nullable();
            $table->unsignedInteger('rows_total')->default(0);
            $table->unsignedInteger('created_count')->default(0);
            $table->unsignedInteger('updated_count')->default(0);
            $table->unsignedInteger('unchanged_count')->default(0);
            $table->unsignedInteger('error_count')->default(0);
            $table->unsignedInteger('swimmers_linked')->default(0);
            $table->unsignedInteger('swimmers_updated')->default(0);
            $table->unsignedInteger('swimmers_created')->default(0);
            $table->unsignedInteger('accounts_linked')->default(0);
            $table->json('errors')->nullable();
            $table->timestamps();
        });

        // Une licence par saison et par IUF
        Schema::create('ffn_licenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('season');
            $table->string('iuf', 20);
            $table->string('civility', 20)->nullable();
            $table->char('gender', 1)->nullable();
            $table->string('last_name', 100);
            $table->string('first_name', 100);
            $table->date('birth_date');
            $table->string('nationality', 10)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('address2', 255)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('country', 10)->nullable();
            $table->string('phone1', 30)->nullable();
            $table->string('phone2', 30)->nullable();
            $table->string('phone3', 30)->nullable();
            $table->string('email', 191)->nullable();
            $table->string('club_code', 20)->nullable();
            $table->string('club_name', 150)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('department', 100)->nullable();
            $table->date('medical_certificate_on')->nullable();
            $table->string('license_type', 60)->nullable();
            $table->date('homologated_on')->nullable();
            $table->string('license_status', 40)->nullable();
            $table->boolean('ffn_mail_consent')->nullable();
            $table->json('activities')->nullable();
            $table->foreignId('planning_swimmer_id')->nullable()->constrained('planning_swimmers')->nullOnDelete();
            $table->foreignId('ffn_license_import_id')->nullable()->constrained('ffn_license_imports')->nullOnDelete();
            $table->timestamps();

            $table->unique(['season', 'iuf']);
            $table->index(['last_name', 'first_name']);
            $table->index(['season', 'license_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ffn_licenses');
        Schema::dropIfExists('ffn_license_imports');
    }
};

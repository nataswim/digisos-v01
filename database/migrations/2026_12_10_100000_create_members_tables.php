<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Suivi des adhérents (administrateurs et éditeurs uniquement).
 *   members              : la personne (conservée d'une saison à l'autre)
 *   member_categories    : catégories et tarifs (modifiables)
 *   memberships          : l'adhésion d'une saison (situation, catégorie, tarif, remise, montant, statut, dossier, Pass'Sport…)
 *   membership_payments  : règlements (chèque, virement, coupons sport, ANCV…)
 *   member_logs          : journal des modifications (qui a changé quoi, quand)
 * Montants en centimes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('last_name', 100);
            $table->string('first_name', 100);
            $table->string('gender', 1)->nullable();          // F | M
            $table->date('birth_date')->nullable();
            $table->string('birth_place', 150)->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('phone1', 30)->nullable();
            $table->string('phone2', 30)->nullable();
            $table->string('email')->nullable()->index();
            $table->boolean('image_rights')->nullable();       // droit à l'image : oui / non / inconnu
            $table->json('guardians')->nullable();             // responsables légaux (mineurs)
            $table->foreignId('planning_swimmer_id')->nullable()->constrained('planning_swimmers')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ffn_iuf', 20)->nullable()->index();
            $table->timestamps();

            $table->index(['last_name', 'first_name']);
        });

        Schema::create('member_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 40)->unique();
            $table->string('label', 120);
            $table->unsignedInteger('price_cents')->default(0);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->string('season', 9)->index();              // « 2026-2027 »
            $table->string('situation', 15)->nullable();       // nouveau | renouvellement | retour
            $table->foreignId('member_category_id')->nullable()->constrained('member_categories')->nullOnDelete();
            $table->unsignedInteger('price_cents')->default(0);    // tarif
            $table->unsignedInteger('discount_cents')->default(0); // remise
            $table->unsignedInteger('amount_cents')->default(0);   // montant = tarif − remise
            $table->string('status', 12)->default('preinscrit');   // preinscrit | en_cours | complet | licencie | abandon
            $table->boolean('file_complete')->default(false);
            $table->json('file_items')->nullable();            // pièces du dossier cochées
            $table->boolean('pass_sport')->default(false);
            $table->string('pass_sport_code', 40)->nullable();
            $table->unsignedInteger('pass_sport_cents')->default(0);
            $table->boolean('payment_certificate')->default(false);   // attestation de paiement remise
            $table->date('payment_certificate_on')->nullable();
            $table->boolean('mini_coach')->default(false);
            $table->unsignedSmallInteger('medical_certificate_year')->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();

            $table->unique(['member_id', 'season']);
        });

        Schema::create('membership_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membership_id')->constrained('memberships')->cascadeOnDelete();
            $table->string('method', 15);                      // cheque | virement | especes | coupons | ancv | cb | autre
            $table->unsignedInteger('amount_cents');
            $table->date('cashed_on')->nullable();             // date d'encaissement (prévue ou faite)
            $table->boolean('is_cashed')->default(false);
            $table->string('reference', 120)->nullable();      // n° de chèque, banque, nom de l'émetteur…
            $table->timestamps();
        });

        Schema::create('member_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 20);                      // created | updated | deleted | imported | payment…
            $table->string('subject', 120)->nullable();        // « Adhésion 2026-2027 », « Règlement »…
            $table->json('changes')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        // Catégories de départ (tarifs 2026-2027 du club, modifiables)
        $now = now();
        $categories = [
            ['ENF-INITIATION', 'Enfants — initiation / école de natation', 17500],
            ['RELEVE', 'Relève', 19000],
            ['JEUNES', 'Jeunes', 19000],
            ['ETUDIANTS', 'Étudiants 18-25 ans', 19000],
            ['ANCIENS-ETUDIANTS', 'Anciens nageurs étudiants hors Bressuire', 11000],
            ['ADULTES', 'Adultes', 21000],
            ['MAITRES', 'Maîtres', 21000],
            ['ENCADREMENT', 'Encadrement', 0],
            ['BENEVOLE', 'Bénévoles', 0],
            ['SPECIALE', 'Spéciale', 0],
        ];
        foreach ($categories as $i => [$code, $label, $price]) {
            DB::table('member_categories')->insert(['code' => $code, 'label' => $label, 'price_cents' => $price, 'sort_order' => $i, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('member_logs');
        Schema::dropIfExists('membership_payments');
        Schema::dropIfExists('memberships');
        Schema::dropIfExists('member_categories');
        Schema::dropIfExists('members');
    }
};

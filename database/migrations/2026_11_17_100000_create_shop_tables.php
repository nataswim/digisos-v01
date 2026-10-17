<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Module Boutique (vitrine) : catégories et produits.
 * Pas de panier ni de paiement : chaque produit renvoie vers un lien de commande externe.
 * Les images sont des adresses choisies dans la médiathèque du site.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('slug', 140)->unique();
            $table->string('description', 500)->nullable();
            $table->string('image', 500)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('shop_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_category_id')->constrained('shop_categories')->restrictOnDelete();
            $table->string('name', 150);
            $table->string('slug', 170)->unique();
            $table->string('summary', 255)->nullable();       // phrase courte affichée sur la carte
            $table->text('description')->nullable();          // HTML de l'éditeur de texte
            $table->decimal('price_min', 8, 2)->nullable();   // prix, ou bas de la fourchette
            $table->decimal('price_max', 8, 2)->nullable();   // haut de la fourchette (facultatif)
            $table->string('price_note', 100)->nullable();    // ex. « prix adhérent », « selon la taille »
            $table->string('image', 500)->nullable();         // image principale
            $table->json('gallery')->nullable();              // images supplémentaires (adresses)
            $table->string('order_url', 500)->nullable();     // lien de commande (site externe)
            $table->string('order_label', 50)->nullable();    // texte du bouton, « Commander » par défaut
            $table->string('badge', 20)->nullable();          // nouveau | precommande | epuise (ShopProduct::BADGES)
            $table->boolean('is_featured')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'shop_category_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_products');
        Schema::dropIfExists('shop_categories');
    }
};

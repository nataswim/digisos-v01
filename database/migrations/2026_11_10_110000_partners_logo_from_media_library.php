<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Module Partenaires : le logo devient une adresse d'image (colonne « logo »),
 * choisie dans la médiathèque du site ou envoyée depuis l'ordinateur.
 * Les logos déjà envoyés (colonne « logo_path ») sont repris tels quels.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->string('logo', 500)->nullable()->after('partner_since');
        });

        DB::table('partners')->whereNotNull('logo_path')->orderBy('id')->each(function ($partner) {
            DB::table('partners')->where('id', $partner->id)->update(['logo' => '/storage/' . ltrim($partner->logo_path, '/')]);
        });

        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn('logo_path');
        });
    }

    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->string('logo_path', 255)->nullable()->after('partner_since');
        });

        DB::table('partners')->where('logo', 'like', '/storage/%')->orderBy('id')->each(function ($partner) {
            DB::table('partners')->where('id', $partner->id)->update(['logo_path' => substr($partner->logo, strlen('/storage/'))]);
        });

        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn('logo');
        });
    }
};

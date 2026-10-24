<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Liens utiles : pages Facebook et Instagram du site (facultatives).
 * Les liens déjà enregistrés ne sont pas modifiés.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('links', function (Blueprint $table) {
            $table->string('facebook_url', 500)->nullable()->after('url');
            $table->string('instagram_url', 500)->nullable()->after('facebook_url');
        });
    }

    public function down(): void
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dropColumn(['facebook_url', 'instagram_url']);
        });
    }
};

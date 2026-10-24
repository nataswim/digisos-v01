<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Planning : photo du nageur (image de la médiathèque), affichée uniquement dans l'administration.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planning_swimmers', function (Blueprint $table) {
            $table->string('photo', 500)->nullable()->after('last_name');
        });
    }

    public function down(): void
    {
        Schema::table('planning_swimmers', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
    }
};

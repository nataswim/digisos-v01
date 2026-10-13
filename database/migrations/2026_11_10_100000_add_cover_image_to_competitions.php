<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Compétitions : image de couverture et présentation en texte enrichi (éditeur Quill, comme les fiches).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            $table->string('image', 255)->nullable()->after('description');   // URL (médiathèque ou externe)
            $table->longText('description')->nullable()->change();              // HTML enrichi
        });
    }

    public function down(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->text('description')->nullable()->change();
        });
    }
};

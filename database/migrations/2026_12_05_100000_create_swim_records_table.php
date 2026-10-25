<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Meilleures performances et records : records du club (saisis par les coachs) et records officiels
 * (département, région, France, Europe, monde), en bassin de 25 m et de 50 m, par catégorie d'âge.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('swim_records', function (Blueprint $table) {
            $table->id();
            $table->string('level', 15);                  // club | departement | region | france | europe | monde
            $table->unsignedTinyInteger('pool');          // 25 | 50
            $table->string('category', 5)->default('all'); // all | 12 … 18
            $table->string('sex', 1);                     // F | M | X (relais mixtes)
            $table->string('event', 20);                  // « 50 NL », « 200 4N », « 4x100 NL »…
            $table->unsignedInteger('time_cs');           // temps en centièmes de seconde
            $table->text('swimmers');                     // un nageur par ligne (relais : 4 lignes)
            $table->unsignedSmallInteger('birth_year')->nullable();
            $table->string('club')->nullable();
            $table->date('record_date')->nullable();
            $table->string('location')->nullable();
            $table->string('competition')->nullable();
            $table->string('source_url', 500)->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['level', 'pool', 'category', 'sex']);
            $table->unique(['level', 'pool', 'category', 'sex', 'event']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('swim_records');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Plusieurs comptes du site par nageur (ex. la mère, le père et le nageur lui-même).
 *
 * - planning_swimmer_user : comptes qui suivent un nageur (séances, assiduité, performances, convocations, messages) ;
 * - member_user : comptes d'une fiche adhérent sans fiche nageur (adulte, bénévole…).
 *
 * Les rattachements existants (colonnes user_id) sont repris, puis les colonnes sont supprimées.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planning_swimmer_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planning_swimmer_id')->constrained('planning_swimmers')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('relation', 50)->nullable();
            $table->timestamps();
            $table->unique(['planning_swimmer_id', 'user_id'], 'planning_swimmer_user_unique');
        });

        Schema::create('member_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('relation', 50)->nullable();
            $table->timestamps();
            $table->unique(['member_id', 'user_id'], 'member_user_unique');
        });

        $now = now();

        // Nageurs : le compte déjà rattaché
        DB::table('planning_swimmers')->whereNotNull('user_id')->orderBy('id')
            ->each(function ($s) use ($now) {
                DB::table('planning_swimmer_user')->insertOrIgnore([
                    'planning_swimmer_id' => $s->id, 'user_id' => $s->user_id, 'created_at' => $now, 'updated_at' => $now,
                ]);
            });

        // Adhérents : le compte va sur la fiche nageur liée, sinon sur la fiche adhérent
        DB::table('members')->whereNotNull('user_id')->orderBy('id')
            ->each(function ($m) use ($now) {
                if ($m->planning_swimmer_id) {
                    DB::table('planning_swimmer_user')->insertOrIgnore([
                        'planning_swimmer_id' => $m->planning_swimmer_id, 'user_id' => $m->user_id, 'created_at' => $now, 'updated_at' => $now,
                    ]);
                } else {
                    DB::table('member_user')->insertOrIgnore([
                        'member_id' => $m->id, 'user_id' => $m->user_id, 'created_at' => $now, 'updated_at' => $now,
                    ]);
                }
            });

        Schema::table('planning_swimmers', fn (Blueprint $table) => $table->dropConstrainedForeignId('user_id'));
        Schema::table('members', fn (Blueprint $table) => $table->dropConstrainedForeignId('user_id'));
    }

    public function down(): void
    {
        Schema::table('planning_swimmers', fn (Blueprint $table) => $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete());
        Schema::table('members', fn (Blueprint $table) => $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete());

        // On ne peut garder qu'un compte : le premier rattaché
        foreach (DB::table('planning_swimmer_user')->orderBy('id')->get()->unique('planning_swimmer_id') as $l) {
            DB::table('planning_swimmers')->where('id', $l->planning_swimmer_id)->update(['user_id' => $l->user_id]);
        }
        foreach (DB::table('member_user')->orderBy('id')->get()->unique('member_id') as $l) {
            DB::table('members')->where('id', $l->member_id)->update(['user_id' => $l->user_id]);
        }

        Schema::dropIfExists('member_user');
        Schema::dropIfExists('planning_swimmer_user');
    }
};

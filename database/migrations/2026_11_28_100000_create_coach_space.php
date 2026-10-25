<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Espace coachs :
 *   - rôle « Coach » (slug coach) ;
 *   - documents réservés aux coachs (fichier privé ou lien) ;
 *   - notes de suivi des nageurs (visibles des coachs et administrateurs uniquement).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! DB::table('roles')->where('slug', 'coach')->exists()) {
            DB::table('roles')->insert([
                'name' => 'Coach',
                'slug' => 'coach',
                'display_name' => 'Coach',
                'description' => "Entraîneur du club : espace coachs (planning, compétitions et convocations, documents réservés, suivi des nageurs).",
                'level' => 40,
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::create('coach_documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category', 60)->nullable()->index();
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();       // stockage privé : storage/app/coach-documents
            $table->string('original_name')->nullable();
            $table->string('mime_type', 120)->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('url', 500)->nullable();        // ou lien externe (Drive, vidéo…)
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('swimmer_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planning_swimmer_id')->constrained('planning_swimmers')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('noted_on');
            $table->string('category', 30)->default('entrainement');
            $table->text('content');
            $table->timestamps();

            $table->index(['planning_swimmer_id', 'noted_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('swimmer_notes');
        Schema::dropIfExists('coach_documents');
        // Le rôle n'est supprimé que s'il n'est attribué à personne
        $role = DB::table('roles')->where('slug', 'coach')->first();
        if ($role && ! DB::table('users')->where('role_id', $role->id)->exists()) {
            DB::table('roles')->where('id', $role->id)->delete();
        }
    }
};

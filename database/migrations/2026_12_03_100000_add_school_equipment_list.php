<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Séances « École de natation » : liste modifiable du matériel pédagogique.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('training_lists')->where('list', 'school_equipment')->exists()) {
            return;
        }
        $now = now();
        foreach (['Frites', 'Planches', 'Cerceaux', 'Tapis flottant', 'Anneaux lestés', 'Ballons', 'Bâtons lestés', 'Toboggan', 'Ceintures', 'Brassards'] as $i => $label) {
            DB::table('training_lists')->insert(['list' => 'school_equipment', 'label' => $label, 'sort_order' => $i, 'created_at' => $now, 'updated_at' => $now]);
        }
    }

    public function down(): void
    {
        DB::table('training_lists')->where('list', 'school_equipment')->delete();
    }
};

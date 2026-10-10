<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Les 4 rôles du site du CNBB.
 * Les slugs (admin, editor, user, visitor) sont utilisés dans le code : ne pas les modifier.
 */
class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Role::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $roles = [
            [
                'name' => 'Administrateur',
                'slug' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Membre du bureau ou webmestre : accès complet au site (comptes, rôles, contenus, réglages).',
                'level' => 100,
                'is_default' => false,
            ],
            [
                'name' => 'Éditeur',
                'slug' => 'editor',
                'display_name' => 'Editor',
                'description' => 'Bénévole chargé de la communication : publie les actualités, fiches, pages, vidéos, galeries et documents.',
                'level' => 50,
                'is_default' => false,
            ],
            [
                'name' => 'Adhérent',
                'slug' => 'user',
                'display_name' => 'Adhérent',
                'description' => 'Nageur ou famille adhérente du club : accès aux contenus réservés et aux documents à télécharger.',
                'level' => 10,
                'is_default' => false,
            ],
            [
                'name' => 'Visiteur',
                'slug' => 'visitor',
                'display_name' => 'Visiteur',
                'description' => 'Compte créé sur le site, adhésion non validée : accès aux contenus publics uniquement.',
                'level' => 0,
                'is_default' => true, // rôle attribué à l'inscription sur le site
            ],
        ];

        foreach ($roles as $data) {
            Role::create($data);
            $this->command->info("✅ Rôle : {$data['name']} (niveau {$data['level']})");
        }

        $this->command->info('🎉 RolesTableSeeder : ' . count($roles) . ' rôles créés');
    }
}

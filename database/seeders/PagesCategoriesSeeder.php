<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PagesCategory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PagesCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PagesCategory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $admin = User::whereHas('role', fn($q) => $q->where('slug', 'admin'))->first();

        $categories = [
            [
                'name' => 'À propos',
                'slug' => 'a-propos',
                'description' => 'Pages institutionnelles présentant Digital\'SOS, la mission, l\'équipe et les valeurs de la plateforme.',
                'image' => 'pages-categories/a-propos.jpg',
                'meta_title' => 'À propos - Digital\'SOS',
                'meta_description' => 'Découvrez Digital\'SOS, notre mission et notre équipe dédiée à la digitalisation sportive.',
                'meta_keywords' => 'à propos, mission, équipe, digital\'sos',
                'is_active' => true,
                'sort_order' => 1,
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ],
            [
                'name' => 'Informations Légales',
                'slug' => 'informations-legales',
                'description' => 'Mentions légales, politique de confidentialité, CGU et documents juridiques obligatoires.',
                'image' => 'pages-categories/informations-legales.jpg',
                'meta_title' => 'Informations légales - Digital\'SOS',
                'meta_description' => 'Consultez nos mentions légales, politique de confidentialité et conditions générales d\'utilisation.',
                'meta_keywords' => 'mentions légales, confidentialité, cgu, rgpd',
                'is_active' => true,
                'sort_order' => 2,
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ],
            [
                'name' => 'Support & Aide',
                'slug' => 'support-aide',
                'description' => 'Guides d\'utilisation, FAQ, tutoriels et ressources d\'assistance pour les utilisateurs.',
                'image' => 'pages-categories/support-aide.jpg',
                'meta_title' => 'Support et aide - Digital\'SOS',
                'meta_description' => 'Accédez à nos guides, FAQ et tutoriels pour utiliser Digital\'SOS efficacement.',
                'meta_keywords' => 'support, aide, faq, tutoriels, guides',
                'is_active' => true,
                'sort_order' => 3,
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ],
        ];

        foreach ($categories as $data) {
            $category = PagesCategory::create($data);
            $this->command->info("✅ Catégorie page : {$category->name}");
        }

        $this->command->info('🎉 PagesCategoriesSeeder terminé : 3 catégories créées');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Post::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $editor = User::whereHas('role', fn($q) => $q->where('slug', 'editor'))->first();
        $categoryActu = Category::where('slug', 'actualites')->first();
        $categoryConseils = Category::where('slug', 'conseils-methodologie')->first();
        $categoryTemoignages = Category::where('slug', 'temoignages-success-stories')->first();

        $posts = [
            [
                'name' => 'Digital\'SOS révolutionne la gestion sportive',
                'slug' => 'digitalsos-revolutionne-gestion-sportive',
                'intro' => 'Découvrez comment notre plateforme transforme le quotidien des structures sportives avec la méthode M2PC : Matériel, Planning, Personnel, Contenu.',
                'content' => '<h2>Une solution complète pour les managers sportifs</h2>
<p>Digital\'SOS centralise tous les aspects de la gestion sportive dans une interface unique et intuitive. Fini les tableaux Excel dispersés et les emails perdus !</p>

<h3>Les 4 piliers de Digital\'SOS</h3>
<ul>
<li><strong>Matériel :</strong> Inventaire en temps réel et traçabilité complète de vos équipements</li>
<li><strong>Planning :</strong> Synchronisation intelligente des entraînements, matchs et événements</li>
<li><strong>Personnel :</strong> Gestion simplifiée des coachs, bénévoles et salariés</li>
<li><strong>Contenu :</strong> Bibliothèque numérique de supports pédagogiques</li>
</ul>

<h3>Des résultats concrets</h3>
<p>Nos premiers utilisateurs rapportent une réduction de 40% du temps consacré aux tâches administratives, permettant de se recentrer sur l\'essentiel : le terrain et les athlètes.</p>

<blockquote>
"Digital\'SOS a transformé notre club. Nous gérons maintenant 200 licenciés avec une efficacité jamais atteinte." - Pierre Dubois, Coach
</blockquote>',
                'type' => 'article',
                'category_id' => $categoryActu->id,
                'category_name' => $categoryActu->name,
                'is_featured' => true,
                'image' => 'posts/digitalsos-revolution.jpg',
                'meta_title' => 'Digital\'SOS révolutionne la gestion sportive',
                'meta_keywords' => 'gestion sportive, digital, m2pc, révolution',
                'meta_description' => 'Découvrez comment Digital\'SOS transforme la gestion des structures sportives avec la méthode M2PC.',
                'hits' => 234,
                'order' => 1,
                'status' => 'published',
                'visibility' => 'public',
                'created_by' => $editor?->id,
                'created_by_name' => $editor?->name,
                'updated_by' => $editor?->id,
                'published_at' => now()->subDays(5),
            ],
            [
                'name' => '5 astuces pour optimiser vos plannings d\'entraînement',
                'slug' => '5-astuces-optimiser-plannings-entrainement',
                'intro' => 'Les meilleurs coachs utilisent ces techniques éprouvées pour maximiser l\'efficacité de leurs séances et prévenir le surentraînement.',
                'content' => '<h2>Optimisez vos plannings comme un pro</h2>
<p>Un planning bien conçu est la clé du succès sportif. Voici nos 5 conseils d\'experts pour structurer vos entraînements.</p>

<h3>1. Alterner intensité et récupération</h3>
<p>Le principe de surcompensation nécessite des phases de repos. Planifiez 1 journée de récupération active tous les 3 jours d\'entraînement intensif.</p>

<h3>2. Bloquer des créneaux fixes</h3>
<p>La régularité crée des habitudes. Fixez les mêmes horaires chaque semaine pour installer une routine performante.</p>

<h3>3. Anticiper les imprévus</h3>
<p>Prévoyez toujours un plan B en cas d\'indisponibilité de matériel ou d\'installations. Digital\'SOS vous alerte automatiquement des conflits.</p>

<h3>4. Varier les types de séances</h3>
<p>Technique, endurance, vitesse, force : la diversification prévient la monotonie et optimise le développement global.</p>

<h3>5. Évaluer et ajuster</h3>
<p>Analysez mensuellement vos résultats pour adapter votre planification. Les meilleurs plans évoluent avec vos athlètes.</p>',
                'type' => 'article',
                'category_id' => $categoryConseils->id,
                'category_name' => $categoryConseils->name,
                'is_featured' => false,
                'image' => 'posts/optimiser-plannings.jpg',
                'meta_title' => '5 astuces pour optimiser vos plannings d\'entraînement',
                'meta_keywords' => 'plannings, entraînement, optimisation, conseils',
                'meta_description' => 'Découvrez 5 techniques éprouvées pour maximiser l\'efficacité de vos plannings d\'entraînement.',
                'hits' => 156,
                'order' => 2,
                'status' => 'published',
                'visibility' => 'authenticated',
                'created_by' => $editor?->id,
                'created_by_name' => $editor?->name,
                'updated_by' => $editor?->id,
                'published_at' => now()->subDays(10),
            ],
            [
                'name' => 'Comment le club AquaSport a triplé ses adhésions',
                'slug' => 'club-aquasport-triple-adhesions',
                'intro' => 'Retour sur la transformation digitale du club AquaSport qui est passé de 50 à 150 licenciés en 18 mois grâce à Digital\'SOS.',
                'content' => '<h2>Success Story : AquaSport</h2>
<p>Le club AquaSport, basé à Lyon, faisait face à des défis de croissance majeurs. Direction débordée, plannings confus, matériel égaré... La situation devenait ingérable.</p>

<h3>Le diagnostic initial</h3>
<p>En 2024, AquaSport comptait 50 licenciés mais perdait 30% de ses membres chaque année par manque d\'organisation. Les entraînements étaient improvisés, le matériel mal géré.</p>

<h3>La solution Digital\'SOS</h3>
<p>En mars 2024, le club a adopté notre plateforme. Résultats après 18 mois :</p>
<ul>
<li>📈 Licenciés : 50 → 150 (+200%)</li>
<li>⏱️ Temps admin : -60%</li>
<li>💰 Chiffre d\'affaires : +180%</li>
<li>😊 Satisfaction : 4.8/5</li>
</ul>

<h3>Le témoignage du président</h3>
<blockquote>
"Digital\'SOS nous a permis de professionnaliser notre gestion sans perdre notre âme associative. Les bénévoles se concentrent enfin sur l\'accompagnement des nageurs plutôt que sur la paperasse." - Marc Durand, Président AquaSport
</blockquote>

<h3>Les clés du succès</h3>
<p>La centralisation des données et l\'automatisation des tâches répétitives ont libéré du temps pour développer l\'offre sportive et améliorer l\'expérience adhérent.</p>',
                'type' => 'article',
                'category_id' => $categoryTemoignages->id,
                'category_name' => $categoryTemoignages->name,
                'is_featured' => true,
                'image' => 'posts/aquasport-success.jpg',
                'meta_title' => 'Success Story : AquaSport triple ses adhésions avec Digital\'SOS',
                'meta_keywords' => 'témoignage, aquasport, success story, adhésions',
                'meta_description' => 'Découvrez comment le club AquaSport a triplé ses adhésions en 18 mois grâce à Digital\'SOS.',
                'hits' => 189,
                'order' => 3,
                'status' => 'published',
                'visibility' => 'public',
                'created_by' => $editor?->id,
                'created_by_name' => $editor?->name,
                'updated_by' => $editor?->id,
                'published_at' => now()->subDays(15),
            ],
        ];

        foreach ($posts as $data) {
            $post = Post::create($data);
            $this->command->info("✅ Post créé : {$post->name}");
        }

        $this->command->info('🎉 PostsTableSeeder terminé : 3 posts créés');
    }
}

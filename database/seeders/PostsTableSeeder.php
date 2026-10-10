<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Articles de démonstration du CNBB.
 * Contenu tiré uniquement des informations fournies par le club (historique, inscription 2026-2027).
 * Les articles de la catégorie « presse » alimentent la page /revue-de-presse.
 */
class PostsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Post::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $author = User::whereHas('role', fn ($q) => $q->where('slug', 'editor'))->first()
            ?? User::whereHas('role', fn ($q) => $q->where('slug', 'admin'))->first();

        $cat = fn (string $slug) => Category::where('slug', $slug)->first()
            ?? throw new \RuntimeException("Catégorie « {$slug} » absente : lancer CategoriesTableSeeder avant PostsTableSeeder.");

        $posts = [
            [
                'category' => 'vie-du-club',
                'name' => 'Inscriptions saison 2026-2027 : tout ce qu\'il faut savoir',
                'slug' => 'inscriptions-saison-2026-2027',
                'intro' => 'Tarifs, réductions familles, pièces du dossier et date limite : le point complet pour s\'inscrire ou se réinscrire au CNBB cette saison.',
                'content' => <<<'HTML'
<p>Les inscriptions pour la saison 2026-2027 sont ouvertes. L'adhésion au CNBB est réservée aux personnes <strong>sachant nager 25 mètres</strong> ; une évaluation peut être proposée lors d'une première adhésion.</p>

<h2>Les tarifs</h2>
<ul>
<li>École de natation : <strong>175 €</strong></li>
<li>Natation jeunes et étudiants (18-25 ans) : <strong>190 €</strong></li>
<li>Anciens nageurs étudiants hors Bressuire : <strong>110 €</strong></li>
<li>Adultes : <strong>210 €</strong></li>
</ul>
<p>Pour une même famille : <strong>10 € de remise</strong> pour 2 adhésions, <strong>10 %</strong> à partir de 3 adhésions (en début de saison uniquement).</p>
<p><strong>Attention :</strong> toute réinscription après le <strong>30 septembre 2026</strong> est majorée de 10 €.</p>

<h2>Le dossier</h2>
<ul>
<li>la fiche d'inscription (et la fiche sanitaire pour les compétiteurs) ;</li>
<li>le règlement ;</li>
<li>le certificat médical pour une première adhésion (ou l'attestation de santé pour les mineurs) ;</li>
<li>une photo pour les nouveaux adhérents ;</li>
<li>5 € de caution pour le badge d'accès.</li>
</ul>
<p>Le dossier complet est à déposer dans la <strong>boîte aux lettres du club</strong>, derrière l'abribus en bas du centre aquatique Cœur d'O.</p>
<p>Le détail de chaque pièce et des moyens de paiement est disponible dans la rubrique Infos pratiques.</p>
HTML,
                'is_featured' => true,
                'days' => 30,
            ],
            [
                'category' => 'vie-du-club',
                'name' => 'Parents : devenez officiels ou rejoignez le comité directeur',
                'slug' => 'parents-devenez-officiels-comite-directeur',
                'intro' => 'Sans officiels, pas de compétition ; sans bénévoles, pas de club. Le CNBB invite les parents de nageurs à s\'engager à leurs côtés.',
                'content' => <<<'HTML'
<p>Le Cercle des Nageurs du Bocage Bressuirais est une association : il fonctionne grâce à l'engagement de ses bénévoles.</p>

<h2>Devenir officiel</h2>
<p>Chaque compétition de la Fédération Française de Natation a besoin d'officiels au bord du bassin (chronométreurs, juges). Un club qui engage des nageurs doit présenter des officiels. Aucune connaissance préalable n'est nécessaire : une formation est proposée et l'on apprend au contact des officiels expérimentés.</p>

<h2>Rejoindre le comité directeur</h2>
<p>Le comité directeur organise la vie du club : inscriptions, relations avec la piscine et l'agglomération, organisation des événements, budget. Les parents y sont les bienvenus, quel que soit le temps dont ils disposent.</p>

<p>Intéressé ? Parlez-en aux entraîneurs ou écrivez au club depuis la page Contact.</p>
HTML,
                'is_featured' => false,
                'days' => 21,
            ],
            [
                'category' => 'ecole-de-natation',
                'name' => 'Sauv\'nage, Pass\'sports de l\'eau, Pass\'compétition : les étapes de l\'école de natation',
                'slug' => 'tests-ecole-de-natation-francaise',
                'intro' => 'À l\'école de natation, les enfants progressent en validant les trois tests de l\'École de Natation Française (ENF).',
                'content' => <<<'HTML'
<p>Le parcours des jeunes nageurs du club suit les trois étapes de l'École de Natation Française (ENF), mises en place par la Fédération Française de Natation.</p>

<h2>1. Le Sauv'nage</h2>
<p>Première étape : l'enfant apprend à être à l'aise et en sécurité dans l'eau (entrer dans l'eau, s'immerger, flotter, se déplacer, se laisser remonter).</p>

<h2>2. Le Pass'sports de l'eau</h2>
<p>L'enfant découvre plusieurs disciplines aquatiques et développe des habiletés variées.</p>

<h2>3. Le Pass'compétition</h2>
<p>Dernière étape avant la compétition : le nageur montre qu'il maîtrise les règles et les bases techniques de la natation course.</p>

<p>Les entraîneurs font passer les tests au fil de la saison et informent les familles des résultats.</p>
HTML,
                'is_featured' => true,
                'days' => 14,
            ],
            [
                'category' => 'vie-du-club',
                'name' => 'De 1954 à aujourd\'hui : l\'histoire du club',
                'slug' => 'histoire-du-club-1954-aujourd-hui',
                'intro' => 'Fondé le 22 mars 1954, le club de natation de Bressuire a traversé sept décennies. Retour sur les grandes dates.',
                'content' => <<<'HTML'
<ul>
<li><strong>22 mars 1954</strong> : fondation du Club Nautique Bressuirais, déclaré au Journal officiel du 12 mai 1954.</li>
<li><strong>1963</strong> : création d'une école de sauvetage.</li>
<li><strong>1964</strong> : le club est reconnu d'utilité publique.</li>
<li><strong>1987</strong> : le club devient le Cercle des Nageurs du Bocage Bressuirais (CNBB).</li>
<li><strong>1989-1990</strong> : lancement de l'aquagym et des « 12 heures de natation ».</li>
<li><strong>1994</strong> : 504 adhérents ; <strong>1998</strong> : 580 adhérents.</li>
<li><strong>2001-2011</strong> : une section de natation synchronisée, animée par Emmanuelle Babin.</li>
<li><strong>2004</strong> : le club fête ses 50 ans.</li>
<li><strong>2009</strong> : ouverture du centre aquatique Cœur d'O.</li>
<li><strong>12 juillet 2013</strong> : 1<sup>er</sup> Aquathlon du Bocage.</li>
<li><strong>2015-2016</strong> : plus de 200 licenciés, 9 nageurs au niveau régional, sections sport adapté et triathlon.</li>
<li><strong>Depuis 2018</strong> : Sébastien Chevalier préside le club.</li>
</ul>
<p>Vous avez des photos ou des souvenirs du club ? Contactez-nous pour enrichir cette histoire.</p>
HTML,
                'is_featured' => false,
                'days' => 45,
            ],
            [
                'category' => 'evenements',
                'name' => 'L\'Aquathlon du Bocage, né au club en 2013',
                'slug' => 'aquathlon-du-bocage-2013',
                'intro' => 'Le 12 juillet 2013, le CNBB organisait le premier Aquathlon du Bocage : une épreuve qui enchaîne natation et course à pied.',
                'content' => <<<'HTML'
<p>Le <strong>12 juillet 2013</strong>, le club a organisé le <strong>premier Aquathlon du Bocage</strong>. Le principe : enchaîner une épreuve de natation et une course à pied.</p>
<p>Cet événement illustre l'ouverture du club vers les disciplines enchaînées, qui a conduit à la création d'une section triathlon.</p>
<p><em>Photos, résultats ou souvenirs de cette édition : envoyez-les au club pour compléter cet article.</em></p>
HTML,
                'is_featured' => false,
                'days' => 40,
            ],
            [
                'category' => 'presse',
                'name' => 'Journal officiel du 12 mai 1954 : la déclaration du club',
                'slug' => 'journal-officiel-12-mai-1954-declaration-du-club',
                'intro' => 'Le Journal officiel de la République française n° 843 du 12 mai 1954 publie la déclaration de l\'association fondée à Bressuire le 22 mars 1954.',
                'content' => <<<'HTML'
<p>La toute première trace écrite du club se trouve au <strong>Journal officiel de la République française du 12 mai 1954 (n° 843)</strong>, qui publie la déclaration de l'association fondée à Bressuire le <strong>22 mars 1954</strong> sous le nom de Club Nautique Bressuirais.</p>
<p>Le club prendra en 1987 son nom actuel : Cercle des Nageurs du Bocage Bressuirais.</p>
<p><em>La revue de presse s'enrichira des articles de la presse locale consacrés au club. Vous en conservez ? Transmettez-les au club.</em></p>
HTML,
                'is_featured' => false,
                'days' => 50,
            ],
        ];

        foreach ($posts as $i => $p) {
            $category = $cat($p['category']);

            Post::create([
                'name' => $p['name'],
                'slug' => $p['slug'],
                'intro' => $p['intro'],
                'content' => trim($p['content']),
                'type' => 'article',
                'category_id' => $category->id,
                'category_name' => $category->name,
                'is_featured' => $p['is_featured'],
                'image' => null,
                'meta_title' => mb_strimwidth($p['name'], 0, 55, '…') . ' - CNBB',
                'meta_keywords' => 'CNBB, natation, Bressuire, ' . implode(', ', self::tagMap()[$p['slug']] ?? []),
                'meta_description' => mb_strimwidth($p['intro'], 0, 160, '…'),
                'hits' => 0,
                'order' => $i + 1,
                'status' => 'published',
                'visibility' => 'public',
                'created_by' => $author?->id,
                'created_by_name' => $author?->name,
                'updated_by' => $author?->id,
                'published_at' => now()->subDays($p['days']),
            ]);
            $this->command->info("✅ Article : {$p['name']}");
        }

        $this->command->info('🎉 PostsTableSeeder : ' . count($posts) . ' articles créés');
    }

    /**
     * Associations article → tags, lues par TaggablesTableSeeder (une seule source de vérité).
     */
    public static function tagMap(): array
    {
        return [
            'inscriptions-saison-2026-2027' => ['inscriptions', 'coeur-d-o'],
            'parents-devenez-officiels-comite-directeur' => ['benevolat', 'natation-course'],
            'tests-ecole-de-natation-francaise' => ['ecole-de-natation'],
            'histoire-du-club-1954-aujourd-hui' => ['histoire-du-club', 'coeur-d-o'],
            'aquathlon-du-bocage-2013' => ['aquathlon', 'histoire-du-club'],
            'journal-officiel-12-mai-1954-declaration-du-club' => ['histoire-du-club'],
        ];
    }
}

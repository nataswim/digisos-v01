<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PagesCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Page::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $admin = User::whereHas('role', fn ($q) => $q->where('slug', 'admin'))->first();

        $cat = fn (string $slug) => PagesCategory::where('slug', $slug)->first()
            ?? throw new \RuntimeException("Catégorie de pages « {$slug} » absente : lancer PagesCategoriesSeeder d'abord.");

        $pages = [
            [
                'category' => 'le-club',
                'title' => 'Le club en bref',
                'slug' => 'le-club-en-bref',
                'short_description' => 'Le Cercle des Nageurs du Bocage Bressuirais : un club de natation course affilié à la FFN depuis 1954.',
                'long_description' => <<<'HTML'
<p>Le <strong>Cercle des Nageurs du Bocage Bressuirais (CNBB)</strong> est une association loi 1901 fondée le 22 mars 1954 à Bressuire. Affilié à la <strong>Fédération Française de Natation</strong>, le club est spécialisé en <strong>natation course</strong>.</p>

<h2>Où nager ?</h2>
<p>Les entraînements ont lieu au <strong>centre aquatique Cœur d'O</strong> (Agglo 2B), à Bressuire.</p>

<h2>Pour qui ?</h2>
<p>Le club accueille toute personne sachant nager 25 mètres : enfants de l'école de natation, jeunes et étudiants, adultes.</p>

<h2>Coordonnées</h2>
<p>CNBB — 40 boulevard de la République, 79300 Bressuire<br>
Courriel : <a href="mailto:cnbb079@gmail.com">cnbb079@gmail.com</a><br>
Téléphone : 06 02 35 08 43</p>

<p>Président : Sébastien Chevalier (depuis 2018).</p>
HTML,
            ],
            [
                'category' => 'le-club',
                'title' => 'Notre histoire',
                'slug' => 'notre-histoire',
                'short_description' => 'Du Club Nautique Bressuirais de 1954 au CNBB d\'aujourd\'hui : sept décennies de natation à Bressuire.',
                'long_description' => <<<'HTML'
<h2>Les débuts</h2>
<p>Le club est fondé le <strong>22 mars 1954</strong> sous le nom de <strong>Club Nautique Bressuirais</strong> ; sa déclaration paraît au Journal officiel du 12 mai 1954. Une école de sauvetage est créée en 1963 et le club est reconnu d'utilité publique en 1964.</p>

<h2>Le CNBB</h2>
<p>En <strong>1987</strong>, le club devient le <strong>Cercle des Nageurs du Bocage Bressuirais</strong>. À la fin des années 1980, il lance l'aquagym et les « 12 heures de natation ». Il compte 504 adhérents en 1994 et 580 en 1998.</p>

<h2>Des années 2000 à aujourd'hui</h2>
<p>De 2001 à 2011, une section de natation synchronisée est animée par Emmanuelle Babin. Le club fête ses 50 ans en 2004. En 2009, le centre aquatique Cœur d'O ouvre ses portes. Le 12 juillet 2013, le club organise le premier Aquathlon du Bocage.</p>
<p>En 2015-2016, le club réunit plus de 200 licenciés, dont 9 nageurs de niveau régional, et ouvre des sections sport adapté et triathlon.</p>
<p>Depuis 2018, le club est présidé par Sébastien Chevalier.</p>
HTML,
            ],
            [
                'category' => 'vie-associative',
                'title' => 'S\'engager au club',
                'slug' => 's-engager-au-club',
                'short_description' => 'Officiels, bénévoles, membres du comité directeur : le club a besoin de chacun.',
                'long_description' => <<<'HTML'
<p>Le CNBB est une association : son fonctionnement repose sur des bénévoles.</p>
<h2>Officiels</h2>
<p>Chaque compétition FFN a besoin d'officiels. Les parents de nageurs sont invités à se former pour accompagner le club au bord des bassins.</p>
<h2>Comité directeur</h2>
<p>Le comité directeur prend les décisions de l'association et organise la saison. Les parents qui le souhaitent peuvent le rejoindre.</p>
<h2>Coups de main ponctuels</h2>
<p>Événements, déplacements, buvette : toute aide, même occasionnelle, est précieuse.</p>
<p>Pour vous proposer : <a href="mailto:cnbb079@gmail.com">cnbb079@gmail.com</a>.</p>
HTML,
            ],
            [
                'category' => 'questions-frequentes',
                'title' => 'Questions fréquentes sur l\'inscription',
                'slug' => 'questions-frequentes-inscription',
                'short_description' => 'Niveau requis, certificat médical, paiement, vacances : les réponses aux questions des familles.',
                'long_description' => <<<'HTML'
<h2>Faut-il savoir nager pour s'inscrire ?</h2>
<p>Oui : l'adhésion est réservée aux personnes sachant nager 25 mètres. Une évaluation peut être proposée lors d'une première adhésion.</p>

<h2>Un certificat médical est-il obligatoire ?</h2>
<p>Pour une première adhésion, oui. Les adultes le renouvellent tous les 3 ans ; pour les mineurs, une attestation (CERFA n° 15699-01) suffit.</p>

<h2>Peut-on payer en plusieurs fois ?</h2>
<p>Oui, en 1 à 4 chèques encaissés le 15 septembre, le 15 octobre, le 15 novembre et le 15 décembre. Le club accepte aussi les espèces, le virement, les coupons sport et les chèques-vacances ANCV (avant le 30 octobre).</p>

<h2>Y a-t-il des réductions pour les familles ?</h2>
<p>10 € de remise pour 2 adhésions, 10 % à partir de 3 adhésions, en début de saison.</p>

<h2>Le club fonctionne-t-il pendant les vacances scolaires ?</h2>
<p>Non, les activités s'arrêtent pendant les vacances scolaires.</p>

<h2>Créer un compte sur le site, est-ce s'inscrire au club ?</h2>
<p>Non. Le compte donne accès au site ; l'adhésion se fait par le dossier d'inscription déposé au club.</p>
HTML,
            ],
        ];

        $order = [];
        foreach ($pages as $p) {
            $order[$p['category']] = ($order[$p['category']] ?? 0) + 1;

            Page::create([
                'title' => $p['title'],
                'slug' => $p['slug'],
                'short_description' => $p['short_description'],
                'long_description' => trim($p['long_description']),
                'image' => null,
                'visibility' => 'public',
                'is_published' => true,
                'sort_order' => $order[$p['category']],
                'pages_category_id' => $cat($p['category'])->id,
                'meta_title' => mb_strimwidth($p['title'], 0, 55, '…') . ' - CNBB',
                'meta_keywords' => 'CNBB, club de natation, Bressuire',
                'meta_description' => mb_strimwidth($p['short_description'], 0, 160, '…'),
                'created_by' => $admin?->id,
                'created_by_name' => $admin?->name,
                'updated_by' => $admin?->id,
                'published_at' => now()->subDays(60),
            ]);
            $this->command->info("✅ Page : {$p['title']}");
        }

        $this->command->info('🎉 PagesSeeder : ' . count($pages) . ' pages créées');
    }
}

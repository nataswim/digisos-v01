<?php

namespace Database\Seeders;

use App\Support\ClubGroupes;
use Database\Seeders\Concerns\CreatesPages;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Pages « Nos groupes » : le parcours du nageur, une page par groupe et la section Maîtres.
 * Source : CNBB-organisation-2026-2027.docx (version du club du 12/10/2026).
 *
 * Les données des groupes (prérequis, horaires, couloirs) sont dans App\Support\ClubGroupes :
 * modifier un horaire là-bas, puis relancer : php artisan db:seed --class=GroupesPagesSeeder
 * À lancer après PagesCategoriesSeeder et PagesSeeder.
 */
class GroupesPagesSeeder extends Seeder
{
    use CreatesPages;


    /** Règles de calendrier selon le type de groupe. */
    private const VACANCES_LOISIR = 'Pas d\'entraînement pendant les vacances scolaires, sauf information spécifique et convocations.';
    private const VACANCES_COMPET = 'Des stages sont organisés pendant les vacances scolaires, à des horaires différents de la période scolaire. Les horaires sont communiqués individuellement : seuls les nageurs convoqués participent.';

    private const MATERIEL_BASE    = 'maillot de bain, serviette, lunettes de natation (obligatoires), bonnet du club (obligatoire, offert à la première adhésion)';
    private const MATERIEL_LIGUE   = self::MATERIEL_BASE . ', planche, pull-buoy, tuba frontal, plaquettes, short et tee-shirt pour la préparation physique à sec';
    private const MATERIEL_MAITRES = self::MATERIEL_BASE . ', planche, pull-buoy, tuba frontal, plaquettes';

    public function run(): void
    {
        $pages = [$this->parcours()];

        foreach (ClubGroupes::GROUPES as $g) {
            $pages[] = [
                'category' => 'nos-groupes',
                'title' => "Groupe {$g['titre']}",
                'slug' => $g['slug'],
                'short_description' => "{$g['section']} — {$g['age']}. {$g['resume']}",
                'long_description' => $this->pageGroupe($g),
            ];
        }

        $pages[] = $this->maitres();

        $n = $this->createPages($pages);
        $this->command->info("🎉 GroupesPagesSeeder : {$n} pages créées");
    }

    // ------------------------------------------------------------------ pages

    private function pageGroupe(array $g): string
    {
        $h = [];
        $intro = "<p><strong>{$g['section']}" . ($g['surnom'] ? " — groupe « {$g['surnom']} »" : '') . "</strong> · {$g['age']}</p>";
        $h[] = $intro . "\n<p>{$g['resume']}</p>";

        if ($g['section'] === 'Jeunesse') {
            $h[] = '<p>La section Jeunesse s\'adresse aux collégiens et lycéens qui recherchent une pratique sportive régulière, hors circuit de compétition officielle, avec une approche multi-activités (natation, sauvetage sportif, palmes-masque-tuba, water-polo). Elle peut intégrer une formation au secourisme ou une préparation diplômante selon le projet de chacun.</p>';
        }

        $h[] = "<h2>Prérequis</h2>\n" . $this->ul($g['prerequis']);

        if (! empty($g['engagement'])) {
            $h[] = "<h2>Engagement</h2>\n<p>{$g['engagement']}</p>";
        }
        if ($g['pour_qui']) {
            $h[] = "<h2>Pour qui ?</h2>\n<p>{$g['pour_qui']}</p>";
        }
        if ($g['apprentissages']) {
            $h[] = "<h2>Ce qu'on y apprend</h2>\n" . $this->ul($g['apprentissages']);
        }
        if (! empty($g['esprit'])) {
            $h[] = "<h2>Esprit des séances</h2>\n<p>{$g['esprit']}</p>";
        }

        $h[] = "<h2>Compétitions</h2>\n<p>{$g['competitions']}</p>";
        $h[] = "<h2>Entraînements</h2>\n<p>{$g['seances']}</p>\n" . $this->lienPlanning(Str::slug($g['titre']));
        $h[] = "<h2>Bon à savoir</h2>\n" . $this->ul([
            $g['type'] === 'compet' ? self::VACANCES_COMPET : self::VACANCES_LOISIR,
            'Pas d\'entraînement les jours fériés ni pendant les fermetures exceptionnelles du centre aquatique, sauf information contraire.',
            'Matériel : ' . ($g['section'] === 'Ligue' ? self::MATERIEL_LIGUE : self::MATERIEL_BASE) . '.',
            'Une évaluation est faite lors du premier cours et en cours d\'année : un changement de groupe peut être proposé selon le niveau et l\'assiduité.',
            $g['type'] === 'compet'
                ? 'Les nageurs de ce groupe respectent les <a href="/pages/regles-du-club/engagements-des-competiteurs">engagements des compétiteurs</a>.'
                : 'Accès au bassin sur présentation de la carte d\'accès valide : pas de carte, pas de séance.',
        ]);

        return implode("\n\n", $h);
    }

    /** Les horaires sont gérés dans le module Planning : la page renvoie vers le planning. */
    private function lienPlanning(string $slugGroupe): string
    {
        return '<p>Jours, horaires et couloirs (période scolaire et vacances scolaires) : '
            . '<a href="/groupes-et-horaires#groupe-' . $slugGroupe . '">voir le planning des entraînements</a>.</p>';
    }

    private function parcours(): array
    {
        $rows = implode("\n", array_map(fn ($g) => sprintf(
            '<tr><td>%s</td><td><a href="/pages/nos-groupes/%s">%s</a></td><td>%s</td><td>%s</td><td>%s</td></tr>',
            $g['section'], $g['slug'], $g['titre'], $g['age'], $g['cle'], $g['seances']
        ), ClubGroupes::GROUPES));

        $html = <<<HTML
<p>Au CNBB, le nageur progresse de section en section : <strong>École de natation</strong> → <strong>Jeunesse</strong> → <strong>Relève</strong> → <strong>Ligue</strong>. Les adultes nagent dans la <a href="/pages/nos-groupes/section-maitres">section Maîtres</a>.</p>
<p>Une <strong>évaluation</strong> est faite lors du premier cours, puis en cours d'année, pour vérifier que chacun est dans le bon groupe. Un changement de groupe peut être proposé à tout moment selon le niveau et l'assiduité.</p>

<h2>Tous les groupes</h2>
<table class="table">
<thead><tr><th>Section</th><th>Groupe</th><th>Âge</th><th>Prérequis principal</th><th>Entraînements</th></tr></thead>
<tbody>
{$rows}
<tr><td>Maîtres</td><td><a href="/pages/nos-groupes/section-maitres">Arc Atlantique, Aqua-Défis, Aqua-Masters</a></td><td>21 ans et plus</td><td>Nager 1 000 m en une heure</td><td>1 à 3 séances de 60 minutes par semaine.</td></tr>
</tbody>
</table>

<h2>Les étapes</h2>
<ol>
<li><strong>École de natation</strong> (Initiation Bleu et Vert, Tremplin Orange et Jaune) : apprendre les quatre nages et découvrir la natation sportive.</li>
<li><strong>Jeunesse</strong> (Promotion 1 et 2) : une pratique régulière pour les 12 ans et plus, hors circuit de compétition officielle.</li>
<li><strong>Relève</strong> (Relève 1 et 2) : apprendre à performer en compétition.</li>
<li><strong>Ligue</strong> (Pré-ligue, Ligue) : apprendre à s'entraîner, puis s'entraîner à performer.</li>
</ol>
<p>Les jours et horaires de chaque groupe sont sur la page <a href="/groupes-et-horaires">Groupes et horaires</a>.</p>
HTML;

        return [
            'category' => 'nos-groupes',
            'title' => 'Le parcours du nageur',
            'slug' => 'parcours-du-nageur',
            'short_description' => 'De l\'école de natation à la Ligue, et la section Maîtres pour les adultes : tous les groupes du club, leurs âges et leurs prérequis.',
            'long_description' => $html,
        ];
    }

    private function maitres(): array
    {
        $materiel = self::MATERIEL_MAITRES;
        $vacances = self::VACANCES_LOISIR;

        $html = <<<HTML
<p>La section Maîtres permet aux adultes, débutants ou confirmés, de s'entraîner, de progresser et de rester en forme.</p>

<h2>Prérequis communs</h2>
<ul>
<li>avoir 21 ans et plus (catégories C0 à C15)</li>
<li>pouvoir parcourir 1 000 m en une heure</li>
<li>maîtriser au moins deux nages sur 200 m</li>
</ul>

<h2>Les trois groupes</h2>
<table class="table">
<thead><tr><th>Groupe</th><th>Repère</th><th>Étape</th><th>Pour qui</th><th>Compétitions</th></tr></thead>
<tbody>
<tr><td>1 — Arc Atlantique</td><td>3 000 m en une heure, quatre nages</td><td>« S'entraîner, se stabiliser et performer » : base physiologique, consolidation technique, souplesse, vitesse</td><td>Compétiteurs du circuit Arc Atlantique (LNA, niveau ligue), nageurs et triathlètes confirmés, nageurs d'eau libre. Possibilité de s'entraîner avec le groupe Ligue selon le niveau et l'accord des entraîneurs.</td><td>Circuit Arc Atlantique (niveau ligue), eau libre</td></tr>
<tr><td>2 — Aqua-Défis « 2000 »</td><td>2 000 m en une heure, trois nages</td><td>« S'entraîner et progresser » : base technique et physiologique, consolidation des acquis</td><td>Compétiteurs du circuit Arc Atlantique (LNA, niveau départemental), nageurs en reprise, triathlètes, nageurs d'eau libre</td><td>Intraclub</td></tr>
<tr><td>3 — Aqua-Masters « 1500 »</td><td>1 500 m en une heure</td><td>« Nager autrement » : renforcer les bases techniques et faire évoluer ses acquis</td><td>Nageurs loisir, futurs triathlètes</td><td>Intraclub</td></tr>
</tbody>
</table>

<h2>Entraînements</h2>
<p>1 à 3 séances de 60 minutes par semaine. Jours et horaires : <a href="/groupes-et-horaires#groupe-maitres">voir le planning des entraînements</a>.</p>

<h2>Catégories d'âge FFN</h2>
<p>C1 (25 à 29 ans), C2 (30 à 34 ans), C3 (35 à 39 ans), C4 (40 à 44 ans), C5 (45 à 49 ans), C6 (50 à 54 ans), C7 (55 à 59 ans), C8 (60 à 64 ans), C9 (65 à 69 ans), C10 (70 à 74 ans), C11 (75 à 79 ans), C12 (80 à 84 ans), C13 (85 à 89 ans), C14 (90 à 94 ans), C15 (95 ans et plus).</p>

<h2>Bon à savoir</h2>
<ul>
<li>{$vacances}</li>
<li>Pas d'entraînement les jours fériés ni pendant les fermetures exceptionnelles du centre aquatique.</li>
<li>Matériel : {$materiel}.</li>
<li>Les nageurs Maîtres sont invités à rejoindre l'équipe des <a href="/pages/vie-associative/devenir-officiel">officiels du club</a>.</li>
</ul>
HTML;

        return [
            'category' => 'nos-groupes',
            'title' => 'Section Maîtres (adultes)',
            'slug' => 'section-maitres',
            'short_description' => 'Pour les adultes de 21 ans et plus, débutants ou confirmés : trois groupes, de l\'Arc Atlantique à l\'Aqua-Masters.',
            'long_description' => $html,
        ];
    }
}

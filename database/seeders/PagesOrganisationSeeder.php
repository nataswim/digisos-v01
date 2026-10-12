<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\CreatesPages;
use Illuminate\Database\Seeder;

/**
 * Pages tirées du document « CNBB-organisation-2026-2027 » (version du club du 12/10/2026) :
 * mission, règlement, engagements des compétiteurs, code de conduite, officiels, partenariat, FAQ.
 * À lancer après PagesCategoriesSeeder et PagesSeeder.
 */
class PagesOrganisationSeeder extends Seeder
{
    use CreatesPages;

    public function run(): void
    {
        $n = $this->createPages([
            $this->mission(),
            $this->reglement(),
            $this->engagements(),
            $this->codeDeConduite(),
            $this->officiels(),
            $this->partenaire(),
            $this->faq(),
        ]);

        $this->command->info("🎉 PagesOrganisationSeeder : {$n} pages créées");
    }

    private function mission(): array
    {
        return [
            'category' => 'le-club',
            'title' => 'Mission et objectifs',
            'slug' => 'mission-et-objectifs',
            'short_description' => 'Développer, promouvoir et soutenir la natation sportive et compétitive, en respectant le rythme de progression de chacun.',
            'long_description' => <<<'HTML'
<h2>Notre mission</h2>
<p>Le CNBB accueille :</p>
<ul>
<li>les <strong>jeunes à partir de 6 ans</strong>, de l'école de natation aux groupes de compétition ;</li>
<li>les <strong>adultes</strong> dans la section Maîtres, débutants ou confirmés ;</li>
<li>toutes celles et ceux qui souhaitent suivre un <strong>programme de natation sportive et compétitive</strong>,</li>
<li>encadrés par une équipe de bénévoles dynamique, dans un environnement collectif et convivial.</li>
</ul>
<p>Le club a pour but de développer, promouvoir et soutenir la natation sportive et compétitive, et de favoriser le développement de ses membres en respectant le rythme de progression de chacun.</p>

<h2>Pour chaque nageur</h2>
<ul>
<li>développer le goût de l'effort, la persévérance, la discipline et la confiance en soi ;</li>
<li>apprendre la valeur d'une progression pas à pas et le goût du dépassement de soi ;</li>
<li>développer le savoir-être et le savoir-vivre en groupe.</li>
</ul>

<h2>Pour le collectif</h2>
<ul>
<li>créer un esprit d'équipe et d'unité qui favorise une attitude positive chez les nageurs et nageuses, leurs parents, les bénévoles et les dirigeants ;</li>
<li>offrir aux parents et aux bénévoles un cadre qui leur donne envie de s'impliquer.</li>
</ul>
HTML,
        ];
    }

    private function reglement(): array
    {
        return [
            'category' => 'regles-du-club',
            'title' => 'Règlement de fonctionnement',
            'slug' => 'reglement-de-fonctionnement',
            'short_description' => 'Calendrier, accès au bassin et aux vestiaires, encadrement des séances, responsabilité et équipement : les règles communes à tous les groupes.',
            'long_description' => <<<'HTML'
<h2>Calendrier</h2>
<ul>
<li><strong>Pas d'entraînement</strong> les jours fériés ni pendant les fermetures exceptionnelles du centre aquatique, sauf information contraire affichée ou envoyée par courriel ou SMS.</li>
<li><strong>École de natation, Jeunesse et Maîtres</strong> : pas d'entraînement pendant les vacances scolaires, sauf information spécifique et convocations.</li>
<li><strong>Relève, Pré-ligue et Ligue</strong> : des stages sont organisés pendant les vacances scolaires, à des horaires différents de la période scolaire. Les horaires sont communiqués individuellement aux nageurs concernés ; <strong>seuls les nageurs convoqués participent</strong>.</li>
</ul>

<h2>Accès au bassin et aux vestiaires</h2>
<ul>
<li>L'accès au bassin se fait uniquement sur présentation de la <strong>carte d'accès valide pour la saison</strong> : pas de carte, pas de séance.</li>
<li>Les vestiaires sont accessibles <strong>10 minutes avant</strong> le créneau ; après la séance, les nageurs disposent de <strong>15 minutes</strong> pour en sortir.</li>
<li>Le nageur et, pour un mineur, son responsable légal s'engagent à utiliser les vestiaires de façon responsable et respectueuse.</li>
</ul>

<h2>Encadrement des séances</h2>
<ul>
<li>Conformément au règlement du centre aquatique, toutes les séances sont obligatoirement encadrées.</li>
<li>Le club se réserve le droit d'annuler une séance si <strong>4 nageurs ou moins</strong> sont présents au bord du bassin.</li>
</ul>

<h2>Responsabilité</h2>
<p>Le club décline toute responsabilité en cas de perte ou de vol dans l'enceinte du centre aquatique (abords extérieurs, circulations, hall, vestiaires).</p>

<h2>Équipement</h2>
<table class="table">
<thead><tr><th>Pour qui</th><th>Équipement</th></tr></thead>
<tbody>
<tr><td>Tous les nageurs</td><td>Maillot de bain, serviette, lunettes de natation (obligatoires), bonnet du club (obligatoire)</td></tr>
<tr><td>Pré-ligue et Ligue</td><td>En plus : planche, pull-buoy, tuba frontal, plaquettes, short et tee-shirt pour la préparation physique à sec</td></tr>
<tr><td>Maîtres</td><td>En plus : planche, pull-buoy, tuba frontal, plaquettes</td></tr>
</tbody>
</table>
<p><strong>Bonnet du club</strong> : offert à la première adhésion, puis 7 € pour les adhérents et 10 € pour les supporters.</p>
HTML,
        ];
    }

    private function engagements(): array
    {
        return [
            'category' => 'regles-du-club',
            'title' => 'Engagements des compétiteurs',
            'slug' => 'engagements-des-competiteurs',
            'short_description' => 'Les règles que s\'engagent à respecter les nageurs des groupes Relève 1, Relève 2, Pré-ligue et Ligue.',
            'long_description' => <<<'HTML'
<p>Ces règles s'appliquent aux groupes <strong>Relève 1, Relève 2, Pré-ligue et Ligue</strong>.</p>
<ol>
<li>Le nageur participe aux compétitions auxquelles il est engagé.</li>
<li>Il confirme sa participation et son moyen de transport <strong>au plus tard 4 jours avant</strong> la compétition.</li>
<li>La participation aux <strong>compétitions par équipes</strong> est obligatoire.</li>
<li>Tout refus non motivé ou toute absence non justifiée après l'engagement par le club entraîne le <strong>remboursement des frais d'engagement</strong> payés par le club.</li>
</ol>
<p>Les nageurs de la Pré-ligue et de la Ligue s'engagent en plus à participer à au moins <strong>75 % des entraînements</strong> et à au moins <strong>80 % des compétitions</strong> prévues au calendrier.</p>
HTML,
        ];
    }

    private function codeDeConduite(): array
    {
        $items = [
            ['Engagement', 'J\'accepte que le but de la natation soit de développer tout mon potentiel, et je suis prêt à fournir les efforts nécessaires, à l\'entraînement comme en compétition.'],
            ['Attitude positive', 'J\'arrive à l\'entraînement prêt à apprendre et à travailler, et je respecte les consignes de mes entraîneurs.'],
            ['Respect', 'Je témoigne respect, soutien et reconnaissance aux nageurs et nageuses, aux entraîneurs, au personnel de la piscine et aux bénévoles du club.'],
            ['Esprit d\'équipe', 'J\'encourage mes coéquipiers, je les traite avec courtoisie et j\'entretiens en tout temps des relations respectueuses.'],
            ['Tolérance zéro pour la violence', 'Je contribue à un environnement sans violence physique, intimidation ni harcèlement, et j\'évite tout langage injurieux ou geste offensant envers toute personne du club ou du centre aquatique.'],
            ['Respect envers tous', 'Je respecte les autres nageurs, les entraîneurs, les bénévoles et les supporters, au bord du bassin comme en dehors, y compris sur les réseaux sociaux.'],
            ['Discipline', 'Je fais preuve de discipline et de fair-play, dans la réussite comme dans l\'échec.'],
            ['Dialogue avec l\'entraîneur', 'Je travaille en lien étroit avec mon entraîneur et je le préviens en cas de douleur, de malaise ou de tout autre problème.'],
            ['Respect des consignes', 'Je respecte les consignes des entraîneurs et, en compétition, celles des officiels.'],
            ['Respect des règles', 'Je respecte les règlements en vigueur (club, centre aquatique, Fédération Française de Natation) ainsi que les orientations et décisions du club.'],
            ['Image du club', 'Je veille à ce que mon comportement ne nuise pas à son image.'],
            ['Responsabilité collective', 'Je participe à l\'installation et au rangement du matériel, en respectant les équipements et les installations.'],
            ['Vie du club', 'Je participe aux activités organisées par le club (moments conviviaux, animations…) et je respecte mes engagements.'],
        ];

        $liste = implode("\n", array_map(fn ($i) => "<li><strong>{$i[0]}</strong> — {$i[1]}</li>", $items));

        return [
            'category' => 'regles-du-club',
            'title' => 'Code de conduite',
            'slug' => 'code-de-conduite',
            'short_description' => 'Les 13 engagements de chaque membre du CNBB : respect, esprit d\'équipe, discipline et tolérance zéro pour la violence.',
            'long_description' => "<p>En tant que membre du CNBB, je m'engage à respecter les principes suivants.</p>\n<ol>\n{$liste}\n</ol>\n"
                . '<p>Le code de conduite encadre le comportement attendu des nageurs, des parents et des bénévoles.</p>',
        ];
    }

    private function officiels(): array
    {
        return [
            'category' => 'vie-associative',
            'title' => 'Devenir officiel',
            'slug' => 'devenir-officiel',
            'short_description' => 'Sans officiels, aucune compétition n\'est possible. Parents et nageurs Maîtres : la formation est courte et accessible.',
            'long_description' => <<<'HTML'
<p><strong>L'implication des parents et des nageurs Maîtres est la clé du succès du club.</strong> Devenir officiel, c'est s'impliquer directement dans la vie des compétitions et contribuer, au sein d'une équipe, à leur réussite.</p>

<h2>Un rôle indispensable</h2>
<ul>
<li><strong>Sans officiels, aucune compétition n'est possible.</strong> Chaque compétition, quel que soit son niveau, repose sur une équipe nombreuse de bénévoles (30 en moyenne).</li>
<li>L'engagement d'un officiel étant souvent lié à la présence de son enfant dans le club, il faut <strong>renouveler l'équipe en permanence</strong>.</li>
</ul>
<p>Le CNBB a besoin de bénévoles pour maintenir une équipe d'officiels forte et compétente, qui le représente dignement lors de ses compétitions et à l'extérieur.</p>

<h2>C'est facile de s'impliquer</h2>
<p>La formation initiale demande très peu de temps. Elle peut se faire :</p>
<ul>
<li>pendant une compétition, aux côtés d'officiels expérimentés ;</li>
<li>en ligne, par une pré-formation à distance.</li>
</ul>

<h2>Les postes</h2>
<p>Les fonctions d'officiel sont réparties en niveaux (C, B, A) selon les formations suivies et l'expérience acquise en compétition.</p>

<p><strong>Intéressé ?</strong> Parlez-en aux entraîneurs ou écrivez à <a href="mailto:cnbb079@gmail.com">cnbb079@gmail.com</a>.</p>
HTML,
        ];
    }

    private function partenaire(): array
    {
        return [
            'category' => 'vie-associative',
            'title' => 'Devenir partenaire',
            'slug' => 'devenir-partenaire',
            'short_description' => 'Entreprises et particuliers : soutenez la réussite et la formation des jeunes nageurs par le sponsoring, le mécénat ou un don.',
            'long_description' => <<<'HTML'
<p>Le CNBB propose un plan de partenariat aux entreprises et aux particuliers qui souhaitent soutenir la réussite et la formation des jeunes nageurs du Bocage Bressuirais.</p>

<h2>Trois façons de soutenir le club</h2>
<ul>
<li><strong>Sponsoring</strong> : associez votre entreprise à l'image du club.</li>
<li><strong>Mécénat</strong> : soutenez un projet du club (formation des jeunes, déplacements en compétition, matériel).</li>
<li><strong>Don</strong> : chaque contribution aide le club à accueillir et former ses nageurs.</li>
</ul>

<h2>Recevoir le plan de partenariat</h2>
<p>Contactez le club à <a href="mailto:cnbb079@gmail.com">cnbb079@gmail.com</a>, au <a href="tel:+33602350843">06 02 35 08 43</a>, ou par le <a href="/contact">formulaire de contact</a> en choisissant « Partenariat, bénévolat ».</p>
HTML,
        ];
    }

    private function faq(): array
    {
        $themes = [
            'Le club' => [
                ['Qu\'est-ce que le CNBB ?', 'Le Cercle des Nageurs du Bocage Bressuirais est un club de natation affilié à la Fédération Française de Natation, fondé en 1954. Il accueille les jeunes à partir de 6 ans et les adultes (section Maîtres) dans un programme de natation sportive. Son objectif : développer, promouvoir et soutenir la natation compétitive dans un environnement convivial, en cultivant le goût de l\'effort, la persévérance, la discipline et la confiance en soi.'],
                ['Où le club s\'entraîne-t-il ?', 'Au centre aquatique Cœur d\'O, à Bressuire. Les horaires de chaque groupe sont précisés sur sa page (rubrique « Nos groupes »).'],
                ['Le club propose-t-il un programme pour les adultes ?', 'Oui : la <a href="/pages/nos-groupes/section-maitres">section Maîtres</a> accueille les adultes, débutants ou confirmés, qui veulent s\'entraîner, progresser et rester en forme.'],
                ['Y a-t-il des activités l\'été ?', 'Oui : des stages techniques par groupes d\'une demi-journée et des entraînements individuels. Les dates sont publiées sur le site.'],
            ],
            'Niveaux et groupes' => [
                ['Quels sont les niveaux proposés ?', 'Le parcours suit une progression : École de natation (Initiation Bleu, Initiation Vert, Tremplin Orange, Tremplin Jaune) → Jeunesse (Promotion 1 et 2) → Relève (Relève 1 et 2) → Ligue (Pré-ligue, Ligue). Les adultes nagent dans la section Maîtres. Voir <a href="/pages/nos-groupes/parcours-du-nageur">le parcours du nageur</a>.'],
                ['Quels sont les prérequis pour entrer en Initiation ?', 'Avoir entre 6 et 9 ans, être à l\'aise dans l\'eau, nager 25 m sans aide à la flottaison et avoir envie de pratiquer la natation sportive.'],
                ['Les nageurs participent-ils à des compétitions ?', 'Oui, selon leur niveau. Les groupes de l\'école de natation participent à des rencontres intraclub non officielles ; à partir de la Relève, les nageurs participent aux compétitions officielles de la Fédération Française de Natation.'],
            ],
            'Inscription' => [
                ['Comment inscrire mon enfant (ou m\'inscrire) ?', 'Faire une demande puis déposer le dossier d\'inscription complet dans la boîte aux lettres du club, derrière l\'abribus en bas du centre aquatique Cœur d\'O. Il est possible de s\'inscrire en cours d\'année, mais il est préférable de le faire avant le début de la saison (pour les anciens). Consultez les prérequis des groupes avant de vous inscrire.'],
                ['La licence fédérale est-elle obligatoire ?', 'Oui : la licence de la Fédération Française de Natation est obligatoire pour tous les nageurs. Elle est incluse dans l\'adhésion (61,20 € pour 2026-2027).'],
                ['Où acheter le bonnet du club ?', 'Auprès du club. Il est offert à la première adhésion, puis vendu 7 € aux adhérents et 10 € aux supporters. Il est obligatoire à l\'entraînement.'],
            ],
            'Vie du club' => [
                ['Où consulter le code de conduite ?', 'Sur la page <a href="/pages/regles-du-club/code-de-conduite">Code de conduite</a>. Il précise le comportement attendu des nageurs, des parents et des bénévoles.'],
                ['Comment devenir partenaire du club ?', 'Le CNBB propose un plan de partenariat aux entreprises et aux particuliers (sponsoring, mécénat et dons). Voir la page <a href="/pages/vie-associative/devenir-partenaire">Devenir partenaire</a>.'],
                ['Le club est-il présent sur les réseaux sociaux ?', 'Oui, sur Facebook et Instagram, où sont partagés les nouvelles, les résultats et les photos.'],
            ],
            'Nous joindre' => [
                ['Comment contacter le club ?', 'Par courriel à <a href="mailto:cnbb079@gmail.com">cnbb079@gmail.com</a>, par téléphone au <a href="tel:+33602350843">06 02 35 08 43</a>, ou avec le <a href="/contact">formulaire de contact</a>.'],
                ['J\'ai une question sur un groupe, un horaire ou une place disponible.', 'Écrivez directement au club en précisant le nom et l\'âge du nageur et le groupe visé.'],
            ],
        ];

        $html = [];
        foreach ($themes as $theme => $questions) {
            $html[] = "<h2>{$theme}</h2>";
            foreach ($questions as [$q, $r]) {
                $html[] = "<h3>{$q}</h3>\n<p>{$r}</p>";
            }
        }

        return [
            'category' => 'questions-frequentes',
            'title' => 'Questions fréquentes',
            'slug' => 'faq',
            'short_description' => 'Le club, les groupes, l\'inscription, la licence, le bonnet, le code de conduite : les réponses aux questions des familles et des nageurs.',
            'long_description' => implode("\n\n", $html),
        ];
    }
}

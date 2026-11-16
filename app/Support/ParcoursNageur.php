<?php

namespace App\Support;

/**
 * Contenu de la page interactive « Le parcours du nageur » (/parcours-du-nageur).
 *
 * Les cinq étapes reprennent le Parcours de développement de l'athlète (PDLTA)
 * de la Fédération Française de Natation : https://www.ffnatation.fr/PDLTA-main/
 * Les groupes reprennent les âges et les séances du planning du club (saison 2026-2027).
 *
 * Les créneaux affichés sur la page ne sont PAS ici : ils viennent du module Planning
 * (administration), via PublicParcoursController.
 */
class ParcoursNageur
{
    public const SOURCE_FFN = 'https://www.ffnatation.fr/PDLTA-main/';

    /** Les cinq étapes du PDLTA. */
    public const ETAPES = [
        1 => [
            'nom' => 'Je découvre',
            'public' => 'Aisance aquatique (avant 6 ans)',
            'scolaire' => 'Avant 6 ans',
            'pratique' => '—',
            'cap' => 'Devenir nageur',
            'intention' => 'Découvrir et explorer le milieu aquatique. Acquérir de l\'aisance et de la confiance dans l\'eau.',
            'periode' => 'À cette période, on découvre que l\'eau est un espace de jeu, de liberté et de sensations nouvelles. Tout commence par le plaisir d\'y retourner.',
            'idees' => [
                'Se sentir à l\'aise dans l\'eau est le premier apprentissage.',
                'Entrer dans l\'eau et en sortir seul : l\'autonomie aquatique se construit.',
                'Un enfant qui aime sa pratique progressera toujours mieux qu\'un enfant qui la subit.',
            ],
            'themes' => ['Fondamentaux du déplacement', 'Explorer et construire la confiance', 'Prévention des noyades'],
            'repartition' => null,
            'volumes' => [],
        ],
        2 => [
            'nom' => 'Je m\'approprie',
            'public' => 'École primaire',
            'scolaire' => 'Primaire (6–11 ans)',
            'pratique' => '1 à 4 ans',
            'cap' => 'Devenir nageur',
            'intention' => 'S\'approprier les fondamentaux techniques, développer sa motricité et construire les bases de sa pratique aquatique.',
            'periode' => 'À cette période, on s\'approprie les fondamentaux du déplacement aquatique. Les nages prennent forme, les repères dans l\'eau s\'installent durablement.',
            'idees' => [
                'Ce qu\'on apprend maintenant, on ne l\'oubliera jamais.',
                'Toutes les nages prennent forme, la course complète s\'enchaîne.',
                'La pratique régulière structure le quotidien et crée des liens durables.',
            ],
            'themes' => ['Fondamentaux du déplacement', 'Les 4 nages', 'Apprentissage athlétique'],
            'repartition' => ['deplacement' => 50, 'endurance' => 17, 'non_nagees' => 17, 'course' => 17, 'physique' => 0],
            'volumes' => [
                ['6-7 ans', '1 à 2 séances', '1 h à 3 h'],
                ['8-9 ans', '2 à 3 séances', '3 h à 4 h 30'],
                ['10-11 ans', '3 à 4 séances', '4 h 30 à 6 h'],
            ],
            'note' => 'Garder un équilibre entre la natation et d\'autres activités sportives, pour une motricité riche et éviter la spécialisation précoce.',
        ],
        3 => [
            'nom' => 'Je m\'organise',
            'public' => 'Collège',
            'scolaire' => 'Collège (11–15 ans)',
            'pratique' => '3 à 7 ans',
            'cap' => 'Meilleur nageur',
            'intention' => 'Maîtriser les variations de vitesse, s\'adapter aux transformations physiques, développer efficacité et gestion de l\'effort.',
            'periode' => 'À cette période, on s\'organise pour nager plus efficacement, mieux dépenser son énergie, construire des appuis solides, et trouver son rythme entre l\'école et l\'entraînement.',
            'idees' => [
                'Le corps change, les résultats peuvent stagner : c\'est normal et attendu.',
                'Nager plus vite sans dépenser plus d\'énergie : c\'est l\'enjeu central.',
                'Jongler entre les entraînements et le collège, ça aussi ça s\'apprend.',
            ],
            'themes' => ['Organiser son déplacement', 'Structuration de la posture d\'athlète', 'Formation athlétique'],
            'repartition' => ['deplacement' => 35, 'endurance' => 40, 'non_nagees' => 5, 'course' => 10, 'physique' => 10],
            'volumes' => [
                ['12-13 ans garçons', '4 à 5 séances', '7 h 30 à 9 h'],
                ['12-13 ans filles', '5 à 6 séances', '9 h 30 à 11 h'],
                ['14-15 ans garçons', '6 à 7 séances', '11 h à 12 h 30'],
                ['14-15 ans filles', '7 à 8 séances', '13 h 30 à 15 h'],
            ],
        ],
        4 => [
            'nom' => 'Je m\'affirme',
            'public' => 'Lycée',
            'scolaire' => 'Lycée (15–18 ans)',
            'pratique' => '6 à 10 ans',
            'cap' => 'Meilleur nageur',
            'intention' => 'Mettre les compétences à l\'épreuve de la vitesse et de la durée, gérer la pression, profiter de la maturation physique.',
            'periode' => 'À cette période, on s\'affirme en compétition. La puissance physique grandit, les projets prennent forme, le nageur construit sa propre identité sportive.',
            'idees' => [
                'Nager avec intention : chaque longueur a un objectif.',
                'Se spécialiser et construire sa propre stratégie de course.',
                'Le projet sportif devient personnel : c\'est le jeune qui en est l\'acteur.',
            ],
            'themes' => ['Maîtriser ses allures', 'Développement de la posture d\'athlète', 'Développement athlétique'],
            'repartition' => ['deplacement' => 25, 'endurance' => 40, 'non_nagees' => 5, 'course' => 15, 'physique' => 15],
            'volumes' => [
                ['16-17 ans filles', '9 à 10 séances', '18 h à 20 h'],
                ['16-18 ans garçons', '7 à 9 séances', '16 h à 20 h'],
                ['18 ans filles', '10 à 11 séances', '20 h à 22 h'],
            ],
        ],
        5 => [
            'nom' => 'J\'excelle',
            'public' => 'Post-bac',
            'scolaire' => 'Post-bac (18 ans et plus)',
            'pratique' => '10 ans et plus',
            'cap' => 'Toujours meilleur nageur',
            'intention' => 'Prendre du recul sur ses performances, chercher des axes d\'amélioration précis, utiliser des outils perfectionnés.',
            'periode' => 'À cette période, on se perfectionne en interrogeant chaque détail. La différence ne se fait plus sur le volume, mais sur la qualité, la connaissance de soi et l\'autonomie.',
            'idees' => [
                'Se connaître soi-même devient le premier outil de progression.',
                'Les progrès se jouent sur des détails que seul l\'athlète peut sentir.',
                'Réussir ses études et progresser sportivement, ce n\'est pas incompatible.',
            ],
            'themes' => ['Se préparer à la compétition', 'Autonomie et expertise dans la posture d\'athlète', 'Individualisation athlétique'],
            'repartition' => ['deplacement' => 20, 'endurance' => 35, 'non_nagees' => 5, 'course' => 25, 'physique' => 15],
            'volumes' => [
                ['19 ans et plus', '8 à 11 séances', '16 h à 22 h'],
            ],
        ],
    ];

    /** Thèmes de la répartition de l'entraînement : [libellé, couleur]. */
    public const THEMES = [
        'deplacement' => ['Déplacement efficace', '#0e7490'],
        'endurance'   => ['Endurance', '#1d6fb8'],
        'non_nagees'  => ['Parties non nagées', '#efa525'],
        'course'      => ['S\'entraîner à la course', '#c2410c'],
        'physique'    => ['Préparation physique', '#4b5563'],
    ];

    /**
     * Groupes du club, dans l'ordre du parcours.
     * cle      : début du slug du groupe dans le module Planning (sert à retrouver ses créneaux)
     * etapes   : étapes FFN couvertes par le groupe
     * min/max  : âges, pour l'outil « Trouver mon groupe »
     * page     : article du groupe (pages « Nos groupes »)
     */
    public const GROUPES = [
        ['cle' => 'initiation-bleu', 'nom' => 'Initiation Bleu', 'section' => 'École de natation', 'etapes' => [1, 2], 'min' => 6, 'max' => 10, 'age' => '6 à 10 ans', 'prerequis' => 'nager 25 m sans aide', 'seances' => '1 à 2 × 45 min', 'competition' => false, 'resume' => 'Premier groupe de l\'école de natation : glisser, respirer dans l\'eau, premières nages.', 'page' => 'groupe-initiation-bleu'],
        ['cle' => 'initiation-vert', 'nom' => 'Initiation Vert', 'section' => 'École de natation', 'etapes' => [2], 'min' => 6, 'max' => 10, 'age' => '6 à 10 ans', 'prerequis' => 'niveau Bleu, nager 50 m', 'seances' => '1 à 2 × 45 min', 'competition' => false, 'resume' => '50 m de nage et la coordination des mouvements.', 'page' => 'groupe-initiation-vert'],
        ['cle' => 'tremplin-orange', 'nom' => 'Tremplin Orange', 'section' => 'École de natation', 'etapes' => [2], 'min' => 8, 'max' => 10, 'age' => '8 à 10 ans', 'prerequis' => 'niveau Vert, 50 m en deux nages', 'seances' => '1 à 2 × 45 min', 'competition' => false, 'resume' => 'Les quatre nages prennent forme, la course complète s\'enchaîne.', 'page' => 'groupe-tremplin-orange'],
        ['cle' => 'tremplin-jaune', 'nom' => 'Tremplin Jaune', 'section' => 'École de natation', 'etapes' => [2], 'min' => 8, 'max' => 11, 'age' => '8 à 11 ans', 'prerequis' => 'niveau Orange, 75 m en trois nages', 'seances' => '1 à 2 × 45 min', 'competition' => true, 'resume' => 'Dernière étape de l\'école de natation, premières rencontres départementales.', 'page' => 'groupe-tremplin-jaune'],
        ['cle' => 'releve-1', 'nom' => 'Relève 1', 'section' => 'Relève', 'etapes' => [2, 3], 'min' => 9, 'max' => 12, 'age' => '9 à 12 ans', 'prerequis' => 'crawl et dos 200 m, brasse 50 m', 'seances' => '2 à 3 × 60 min', 'competition' => true, 'resume' => 'Premier groupe de compétiteurs : apprendre à performer sur les quatre nages.', 'page' => 'groupe-releve-1'],
        ['cle' => 'releve-2', 'nom' => 'Relève 2', 'section' => 'Relève', 'etapes' => [3], 'min' => 10, 'max' => 12, 'age' => '10 à 12 ans', 'prerequis' => '400 m crawl, 200 m 4 nages', 'seances' => '2 à 3 × 60 min', 'competition' => true, 'resume' => 'Nager plus vite sans dépenser plus d\'énergie.', 'page' => 'groupe-releve-2'],
        ['cle' => 'promotion-2', 'nom' => 'Promotion 2', 'section' => 'Jeunesse', 'etapes' => [3], 'min' => 12, 'max' => 15, 'age' => 'collège, 12 à 15 ans', 'prerequis' => 'crawl et dos 50 m', 'seances' => '1 à 2 × 50 min', 'competition' => false, 'resume' => 'Tous les fondamentaux, sans pression de compétition.', 'page' => 'groupe-promotion-2'],
        ['cle' => 'pre-ligue', 'nom' => 'Pré-ligue', 'section' => 'Ligue', 'etapes' => [3], 'min' => 12, 'max' => 18, 'age' => '12 à 18 ans', 'prerequis' => '400 m crawl, 100 m 4 nages', 'seances' => '3 × 60 min', 'competition' => true, 'resume' => 'Apprendre à s\'entraîner : principes d\'entraînement, stratégies de course, confiance en soi.', 'page' => 'groupe-pre-ligue'],
        ['cle' => 'promotion-1', 'nom' => 'Promotion 1', 'section' => 'Jeunesse', 'etapes' => [4], 'min' => 15, 'max' => 18, 'age' => 'lycée, 15 à 18 ans', 'prerequis' => 'crawl et dos 50 m', 'seances' => '1 à 2 × 50 min', 'competition' => false, 'resume' => 'Les quatre nages, les départs et les virages, pour nager bien et longtemps.', 'page' => 'groupe-promotion-1'],
        ['cle' => 'ligue', 'nom' => 'Ligue', 'section' => 'Ligue', 'etapes' => [4, 5], 'min' => 13, 'max' => 99, 'age' => '13 ans et plus', 'prerequis' => 'Pré-ligue ou Relève 2 validée', 'seances' => '3 à 5 × 90 min', 'competition' => true, 'resume' => 'Le groupe phare du club : s\'entraîner à performer, en mode projet.', 'page' => 'groupe-ligue'],
        ['cle' => 'maitres', 'nom' => 'Maîtres', 'section' => 'Maîtres', 'etapes' => [5], 'min' => 21, 'max' => 99, 'age' => '21 ans et plus', 'prerequis' => 'nager 1 000 m en une heure', 'seances' => '1 à 3 × 60 min', 'competition' => null, 'resume' => 'Trois groupes pour les adultes : Arc Atlantique, Aqua-Défis « 2000 », Aqua-Masters « 1500 ».', 'page' => 'section-maitres'],
        ['cle' => 'par-natation', 'nom' => 'Par-Natation FFSA FFH', 'section' => 'Par-Natation', 'etapes' => [], 'min' => 0, 'max' => 99, 'age' => 'tous âges', 'prerequis' => 'situation de handicap', 'seances' => '1 à 2 séances', 'competition' => null, 'resume' => 'Une pratique adaptée, au rythme de chacun, en lien avec la FFSA et la FFH.', 'page' => null],
    ];

    /** Couleur des sections (reprend celles du planning, plus Par-Natation). */
    public const COULEURS = [
        'École de natation' => '#17a2b8',
        'Jeunesse'          => '#198754',
        'Relève'            => '#efa525',
        'Ligue'             => '#2f80b8',
        'Maîtres'           => '#1c2111',
        'Par-Natation'      => '#7c3aed',
    ];

    /** Le rôle des parents (PDLTA, étapes 1 et 2). */
    public const PARENTS = [
        ['Préparation', 'Parler positivement de la séance avant d\'y aller.'],
        ['Ponctualité', 'Arriver à l\'heure pour respecter les rituels du groupe.'],
        ['Confiance', 'Ne pas intervenir pendant la séance : l\'éducateur est le référent.'],
        ['Présence', 'Rester visible mais à distance pour rassurer l\'enfant.'],
        ['Valorisation', 'Parler des progrès, même minimes, à la maison.'],
        ['Patience', 'Accepter que l\'apprentissage prenne du temps, et les retours en arrière.'],
        ['Communication', 'Informer l\'éducateur des peurs ou particularités de l\'enfant.'],
        ['Régularité', 'Des séances fréquentes permettent de progresser plus vite.'],
        ['Pas de pression', 'Ne pas comparer avec d\'autres enfants ni exiger des résultats.'],
        ['Hygiène de vie', 'Sommeil (10 à 13 h chez l\'enfant) et alimentation équilibrée.'],
    ];

    /** Groupes d'une étape. */
    public static function groupesDeLEtape(int $etape): array
    {
        return array_values(array_filter(self::GROUPES, fn ($g) => in_array($etape, $g['etapes'], true)));
    }
}

<?php

namespace App\Support;

/**
 * Groupes du CNBB et créneaux d'entraînement de la saison 2026-2027.
 * Source unique utilisée par :
 *   - la page « Groupes et horaires » (resources/views/public/schedule.blade.php)
 *   - GroupesPagesSeeder (pages « Nos groupes »)
 *
 * Pour changer un horaire : modifier ce fichier, puis relancer
 *   php artisan db:seed --class=GroupesPagesSeeder
 * (la page « Groupes et horaires » se met à jour toute seule).
 */
class ClubGroupes
{
    public const SAISON = '2026-2027';

    /** Jours affichés dans le planning, dans l'ordre. */
    public const JOURS = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

    /** Couleur de chaque section dans le planning : [fond, texte]. */
    public const COULEURS = [
        'École de natation' => ['#17a2b8', '#ffffff'],
        'Jeunesse'          => ['#198754', '#ffffff'],
        'Relève'            => ['#efa525', '#1c2111'],
        'Ligue'             => ['#2f80b8', '#ffffff'],
        'Maîtres'           => ['#1c2111', '#ffffff'],
    ];

    /**
     * Les groupes, dans l'ordre du parcours.
     * creneaux : [jour, horaire, espace]
     */
    public const GROUPES = [
        [
            'section' => 'École de natation',
            'titre' => 'Initiation Bleu',
            'surnom' => 'Découverte',
            'slug' => 'groupe-initiation-bleu',
            'cle' => 'Nager 25 m sans aide',
            'age' => '6 à 9 ans',
            'resume' => 'Premier groupe de l\'école de natation, pour les enfants de 6 à 9 ans qui nagent 25 m sans aide.',
            'prerequis' => ['avoir entre 6 et 9 ans', 'être à l\'aise dans l\'eau', 'nager 25 m sans aide à la flottaison', 'avoir envie de pratiquer la natation sportive'],
            'pour_qui' => 'Les enfants qui se déplacent sans aide en grande profondeur et parcourent au moins 25 m sur le ventre et sur le dos en utilisant les bras de façon rythmée.',
            'apprentissages' => ['les coulées en position hydrodynamique', 'les mouvements de jambes de brasse et de papillon', '25 m de crawl avec respiration sur le côté', '25 m de dos', 'le plongeon depuis le bord en position debout', 'les virages ouverts en crawl et en dos'],
            'competitions' => 'Rencontres intraclub non officielles.',
            'seances' => '1 à 2 séances de 45 minutes effectives par semaine.',
            'creneaux' => [['Mercredi', '15 h – 15 h 45', 'couloirs 4 et 5'], ['Samedi', '10 h – 10 h 45', 'couloirs 4 et 5']],
            'type' => 'loisir',
        ],
        [
            'section' => 'École de natation',
            'titre' => 'Initiation Vert',
            'surnom' => 'Découverte',
            'slug' => 'groupe-initiation-vert',
            'cle' => 'Niveau Bleu, nager 50 m',
            'age' => '6 à 9 ans',
            'resume' => 'Deuxième étape de l\'école de natation : 50 m de nage et la coordination des mouvements.',
            'prerequis' => ['avoir entre 6 et 9 ans', 'avoir validé le niveau Bleu', 'être à l\'aise dans l\'eau', 'nager 50 m sans aide à la flottaison', 'avoir envie de pratiquer la natation sportive et la compétition'],
            'pour_qui' => 'Les enfants qui parcourent au moins 50 m sur le ventre et sur le dos en utilisant les bras de façon rythmée.',
            'apprentissages' => ['améliorer les coulées sur le ventre et sur le dos', 'coordonner bras et jambes', 'plonger depuis le bord en position de départ', 'réaliser les virages des quatre nages'],
            'competitions' => 'Rencontres intraclub non officielles.',
            'seances' => '1 à 2 séances de 45 minutes effectives par semaine.',
            'creneaux' => [['Mercredi', '15 h – 15 h 45', 'couloirs 4 et 5'], ['Samedi', '10 h – 10 h 45', 'couloirs 4 et 5']],
            'type' => 'loisir',
        ],
        [
            'section' => 'École de natation',
            'titre' => 'Tremplin Orange',
            'surnom' => 'Tremplin',
            'slug' => 'groupe-tremplin-orange',
            'cle' => 'Niveau Vert, 50 m en deux nages',
            'age' => '6 à 11 ans',
            'resume' => 'Le groupe Tremplin prépare les enfants à la natation sportive : 50 m en deux nages.',
            'prerequis' => ['avoir entre 6 et 11 ans', 'avoir validé le niveau Vert', 'nager 50 m en deux nages', 'avoir envie de pratiquer la natation sportive et la compétition'],
            'pour_qui' => null,
            'apprentissages' => [],
            'competitions' => 'Rencontres intraclub non officielles.',
            'seances' => '1 à 2 séances de 45 minutes effectives par semaine.',
            'creneaux' => [['Mercredi', '16 h – 16 h 45', 'couloirs 4 et 5'], ['Samedi', '9 h – 9 h 45', 'couloirs 4 et 5']],
            'type' => 'loisir',
        ],
        [
            'section' => 'École de natation',
            'titre' => 'Tremplin Jaune',
            'surnom' => 'Tremplin',
            'slug' => 'groupe-tremplin-jaune',
            'cle' => 'Niveau Orange, 75 m en trois nages',
            'age' => '6 à 11 ans',
            'resume' => 'Dernière étape de l\'école de natation, avec les premières rencontres officielles départementales.',
            'prerequis' => ['avoir entre 6 et 11 ans', 'avoir validé le niveau Orange', 'nager 75 m en trois nages', 'avoir envie de pratiquer la natation sportive et la compétition'],
            'pour_qui' => null,
            'apprentissages' => [],
            'competitions' => 'Rencontres intraclub et rencontres officielles départementales.',
            'seances' => '1 à 2 séances de 45 minutes effectives par semaine.',
            'creneaux' => [['Mercredi', '16 h – 16 h 45', 'couloirs 4 et 5'], ['Samedi', '9 h – 9 h 45', 'couloirs 4 et 5']],
            'type' => 'loisir',
        ],
        [
            'section' => 'Jeunesse',
            'titre' => 'Promotion 1',
            'surnom' => null,
            'slug' => 'groupe-promotion-1',
            'cle' => 'Crawl et dos 50 m, niveau Tremplin Jaune',
            'age' => '12 ans et plus',
            'resume' => 'Pour les collégiens et lycéens qui débutent ou reprennent la natation : tous les fondamentaux, sans pression de compétition.',
            'prerequis' => ['avoir 12 ans et plus', 'nager le crawl et le dos sur 50 m', 'avoir le niveau équivalent au Tremplin Jaune'],
            'pour_qui' => null,
            'apprentissages' => ['lire la pendule (l\'horloge du bassin)', 'enchaîner coulées et ondulations de papillon', 'nager le crawl avec respiration bilatérale', 'nager 50 m de crawl et de dos et 25 m de brasse', 'réaliser les virages de crawl, de dos et de brasse', 'partir du plot', 'relever quelques défis physiques'],
            'esprit' => 'Une initiation qui travaille tous les fondamentaux, avec une progression logique et un maximum de temps actif dans l\'eau.',
            'competitions' => 'Rencontres intraclub. Selon leur niveau et sur proposition des entraîneurs, certains nageurs sont invités à rejoindre un autre groupe.',
            'seances' => '1 à 2 séances de 45 minutes effectives par semaine.',
            'creneaux' => [['Mercredi', '18 h – 18 h 50', '2 couloirs'], ['Jeudi', '17 h 30 – 18 h 20', '3 couloirs'], ['Samedi', '14 h – 14 h 50', '4 couloirs']],
            'type' => 'loisir',
        ],
        [
            'section' => 'Jeunesse',
            'titre' => 'Promotion 2',
            'surnom' => null,
            'slug' => 'groupe-promotion-2',
            'cle' => 'Crawl et dos 100 m sans arrêt',
            'age' => '12 ans et plus',
            'resume' => 'La maîtrise des quatre nages, des départs et des virages pour les jeunes qui nagent 100 m sans arrêt.',
            'prerequis' => ['avoir 12 ans et plus', 'nager le crawl et le dos sur 100 m sans arrêt'],
            'pour_qui' => null,
            'apprentissages' => ['la maîtrise des quatre nages', 'les départs et les virages', 'nager correctement le plus longtemps possible'],
            'competitions' => 'Rencontres intraclub. Selon leur niveau, certains nageurs sont invités à rejoindre un autre groupe.',
            'seances' => '1 à 2 séances de 60 minutes effectives par semaine.',
            'creneaux' => [['Mercredi', '18 h – 18 h 50', '2 couloirs'], ['Samedi', '14 h – 14 h 50', '4 couloirs']],
            'type' => 'loisir',
        ],
        [
            'section' => 'Relève',
            'titre' => 'Relève 1',
            'surnom' => null,
            'slug' => 'groupe-releve-1',
            'cle' => 'Crawl et dos 200 m, brasse 50 m',
            'age' => '10 à 13 ans',
            'resume' => 'Premier groupe de compétiteurs : apprendre à performer sur les quatre nages.',
            'prerequis' => ['avoir entre 10 et 13 ans', 'nager 200 m de crawl et 200 m de dos sans arrêt, et 50 m de brasse', 'avoir validé le Tremplin Jaune', 'réussir l\'évaluation de groupe'],
            'pour_qui' => 'Les nageurs et nageuses de la Relève sont des compétiteurs au stade « apprendre à performer ».',
            'apprentissages' => ['la technique des quatre nages, les départs, les virages et les enchaînements de nages', 'le règlement des compétitions', 'les habiletés motrices : agilité, coordination, équilibre, souplesse'],
            'competitions' => 'Intraclub, prises de temps internes, circuit de compétitions de niveau, sélections départementales. Compétitions et meetings par catégories d\'âge.',
            'seances' => '2 à 3 séances de 60 minutes effectives par semaine.',
            'creneaux' => [['Mercredi', '17 h – 18 h', '2 couloirs'], ['Vendredi', '18 h – 19 h', '2 couloirs'], ['Samedi', '11 h – 12 h', '2 couloirs']],
            'type' => 'compet',
        ],
        [
            'section' => 'Relève',
            'titre' => 'Relève 2',
            'surnom' => null,
            'slug' => 'groupe-releve-2',
            'cle' => '400 m crawl, 200 m 4 nages',
            'age' => '11 à 14 ans',
            'resume' => 'Deuxième groupe de la Relève, pour les compétiteurs qui nagent 400 m de crawl et 200 m 4 nages.',
            'prerequis' => ['avoir entre 11 et 14 ans', 'nager 400 m de crawl sans arrêt', 'nager 200 m 4 nages', 'réussir l\'évaluation de groupe'],
            'pour_qui' => 'Les nageurs et nageuses de la Relève sont des compétiteurs au stade « apprendre à performer ».',
            'apprentissages' => ['la technique des quatre nages, les départs, les virages et les enchaînements de nages', 'le règlement des compétitions', 'les habiletés motrices : agilité, coordination, équilibre, souplesse'],
            'competitions' => 'Intraclub, prises de temps internes, circuit de compétitions de niveau, sélections départementales. Compétitions et meetings par catégories d\'âge.',
            'seances' => '2 à 3 séances de 60 minutes effectives par semaine.',
            'creneaux' => [['Mercredi', '17 h – 18 h', '2 couloirs'], ['Vendredi', '18 h – 19 h', '2 couloirs'], ['Samedi', '11 h – 12 h', '2 couloirs']],
            'type' => 'compet',
        ],
        [
            'section' => 'Ligue',
            'titre' => 'Pré-ligue',
            'surnom' => null,
            'slug' => 'groupe-pre-ligue',
            'cle' => '400 m crawl, 100 m 4 nages',
            'age' => '12 ans et plus',
            'resume' => 'Apprendre à s\'entraîner : principes d\'entraînement, stratégies de course et confiance en soi.',
            'prerequis' => ['avoir 12 ans et plus', 'nager 400 m de crawl sans arrêt', 'nager 100 m 4 nages', 'avoir validé la Relève', 'réussir l\'évaluation de groupe'],
            'engagement' => 'Participer à au moins <strong>75 % des entraînements</strong> et à au moins <strong>80 % des compétitions</strong> prévues au calendrier.',
            'pour_qui' => 'Étape « apprendre à s\'entraîner ». Tout en poursuivant leur progression technique, les nageurs découvrent les principes de l\'entraînement et des stratégies de course simples. L\'accent est mis sur la tâche à accomplir et sur le processus, pas sur la performance. La coopération entre entraîneur et nageur et la confiance en soi sont au cœur du groupe.',
            'apprentissages' => ['travail physique : agilité, coordination, équilibre, souplesse et exercices au poids du corps'],
            'competitions' => 'Intraclub, prises de temps internes, circuit de compétitions de niveau, sélections départementales et régionales occasionnelles, selon le niveau et la catégorie d\'âge.',
            'seances' => '3 à 4 séances de 60 minutes effectives par semaine.',
            'creneaux' => [['Mercredi', '19 h – 20 h', '1 couloir'], ['Vendredi', '19 h – 20 h', '1 couloir'], ['Samedi', '12 h 30 – 13 h 30', '1 couloir']],
            'type' => 'compet',
        ],
        [
            'section' => 'Ligue',
            'titre' => 'Ligue',
            'surnom' => null,
            'slug' => 'groupe-ligue',
            'cle' => 'Pré-ligue validée, assiduité 75 %',
            'age' => '13 ans et plus',
            'resume' => 'Le groupe phare du club : s\'entraîner à performer, jusqu\'à cinq séances par semaine.',
            'prerequis' => ['avoir 13 ans et plus', 'avoir validé la Pré-ligue', 'avoir passé l\'évaluation de groupe et y être classé'],
            'engagement' => 'Participer à au moins <strong>75 % des entraînements</strong> et à au moins <strong>80 % des compétitions</strong> prévues au calendrier.',
            'pour_qui' => 'Étape « s\'entraîner à performer ». Les nageurs développent leur base physiologique tout en consolidant leurs habiletés techniques, leur souplesse et leur vitesse.',
            'apprentissages' => ['à sec : développement de la force avec différents outils et au poids du corps', 'au-delà du bassin : bases de la nutrition et habiletés mentales (concentration, attention au moment présent, discours intérieur positif)'],
            'competitions' => 'Compétitions du calendrier de la Fédération Française de Natation (80 % minimum).',
            'seances' => '3 à 5 séances de 60 à 90 minutes effectives par semaine.',
            'creneaux' => [['Mardi', '17 h 30 – 19 h', '4 couloirs'], ['Mercredi', '19 h – 20 h 30', '4 couloirs'], ['Jeudi', '18 h 30 – 20 h', '3 couloirs'], ['Vendredi', '19 h – 20 h 30', '5 couloirs'], ['Samedi', '12 h 30 – 14 h', '4 couloirs']],
            'type' => 'compet',
        ],
    ];

    public const MAITRES_CRENEAUX = [
        ['Mardi', '13 h – 14 h'],
        ['Mercredi', '20 h 30 – 21 h 30'],
        ['Jeudi', '12 h 15 – 13 h 15'],
        ['Jeudi', '20 h – 21 h'],
        ['Samedi', '8 h – 9 h'],
        ['Samedi', '9 h – 10 h'],
    ];

    /**
     * Tous les créneaux, regroupés par jour et triés par heure de début.
     * Les groupes qui partagent le même créneau et le même espace sont réunis
     * (ex. Initiation Bleu et Vert le mercredi à 15 h).
     *
     * Chaque créneau : section, horaire, espace, debut (minutes depuis minuit),
     * groupes = [[titre, slug], …]. Les jours sans créneau sont omis.
     */
    public static function planning(): array
    {
        $creneaux = [];
        $ordre = 0;

        $ajouter = function (string $section, string $titre, string $slug, string $jour, string $horaire, string $espace, int $rang) use (&$creneaux) {
            $cle = "{$jour}|{$horaire}|{$section}|{$espace}";
            if (! isset($creneaux[$cle])) {
                preg_match('/(\d{1,2}) h(?: (\d{2}))?/u', $horaire, $m);
                $creneaux[$cle] = [
                    'jour' => $jour,
                    'section' => $section,
                    'horaire' => $horaire,
                    'espace' => $espace,
                    'debut' => isset($m[1]) ? ((int) $m[1]) * 60 + (int) ($m[2] ?? 0) : 0,
                    'rang' => $rang,
                    'groupes' => [],
                ];
            }
            $creneaux[$cle]['groupes'][] = [$titre, $slug];
        };

        foreach (self::GROUPES as $g) {
            $ordre++;
            foreach ($g['creneaux'] as [$jour, $horaire, $espace]) {
                $ajouter($g['section'], $g['titre'], $g['slug'], $jour, $horaire, $espace, $ordre);
            }
        }

        foreach (self::MAITRES_CRENEAUX as [$jour, $horaire]) {
            $ajouter('Maîtres', 'Maîtres (adultes)', 'section-maitres', $jour, $horaire, '2 couloirs', $ordre + 1);
        }

        $planning = [];
        foreach (self::JOURS as $jour) {
            $duJour = array_values(array_filter($creneaux, fn ($c) => $c['jour'] === $jour));
            if ($duJour) {
                usort($duJour, fn ($a, $b) => [$a['debut'], $a['rang']] <=> [$b['debut'], $b['rang']]);
                $planning[$jour] = $duJour;
            }
        }

        return $planning;
    }

    /** Sections présentes, dans l'ordre du parcours. */
    public static function sections(): array
    {
        return array_keys(self::COULEURS);
    }
}

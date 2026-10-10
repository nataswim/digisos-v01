<?php

namespace Database\Seeders;

use App\Models\Fiche;
use App\Models\FichesSousCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Fiches « Infos pratiques » du CNBB — règles d'inscription 2026-2027 fournies par le club.
 * À mettre à jour à chaque nouvelle saison (tarifs, dates).
 */
class FichesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Fiche::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $author = User::whereHas('role', fn ($q) => $q->where('slug', 'editor'))->first()
            ?? User::whereHas('role', fn ($q) => $q->where('slug', 'admin'))->first();

        $sous = fn (string $slug) => FichesSousCategory::where('slug', $slug)->first()
            ?? throw new \RuntimeException("Sous-catégorie « {$slug} » absente : lancer FichesSousCategoriesSeeder d'abord.");

        $fiches = [
            [
                'sous' => 'dossier-inscription',
                'title' => 'Le dossier d\'inscription : les pièces à fournir',
                'slug' => 'dossier-inscription-pieces-a-fournir',
                'short_description' => 'La liste complète des pièces à rassembler et l\'endroit où déposer le dossier.',
                'featured' => true,
                'long_description' => <<<'HTML'
<p>L'adhésion au club est réservée aux personnes <strong>sachant nager 25 mètres</strong>. Une évaluation peut être proposée lors d'une première adhésion.</p>

<h2>Les pièces du dossier</h2>
<ol>
<li><strong>La fiche d'inscription</strong> complétée et signée. Les compétiteurs ajoutent la <strong>fiche sanitaire</strong>.</li>
<li><strong>Le règlement</strong> de la cotisation (voir la fiche « Moyens de paiement »).</li>
<li><strong>Le certificat médical</strong> lors de la première adhésion, ou l'attestation de santé pour les mineurs (voir la fiche dédiée).</li>
<li><strong>Une photo</strong> pour les nouveaux adhérents.</li>
<li><strong>5 € de caution</strong> pour le badge d'accès.</li>
</ol>

<h2>Où déposer le dossier ?</h2>
<p>Le dossier complet est à déposer dans la <strong>boîte aux lettres du club</strong>, située derrière l'abribus en bas du centre aquatique Cœur d'O.</p>
<p><strong>Un dossier incomplet ne peut pas être validé.</strong> Réinscription après le 30 septembre 2026 : majoration de 10 €.</p>
HTML,
            ],
            [
                'sous' => 'dossier-inscription',
                'title' => 'Certificat médical et attestation de santé',
                'slug' => 'certificat-medical-attestation-sante',
                'short_description' => 'Quand fournir un certificat médical, et quand une simple attestation suffit.',
                'featured' => false,
                'long_description' => <<<'HTML'
<h2>Première adhésion</h2>
<p>Un <strong>certificat médical</strong> est demandé lors de la première adhésion au club.</p>

<h2>Adultes</h2>
<p>Le certificat médical est à <strong>renouveler tous les 3 ans</strong>.</p>

<h2>Mineurs</h2>
<p>Pour les nageurs mineurs, une <strong>attestation</strong> remplie par les parents (formulaire <strong>CERFA n° 15699-01</strong>) remplace le certificat.</p>

<p>En cas de doute sur votre situation, posez la question au club avant de déposer le dossier.</p>
HTML,
            ],
            [
                'sous' => 'tarifs-paiement',
                'title' => 'Tarifs et réductions de la saison 2026-2027',
                'slug' => 'tarifs-reductions-2026-2027',
                'short_description' => 'Le montant de la cotisation selon le groupe, les réductions familles et la majoration de retard.',
                'featured' => true,
                'long_description' => <<<'HTML'
<h2>Cotisations</h2>
<table class="table">
<thead><tr><th>Groupe</th><th>Tarif</th></tr></thead>
<tbody>
<tr><td>École de natation</td><td>175 €</td></tr>
<tr><td>Natation jeunes et étudiants (18-25 ans)</td><td>190 €</td></tr>
<tr><td>Anciens nageurs étudiants hors Bressuire</td><td>110 €</td></tr>
<tr><td>Adultes</td><td>210 €</td></tr>
</tbody>
</table>

<h2>Réductions familles</h2>
<ul>
<li>2 adhésions : <strong>10 € de remise</strong></li>
<li>3 adhésions et plus : <strong>10 % de remise</strong></li>
</ul>
<p>Ces réductions s'appliquent en début de saison uniquement.</p>

<h2>Majoration</h2>
<p>Toute réinscription après le <strong>30 septembre 2026</strong> est majorée de <strong>10 €</strong>.</p>
<p>À ajouter : 5 € de caution pour le badge d'accès.</p>
HTML,
            ],
            [
                'sous' => 'tarifs-paiement',
                'title' => 'Les moyens de paiement',
                'slug' => 'moyens-de-paiement',
                'short_description' => 'Espèces, chèques en plusieurs fois, virement, coupons sport et chèques-vacances ANCV.',
                'featured' => false,
                'long_description' => <<<'HTML'
<ul>
<li><strong>Espèces</strong></li>
<li><strong>Chèques</strong> : paiement possible en 1 à 4 chèques, encaissés le 15 septembre, le 15 octobre, le 15 novembre et le 15 décembre.</li>
<li><strong>Virement</strong></li>
<li><strong>Coupons sport et chèques-vacances ANCV</strong> : à remettre avant le 30 octobre.</li>
</ul>
<p>Le règlement est joint au dossier d'inscription.</p>
HTML,
            ],
            [
                'sous' => 'ecole-de-natation',
                'title' => 'Les tests de l\'École de Natation Française',
                'slug' => 'tests-ecole-natation-francaise',
                'short_description' => 'Sauv\'nage, Pass\'sports de l\'eau et Pass\'compétition : les trois étapes du jeune nageur.',
                'featured' => false,
                'long_description' => <<<'HTML'
<p>Les jeunes nageurs progressent en validant les trois tests de l'École de Natation Française (Fédération Française de Natation).</p>
<ol>
<li><strong>Sauv'nage</strong> : être à l'aise et en sécurité dans l'eau.</li>
<li><strong>Pass'sports de l'eau</strong> : découvrir plusieurs disciplines aquatiques.</li>
<li><strong>Pass'compétition</strong> : maîtriser les bases de la natation course pour accéder aux compétitions.</li>
</ol>
<p>Les entraîneurs organisent les tests au cours de la saison.</p>
HTML,
            ],
            [
                'sous' => 'competition',
                'title' => 'Devenir officiel ou bénévole',
                'slug' => 'devenir-officiel-benevole',
                'short_description' => 'Le club invite les parents à devenir officiels ou à rejoindre le comité directeur.',
                'featured' => false,
                'long_description' => <<<'HTML'
<h2>Officiel</h2>
<p>Les compétitions de la FFN ne peuvent pas se dérouler sans officiels (chronométreurs, juges). Les parents de nageurs sont invités à se former : c'est une manière concrète d'accompagner les enfants en compétition.</p>

<h2>Comité directeur</h2>
<p>Le comité directeur, composé de bénévoles, organise la vie du club. Les parents qui souhaitent s'impliquer y sont les bienvenus.</p>

<p>Pour vous proposer, adressez-vous aux entraîneurs ou écrivez au club.</p>
HTML,
            ],
            [
                'sous' => 'centre-aquatique',
                'title' => 'Cœur d\'O, badge d\'accès et vacances scolaires',
                'slug' => 'coeur-d-o-badge-vacances-scolaires',
                'short_description' => 'Les entraînements ont lieu au centre aquatique Cœur d\'O ; les activités s\'arrêtent pendant les vacances scolaires.',
                'featured' => false,
                'long_description' => <<<'HTML'
<h2>La piscine du club</h2>
<p>Les entraînements ont lieu au <strong>centre aquatique Cœur d'O</strong> de l'Agglomération du Bocage Bressuirais (Agglo 2B), ouvert en 2009.</p>

<h2>Le badge</h2>
<p>Chaque adhérent reçoit un badge d'accès contre une <strong>caution de 5 €</strong>, versée avec le dossier d'inscription.</p>

<h2>Vacances scolaires</h2>
<p>Les activités du club <strong>s'arrêtent pendant les vacances scolaires</strong>.</p>

<h2>Boîte aux lettres</h2>
<p>La boîte aux lettres du club se trouve derrière l'abribus, en bas de Cœur d'O.</p>
HTML,
            ],
        ];

        $order = [];
        foreach ($fiches as $f) {
            $sousCategory = $sous($f['sous']);
            $order[$f['sous']] = ($order[$f['sous']] ?? 0) + 1;

            Fiche::create([
                'title' => $f['title'],
                'slug' => $f['slug'],
                'short_description' => $f['short_description'],
                'long_description' => trim($f['long_description']),
                'image' => null,
                'visibility' => 'public',
                'is_published' => true,
                'is_featured' => $f['featured'],
                'views_count' => 0,
                'sort_order' => $order[$f['sous']],
                'fiches_category_id' => $sousCategory->fiches_category_id,
                'fiches_sous_category_id' => $sousCategory->id,
                'meta_title' => mb_strimwidth($f['title'], 0, 55, '…') . ' - CNBB',
                'meta_keywords' => 'CNBB, natation, Bressuire, infos pratiques',
                'meta_description' => mb_strimwidth($f['short_description'], 0, 160, '…'),
                'created_by' => $author?->id,
                'created_by_name' => $author?->name,
                'updated_by' => $author?->id,
                'published_at' => now()->subDays(30),
            ]);
            $this->command->info("✅ Fiche : {$f['title']}");
        }

        $this->command->info('🎉 FichesSeeder : ' . count($fiches) . ' fiches créées');
    }
}

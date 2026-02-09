<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fiche;
use App\Models\FichesCategory;
use App\Models\FichesSousCategory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FichesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Fiche::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $editor = User::whereHas('role', fn($q) => $q->where('slug', 'editor'))->first();
        
        $categoryTechniques = FichesCategory::where('slug', 'techniques-entrainement')->first();
        $categoryGestion = FichesCategory::where('slug', 'gestion-administrative')->first();
        $categoryMateriel = FichesCategory::where('slug', 'materiel-equipement')->first();
        
        $sousCategoryNatation = FichesSousCategory::where('slug', 'natation')->first();
        $sousCategoryInscriptions = FichesSousCategory::where('slug', 'inscriptions')->first();
        $sousCategoryBassins = FichesSousCategory::where('slug', 'bassins')->first();

        $fiches = [
            [
                'title' => 'Technique du crawl : perfectionnement du mouvement de bras',
                'slug' => 'technique-crawl-perfectionnement-mouvement-bras',
                'short_description' => 'Fiche technique détaillée pour améliorer l\'efficacité du mouvement de bras en crawl et réduire la fatigue.',
                'long_description' => '<h2>Objectif de la fiche</h2>
<p>Cette fiche permet aux coachs et nageurs de maîtriser les 4 phases du mouvement de bras en crawl pour optimiser la propulsion et l\'économie d\'énergie.</p>

<h3>Phase 1 : Entrée dans l\'eau</h3>
<ul>
<li>Main détendue, doigts légèrement écartés</li>
<li>Entrée à 45° devant l\'épaule</li>
<li>Coude haut, bras tendu</li>
</ul>

<h3>Phase 2 : Prise d\'appui</h3>
<ul>
<li>Flexion progressive du coude (90-120°)</li>
<li>Main orientée vers l\'arrière</li>
<li>Avant-bras perpendiculaire au fond</li>
</ul>

<h3>Phase 3 : Traction</h3>
<ul>
<li>Accélération progressive de la main</li>
<li>Trajet en S sous le corps</li>
<li>Maximum de force au niveau des hanches</li>
</ul>

<h3>Phase 4 : Poussée et sortie</h3>
<ul>
<li>Extension complète du bras</li>
<li>Main sort paume vers la cuisse</li>
<li>Coude sort en premier</li>
</ul>

<h3>Exercices recommandés</h3>
<ol>
<li>4x50m éducatif "rattrapé" (25 à 50% intensité)</li>
<li>6x25m nage complète avec focus phase par phase</li>
<li>200m crawl technique (65% intensité)</li>
</ol>

<h3>Points de vigilance</h3>
<p><strong>Erreurs fréquentes :</strong> Entrée de main trop large, coude qui tombe, poussée incomplète, rotation insuffisante des épaules.</p>',
                'image' => 'fiches/crawl-technique-bras.jpg',
                'visibility' => 'authenticated',
                'is_published' => true,
                'is_featured' => true,
                'views_count' => 342,
                'sort_order' => 1,
                'fiches_category_id' => $categoryTechniques->id,
                'fiches_sous_category_id' => $sousCategoryNatation->id,
                'meta_title' => 'Technique crawl : mouvement de bras - Fiche Digital\'SOS',
                'meta_keywords' => 'crawl, natation, technique, bras, mouvement',
                'meta_description' => 'Fiche technique complète pour perfectionner le mouvement de bras en crawl : phases, exercices et points de vigilance.',
                'created_by' => $editor?->id,
                'created_by_name' => $editor?->name,
                'updated_by' => $editor?->id,
                'published_at' => now()->subDays(20),
            ],
            [
                'title' => 'Procédure d\'inscription : checklist complète',
                'slug' => 'procedure-inscription-checklist-complete',
                'short_description' => 'Guide pas à pas pour traiter les inscriptions de nouveaux membres avec tous les documents obligatoires.',
                'long_description' => '<h2>Procédure d\'inscription standard</h2>
<p>Cette fiche garantit une inscription complète et conforme pour tous les nouveaux adhérents de votre structure.</p>

<h3>Documents obligatoires</h3>
<ul>
<li>✅ Formulaire d\'inscription complété et signé</li>
<li>✅ Certificat médical de non contre-indication (< 1 an)</li>
<li>✅ Photo d\'identité récente</li>
<li>✅ Copie pièce d\'identité (recto-verso)</li>
<li>✅ Autorisation parentale (si mineur)</li>
<li>✅ Attestation assurance responsabilité civile</li>
<li>✅ Règlement de la cotisation (chèque, CB, espèces)</li>
</ul>

<h3>Étapes de traitement</h3>
<ol>
<li><strong>Réception :</strong> Vérifier exhaustivité du dossier</li>
<li><strong>Validation :</strong> Contrôler validité certificat médical</li>
<li><strong>Saisie :</strong> Enregistrer dans Digital\'SOS</li>
<li><strong>Paiement :</strong> Encaisser et éditer reçu</li>
<li><strong>Licence :</strong> Demander licence fédérale si applicable</li>
<li><strong>Kit adhérent :</strong> Remettre carte membre + règlement intérieur</li>
</ol>

<h3>Cas particuliers</h3>
<p><strong>Certificat médical périmé :</strong> Proposer questionnaire de santé QPE (Questionnaire Personne Entreprise) en attendance renouvellement.</p>

<p><strong>Paiement échelonné :</strong> Faire signer échéancier avec RIB et autorisation prélèvement.</p>

<p><strong>Réinscription :</strong> Vérifier validité des documents année précédente (certificat médical valable 3 ans si QPE négatif).</p>

<h3>Conservation des données</h3>
<p>Durée légale : 5 ans après fin d\'adhésion (RGPD). Archivage sécurisé obligatoire.</p>',
                'image' => 'fiches/procedure-inscription.jpg',
                'visibility' => 'authenticated',
                'is_published' => true,
                'is_featured' => false,
                'views_count' => 198,
                'sort_order' => 2,
                'fiches_category_id' => $categoryGestion->id,
                'fiches_sous_category_id' => $sousCategoryInscriptions->id,
                'meta_title' => 'Procédure d\'inscription complète - Fiche Digital\'SOS',
                'meta_keywords' => 'inscription, procédure, documents, adhésion',
                'meta_description' => 'Checklist complète pour gérer les inscriptions : documents obligatoires, étapes et cas particuliers.',
                'created_by' => $editor?->id,
                'created_by_name' => $editor?->name,
                'updated_by' => $editor?->id,
                'published_at' => now()->subDays(30),
            ],
            [
                'title' => 'Maintenance hebdomadaire du bassin : protocole sanitaire',
                'slug' => 'maintenance-hebdomadaire-bassin-protocole-sanitaire',
                'short_description' => 'Protocole de maintenance et contrôle qualité de l\'eau pour garantir la sécurité et l\'hygiène des installations aquatiques.',
                'long_description' => '<h2>Protocole de maintenance bassin</h2>
<p>Ce protocole garantit la conformité sanitaire de votre bassin et la sécurité des usagers.</p>

<h3>Contrôles quotidiens (avant ouverture)</h3>
<table>
<tr><th>Paramètre</th><th>Norme</th><th>Action si hors norme</th></tr>
<tr><td>pH</td><td>7.2 - 7.6</td><td>Ajuster avec pH+ ou pH-</td></tr>
<tr><td>Chlore libre</td><td>0.4 - 1.4 mg/L</td><td>Ajout chlore ou dilution</td></tr>
<tr><td>Température</td><td>26 - 28°C</td><td>Régler chauffage</td></tr>
<tr><td>Turbidité</td><td>< 0.5 NTU</td><td>Nettoyage filtres</td></tr>
</table>

<h3>Tâches hebdomadaires</h3>
<ul>
<li><strong>Lundi :</strong> Nettoyage manuel parois et ligne d\'eau</li>
<li><strong>Mercredi :</strong> Aspiration fond du bassin</li>
<li><strong>Vendredi :</strong> Contre-lavage filtres (backwash)</li>
<li><strong>Samedi :</strong> Contrôle équipements sécurité (perches, bouées)</li>
</ul>

<h3>Tâches mensuelles</h3>
<ol>
<li>Nettoyage complet skimmers et préfiltres pompe</li>
<li>Vérification niveau désinfectant automatique</li>
<li>Test alcalinité (TAC) : norme 80-120 mg/L</li>
<li>Inspection visuelle canalisations et joints</li>
<li>Remplacement cartouches filtrantes si nécessaire</li>
</ol>

<h3>Traitement choc (mensuel ou si incident)</h3>
<p><strong>Indications :</strong> Eau trouble, odeur forte, irritations usagers, forte fréquentation.</p>
<p><strong>Procédure :</strong> Fermeture bassin 24h, chloration choc (10x dose normale), filtration continue 48h.</p>

<h3>Registres obligatoires</h3>
<p>Tenir à jour registre sanitaire avec relevés quotidiens. Conservation 3 ans. Inspection ARS possible.</p>

<h3>Équipements de protection</h3>
<p>Port obligatoire : gants, lunettes, masque pour manipulation produits chimiques.</p>',
                'image' => 'fiches/maintenance-bassin.jpg',
                'visibility' => 'public',
                'is_published' => true,
                'is_featured' => false,
                'views_count' => 267,
                'sort_order' => 3,
                'fiches_category_id' => $categoryMateriel->id,
                'fiches_sous_category_id' => $sousCategoryBassins->id,
                'meta_title' => 'Maintenance bassin : protocole sanitaire - Fiche Digital\'SOS',
                'meta_keywords' => 'bassin, maintenance, protocole, sanitaire, eau',
                'meta_description' => 'Protocole complet de maintenance et contrôle qualité de l\'eau pour bassins : contrôles, tâches et normes.',
                'created_by' => $editor?->id,
                'created_by_name' => $editor?->name,
                'updated_by' => $editor?->id,
                'published_at' => now()->subDays(25),
            ],
        ];

        foreach ($fiches as $data) {
            $fiche = Fiche::create($data);
            $this->command->info("✅ Fiche créée : {$fiche->title}");
        }

        $this->command->info('🎉 FichesSeeder terminé : 3 fiches créées');
    }
}

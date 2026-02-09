<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Downloadable;
use App\Models\DownloadCategory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DownloadablesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Downloadable::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $editor = User::whereHas('role', fn($q) => $q->where('slug', 'editor'))->first();
        
        $categoryEbooks = DownloadCategory::where('slug', 'ebooks-guides')->first();
        $categoryFormulaires = DownloadCategory::where('slug', 'formulaires-modeles')->first();
        $categoryPlans = DownloadCategory::where('slug', 'plans-entrainement')->first();

        $downloadables = [
            [
                'title' => 'Guide Complet de la Gestion Sportive Moderne',
                'slug' => 'guide-complet-gestion-sportive-moderne',
                'format' => 'pdf',
                'short_description' => 'E-book de 120 pages couvrant tous les aspects de la gestion d\'une structure sportive : administration, finances, RH, communication.',
                'long_description' => '<h2>Votre référence complète en gestion sportive</h2>
<p>Cet e-book de 120 pages est le guide définitif pour professionnaliser votre structure sportive.</p>

<h3>Table des matières</h3>
<ul>
<li><strong>Chapitre 1 :</strong> Structurer son organisation (20 pages)</li>
<li><strong>Chapitre 2 :</strong> Gérer les finances et la comptabilité (25 pages)</li>
<li><strong>Chapitre 3 :</strong> Recruter et manager son équipe (18 pages)</li>
<li><strong>Chapitre 4 :</strong> Optimiser les plannings et le matériel (22 pages)</li>
<li><strong>Chapitre 5 :</strong> Développer sa communication (15 pages)</li>
<li><strong>Chapitre 6 :</strong> Mesurer et améliorer la performance (20 pages)</li>
</ul>

<h3>Points forts</h3>
<ul>
<li>✅ 40 fiches pratiques prêtes à l\'emploi</li>
<li>✅ 15 études de cas réels</li>
<li>✅ Templates et checklists téléchargeables</li>
<li>✅ Interviews d\'experts du secteur</li>
</ul>

<h3>Pour qui ?</h3>
<p>Présidents de club, directeurs sportifs, coachs entrepreneurs, gestionnaires de structures.</p>',
                'file_path' => 'downloads/ebooks/guide-gestion-sportive-2025.pdf',
                'file_size' => '8.5 MB',
                'cover_image' => 'downloads/covers/guide-gestion-sportive.jpg',
                'download_category_id' => $categoryEbooks->id,
                'user_permission' => 'user',
                'download_count' => 342,
                'order' => 1,
                'status' => 'active',
                'is_featured' => true,
                'meta_title' => 'Guide gestion sportive moderne - E-book Digital\'SOS',
                'meta_description' => 'Téléchargez notre guide complet de 120 pages sur la gestion moderne des structures sportives.',
                'meta_keywords' => 'guide, gestion sportive, ebook, management',
                'created_by' => $editor?->id,
                'created_by_name' => $editor?->name,
                'updated_by' => $editor?->id,
            ],
            [
                'title' => 'Pack Formulaires Administratifs Complet',
                'slug' => 'pack-formulaires-administratifs-complet',
                'format' => 'zip',
                'short_description' => 'Archive contenant 25 formulaires types au format Word modifiable : inscriptions, autorisations, contrats, inventaires.',
                'long_description' => '<h2>Tous vos documents administratifs prêts à l\'emploi</h2>
<p>Gain de temps garanti avec ces 25 formulaires professionnels conformes aux réglementations en vigueur.</p>

<h3>Contenu de l\'archive (25 fichiers .docx)</h3>

<h4>Gestion des adhérents (8 documents)</h4>
<ul>
<li>Formulaire d\'inscription standard</li>
<li>Fiche sanitaire de liaison</li>
<li>Autorisation parentale pour mineurs</li>
<li>Autorisation de droit à l\'image</li>
<li>Décharge de responsabilité</li>
<li>Questionnaire de santé QPE</li>
<li>Demande de certificat médical</li>
<li>Attestation de présence</li>
</ul>

<h4>Gestion du personnel (7 documents)</h4>
<ul>
<li>Contrat d\'engagement éducatif (CEE)</li>
<li>Contrat de bénévolat</li>
<li>Feuille de présence mensuelle</li>
<li>Note de frais</li>
<li>Demande de congés</li>
<li>Fiche de poste type</li>
<li>Entretien annuel d\'évaluation</li>
</ul>

<h4>Gestion matérielle (6 documents)</h4>
<ul>
<li>Fiche d\'inventaire matériel</li>
<li>Bon de sortie équipement</li>
<li>Déclaration de perte/vol</li>
<li>Fiche de maintenance préventive</li>
<li>Check-list sécurité installations</li>
<li>Registre d\'entretien bassin</li>
</ul>

<h4>Documents généraux (4 documents)</h4>
<ul>
<li>Procès-verbal de réunion</li>
<li>Convocation assemblée générale</li>
<li>Compte-rendu d\'incident</li>
<li>Demande de subvention</li>
</ul>

<h3>Formats</h3>
<p>Tous les documents sont au format .docx (Microsoft Word) entièrement modifiables. Compatible Word, LibreOffice, Google Docs.</p>',
                'file_path' => 'downloads/formulaires/pack-formulaires-complet.zip',
                'file_size' => '2.3 MB',
                'cover_image' => 'downloads/covers/pack-formulaires.jpg',
                'download_category_id' => $categoryFormulaires->id,
                'user_permission' => 'user',
                'download_count' => 589,
                'order' => 2,
                'status' => 'active',
                'is_featured' => false,
                'meta_title' => 'Pack formulaires administratifs - Digital\'SOS',
                'meta_description' => 'Téléchargez 25 formulaires administratifs types modifiables pour votre structure sportive.',
                'meta_keywords' => 'formulaires, documents, administratif, templates',
                'created_by' => $editor?->id,
                'created_by_name' => $editor?->name,
                'updated_by' => $editor?->id,
            ],
            [
                'title' => 'Plan d\'Entraînement Natation 12 Semaines',
                'slug' => 'plan-entrainement-natation-12-semaines',
                'format' => 'pdf',
                'short_description' => 'Programme progressif de 12 semaines pour améliorer technique et endurance en natation. Niveau intermédiaire à confirmé.',
                'long_description' => '<h2>Programme structuré de préparation natation</h2>
<p>Ce plan d\'entraînement de 12 semaines vous accompagne vers vos objectifs avec une progression adaptée.</p>

<h3>Objectifs du programme</h3>
<ul>
<li>🎯 Améliorer la technique des 4 nages</li>
<li>🎯 Développer l\'endurance aérobie</li>
<li>🎯 Augmenter la vitesse sur distances courtes</li>
<li>🎯 Perfectionner les virages et départs</li>
</ul>

<h3>Structure du plan</h3>

<h4>Phase 1 : Fondations (Semaines 1-4)</h4>
<p>Focus technique et endurance de base. 3 séances/semaine, 45-60 min.</p>

<h4>Phase 2 : Développement (Semaines 5-8)</h4>
<p>Intensification progressive. 4 séances/semaine, 60-75 min.</p>

<h4>Phase 3 : Performance (Semaines 9-12)</h4>
<p>Pics d\'intensité et affûtage. 4-5 séances/semaine, 60-90 min.</p>

<h3>Contenu détaillé</h3>
<ul>
<li>✅ 48 séances complètes clés en main</li>
<li>✅ Échauffements et récupérations adaptés</li>
<li>✅ Séries techniques avec éducatifs</li>
<li>✅ Tests d\'évaluation mensuels</li>
<li>✅ Conseils nutrition et récupération</li>
<li>✅ Tableau de suivi progression</li>
</ul>

<h3>Niveau requis</h3>
<p>Savoir nager les 4 nages + parcourir 1000m en continu. Volume hebdomadaire initial : 3000-4000m.</p>

<h3>Matériel recommandé</h3>
<p>Planche, pull-buoy, palmes courtes, plaquettes. Accès bassin 25m minimum.</p>',
                'file_path' => 'downloads/plans/plan-natation-12-semaines.pdf',
                'file_size' => '1.8 MB',
                'cover_image' => 'downloads/covers/plan-natation-12sem.jpg',
                'download_category_id' => $categoryPlans->id,
                'user_permission' => 'user',
                'download_count' => 724,
                'order' => 3,
                'status' => 'active',
                'is_featured' => true,
                'meta_title' => 'Plan entraînement natation 12 semaines - Digital\'SOS',
                'meta_description' => 'Programme complet de 12 semaines pour progresser en natation : technique, endurance et vitesse.',
                'meta_keywords' => 'plan entraînement, natation, programme, progression',
                'created_by' => $editor?->id,
                'created_by_name' => $editor?->name,
                'updated_by' => $editor?->id,
            ],
        ];

        foreach ($downloadables as $data) {
            $downloadable = Downloadable::create($data);
            $this->command->info("✅ Téléchargement créé : {$downloadable->title}");
        }

        $this->command->info('🎉 DownloadablesSeeder terminé : 3 téléchargements créés');
    }
}

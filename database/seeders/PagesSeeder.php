<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\PagesCategory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PagesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Page::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $admin = User::whereHas('role', fn($q) => $q->where('slug', 'admin'))->first();
        
        $categoryAPropos = PagesCategory::where('slug', 'a-propos')->first();
        $categoryLegal = PagesCategory::where('slug', 'informations-legales')->first();
        $categorySupport = PagesCategory::where('slug', 'support-aide')->first();

        $pages = [
            [
                'title' => 'Notre Mission',
                'slug' => 'notre-mission',
                'short_description' => 'Digital\'SOS accompagne les structures sportives dans leur transformation digitale pour une gestion maîtrisée et performante.',
                'long_description' => '<h1>Digital\'SOS : Révolutionner la gestion sportive</h1>

<h2>Notre Vision</h2>
<p>Nous croyons qu\'une structure sportive performante est une structure bien organisée. Digital\'SOS est né d\'un constat simple : les managers sportifs passent trop de temps sur des tâches administratives au détriment de l\'essentiel.</p>

<h2>La Méthode M2PC</h2>
<p>Notre approche innovante repose sur 4 piliers complémentaires :</p>

<h3>🔧 Matériel</h3>
<p>Inventaire en temps réel, traçabilité complète et alertes de maintenance pour optimiser votre parc d\'équipements.</p>

<h3>📅 Planning</h3>
<p>Synchronisation intelligente des entraînements, matchs et événements avec détection automatique des conflits.</p>

<h3>👥 Personnel</h3>
<p>Gestion simplifiée des coachs, bénévoles et salariés : missions, contrats, présences et communications centralisées.</p>

<h3>📚 Contenu</h3>
<p>Bibliothèque numérique de supports pédagogiques, fiches techniques et ressources pour vos équipes.</p>

<h2>Nos Valeurs</h2>
<ul>
<li><strong>Simplicité :</strong> Une interface intuitive accessible à tous</li>
<li><strong>Efficacité :</strong> Automatisation des tâches répétitives</li>
<li><strong>Transparence :</strong> Visibilité totale sur vos opérations</li>
<li><strong>Accompagnement :</strong> Support dédié et formation continue</li>
</ul>

<h2>Notre Impact</h2>
<p>Depuis 2024, Digital\'SOS accompagne plus de 150 structures sportives en France :</p>
<ul>
<li>-60% de temps consacré aux tâches administratives</li>
<li>+40% de satisfaction des adhérents</li>
<li>95% de taux de renouvellement client</li>
</ul>

<h2>L\'Équipe</h2>
<p>Digital\'SOS réunit des experts en gestion sportive, développeurs passionnés et coachs de terrain pour créer la solution que nous aurions rêvé d\'avoir.</p>',
                'image' => 'pages/notre-mission.jpg',
                'visibility' => 'public',
                'is_published' => true,
                'sort_order' => 1,
                'pages_category_id' => $categoryAPropos->id,
                'meta_title' => 'Notre Mission - Digital\'SOS',
                'meta_keywords' => 'mission, vision, m2pc, gestion sportive',
                'meta_description' => 'Découvrez la mission de Digital\'SOS : révolutionner la gestion sportive avec la méthode M2PC.',
                'created_by' => $admin?->id,
                'created_by_name' => $admin?->name,
                'updated_by' => $admin?->id,
                'published_at' => now()->subDays(60),
            ],
            [
                'title' => 'Mentions Légales',
                'slug' => 'mentions-legales',
                'short_description' => 'Informations légales et conditions d\'utilisation de la plateforme Digital\'SOS.',
                'long_description' => '<h1>Mentions Légales</h1>

<h2>Éditeur du site</h2>
<p><strong>Raison sociale :</strong> Digital\'SOS SAS<br>
<strong>Capital social :</strong> 50 000 €<br>
<strong>SIRET :</strong> 123 456 789 00012<br>
<strong>RCS :</strong> Paris B 123 456 789<br>
<strong>Siège social :</strong> 123 Avenue des Sports, 75000 Paris, France<br>
<strong>Téléphone :</strong> +33 1 23 45 67 89<br>
<strong>Email :</strong> contact@digitalsos.fr</p>

<h2>Directeur de publication</h2>
<p>Hassan El Haouat, Président</p>

<h2>Hébergement</h2>
<p><strong>Hébergeur :</strong> OVH SAS<br>
<strong>Siège social :</strong> 2 rue Kellermann, 59100 Roubaix, France<br>
<strong>Téléphone :</strong> +33 9 72 10 10 07</p>

<h2>Propriété intellectuelle</h2>
<p>L\'ensemble du contenu de ce site (textes, images, logos, vidéos) est la propriété exclusive de Digital\'SOS SAS, sauf mention contraire.</p>
<p>Toute reproduction, représentation, modification, publication ou adaptation sans autorisation écrite préalable est strictement interdite.</p>

<h2>Protection des données personnelles</h2>
<p>Conformément au RGPD (Règlement Général sur la Protection des Données), vous disposez d\'un droit d\'accès, de rectification et de suppression de vos données.</p>
<p>Pour exercer ces droits : <a href="mailto:rgpd@digitalsos.fr">rgpd@digitalsos.fr</a></p>

<h2>Cookies</h2>
<p>Ce site utilise des cookies pour améliorer votre expérience. Consultez notre <a href="/cookies">politique de cookies</a> pour plus d\'informations.</p>

<h2>Responsabilité</h2>
<p>Digital\'SOS s\'efforce d\'assurer l\'exactitude des informations diffusées mais ne peut garantir l\'absence d\'erreurs ou d\'omissions.</p>

<h2>Droit applicable</h2>
<p>Les présentes mentions sont régies par le droit français. Tout litige relève de la compétence exclusive des tribunaux de Paris.</p>

<p><em>Dernière mise à jour : ' . now()->format('d/m/Y') . '</em></p>',
                'image' => 'pages/mentions-legales.jpg',
                'visibility' => 'public',
                'is_published' => true,
                'sort_order' => 1,
                'pages_category_id' => $categoryLegal->id,
                'meta_title' => 'Mentions Légales - Digital\'SOS',
                'meta_keywords' => 'mentions légales, rgpd, propriété intellectuelle',
                'meta_description' => 'Consultez les mentions légales de Digital\'SOS : éditeur, hébergeur, propriété intellectuelle et RGPD.',
                'created_by' => $admin?->id,
                'created_by_name' => $admin?->name,
                'updated_by' => $admin?->id,
                'published_at' => now()->subDays(90),
            ],
            [
                'title' => 'Guide de Démarrage Rapide',
                'slug' => 'guide-demarrage-rapide',
                'short_description' => 'Tutoriel complet pour bien démarrer avec Digital\'SOS en 5 étapes simples.',
                'long_description' => '<h1>Guide de Démarrage Rapide</h1>

<p>Bienvenue sur Digital\'SOS ! Ce guide vous accompagne dans vos premiers pas sur la plateforme.</p>

<h2>Étape 1 : Créer votre compte (5 min)</h2>
<ol>
<li>Cliquez sur "Inscription" en haut à droite</li>
<li>Renseignez vos informations (email, nom, structure)</li>
<li>Validez votre email via le lien de confirmation</li>
<li>Complétez votre profil dans "Mon compte"</li>
</ol>

<h2>Étape 2 : Configurer votre structure (10 min)</h2>
<h3>Informations générales</h3>
<p>Accédez à <strong>Paramètres > Structure</strong> pour renseigner :</p>
<ul>
<li>Nom officiel et logo</li>
<li>Adresse et coordonnées</li>
<li>Disciplines sportives pratiquées</li>
<li>Horaires d\'ouverture</li>
</ul>

<h3>Gestion des utilisateurs</h3>
<p>Invitez votre équipe depuis <strong>Personnel > Inviter</strong> :</p>
<ul>
<li>Attribuez les rôles (Admin, Coach, Bénévole)</li>
<li>Définissez les permissions par rôle</li>
</ul>

<h2>Étape 3 : Inventorier votre matériel (15 min)</h2>
<p>Depuis <strong>Matériel > Ajouter équipement</strong> :</p>
<ol>
<li>Scannez les codes-barres ou saisissez manuellement</li>
<li>Catégorisez par type (Balles, Maillots, etc.)</li>
<li>Définissez l\'état et la localisation</li>
<li>Programmez les rappels de maintenance</li>
</ol>

<h2>Étape 4 : Créer votre premier planning (20 min)</h2>
<p>Accédez à <strong>Planning > Nouveau créneau</strong> :</p>
<ol>
<li>Sélectionnez date, heure, durée</li>
<li>Choisissez l\'installation et le matériel nécessaire</li>
<li>Assignez le(s) encadrant(s)</li>
<li>Ajoutez les participants</li>
<li>Digital\'SOS détecte automatiquement les conflits !</li>
</ol>

<h2>Étape 5 : Explorer les ressources (10 min)</h2>
<p>Découvrez notre bibliothèque de contenus :</p>
<ul>
<li><strong>Fiches techniques :</strong> Protocoles d\'entraînement, exercices</li>
<li><strong>Vidéos :</strong> Tutoriels et démonstrations</li>
<li><strong>E-books :</strong> Guides complets téléchargeables</li>
<li><strong>Formulaires :</strong> Documents administratifs types</li>
</ul>

<h2>Besoin d\'aide ?</h2>
<p>Notre support est disponible 7j/7 :</p>
<ul>
<li>📧 Email : <a href="mailto:support@digitalsos.fr">support@digitalsos.fr</a></li>
<li>💬 Chat en direct (coin inférieur droit)</li>
<li>📞 Téléphone : +33 1 23 45 67 89</li>
<li>📚 <a href="/support-aide">Centre d\'aide complet</a></li>
</ul>

<h2>Prochaines étapes recommandées</h2>
<ol>
<li>Personnaliser les notifications (emails, SMS)</li>
<li>Importer vos données existantes (Excel, CSV)</li>
<li>Configurer les paiements en ligne</li>
<li>Explorer les statistiques et rapports</li>
</ol>

<p><strong>Astuce :</strong> Activez le mode "Visite guidée" dans Paramètres pour un tutoriel interactif complet !</p>',
                'image' => 'pages/guide-demarrage.jpg',
                'visibility' => 'public',
                'is_published' => true,
                'sort_order' => 1,
                'pages_category_id' => $categorySupport->id,
                'meta_title' => 'Guide de démarrage rapide - Digital\'SOS',
                'meta_keywords' => 'guide, tutoriel, démarrage, aide, support',
                'meta_description' => 'Démarrez avec Digital\'SOS en 5 étapes simples : création compte, configuration, matériel, planning et ressources.',
                'created_by' => $admin?->id,
                'created_by_name' => $admin?->name,
                'updated_by' => $admin?->id,
                'published_at' => now()->subDays(45),
            ],
        ];

        foreach ($pages as $data) {
            $page = Page::create($data);
            $this->command->info("✅ Page créée : {$page->title}");
        }

        $this->command->info('🎉 PagesSeeder terminé : 3 pages créées');
    }
}

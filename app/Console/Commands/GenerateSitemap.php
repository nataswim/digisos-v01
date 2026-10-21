<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SitemapService;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate {--discover : Recenser d\'abord les pages du site (les nouveaux contenus restent à approuver)}';
    protected $description = 'Génère le sitemap XML du site';

    protected $sitemapService;

    public function __construct(SitemapService $sitemapService)
    {
        parent::__construct();
        $this->sitemapService = $sitemapService;
    }

    public function handle()
    {
        $this->info('🚀 Génération du sitemap en cours...');

        try {
            if ($this->option('discover')) {
                $stats = $this->sitemapService->discoverUrls();
                $this->info("Découverte : {$stats['total']} adresses, {$stats['new']} nouvelles, {$stats['removed']} retirées.");
                if ($stats['errors']) {
                    $this->warn('Modules non lus : ' . implode(', ', $stats['errors']));
                }
            }

            // Générer le XML
            $result = $this->sitemapService->generateXml();

            $this->info('✅ Sitemap généré avec succès !');
            $this->table(
                ['Metric', 'Value'],
                [
                    ['URLs incluses', $result['urls_count']],
                    ['Taille du fichier', number_format($result['file_size'] / 1024, 2) . ' KB'],
                    ['Généré le', $result['generated_at']->format('d/m/Y H:i:s')],
                    ['Chemin', $result['file_path']]
                ]
            );

            $this->info('📍 Le sitemap est accessible sur : ' . route('sitemap.xml'));

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('❌ Erreur lors de la génération : ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
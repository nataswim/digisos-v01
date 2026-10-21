{{-- Modal Découverte --}}
<div class="modal fade" id="discoverModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🔍 Découvrir les URLs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Recense toutes les pages publiques du site : pages fixes, articles, fiches, pages, vidéos, galeries, documents, compétitions, partenaires, boutique et installations. Les contenus réservés aux membres ne sont pas repris.</p>
                <div class="alert alert-info mb-0">
                    <ul class="mb-0 ps-3">
                        <li>Les pages fixes sont approuvées d'office ; les nouveaux contenus attendent votre approbation.</li>
                        <li>Les approbations déjà données sont conservées.</li>
                        <li>Les adresses qui n'existent plus (contenu supprimé ou dépublié) sont retirées. Les adresses ajoutées à la main sont gardées.</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form method="POST" action="{{ route('admin.sitemap.discover') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Lancer la découverte
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Génération --}}
<div class="modal fade" id="generateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🚀 Générer le Sitemap XML</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Cette action va générer le fichier <code>sitemap.xml</code> avec toutes les URLs <strong>approuvées et actives</strong>.</p>
                <div class="alert alert-success">
                    <strong>URLs qui seront incluses :</strong> {{ $statistics['approved_urls'] ?? 0 }}
                </div>
                <div class="alert alert-info">
                    <strong>📍 Le sitemap sera accessible sur :</strong><br>
                    <code>{{ route('sitemap.xml') }}</code>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form method="POST" action="{{ route('admin.sitemap.generate') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-rocket me-2"></i>Générer maintenant
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Ajouter URL --}}
<div class="modal fade" id="addUrlModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.sitemap.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">➕ Ajouter une URL manuelle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">URL *</label>
                        <input type="url" name="url" class="form-control" placeholder="{{ url('/') }}/page-exemple" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Priorité</label>
                        <input type="number" name="priority" class="form-control" value="0.5" step="0.1" min="0" max="1">
                        <small class="text-muted">Entre 0.0 et 1.0 (1.0 = priorité maximale)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fréquence de changement</label>
                        <select name="changefreq" class="form-select">
                            <option value="always">Toujours</option>
                            <option value="hourly">Toutes les heures</option>
                            <option value="daily">Quotidien</option>
                            <option value="weekly" selected>Hebdomadaire</option>
                            <option value="monthly">Mensuel</option>
                            <option value="yearly">Annuel</option>
                            <option value="never">Jamais</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Nettoyage --}}
<div class="modal fade" id="cleanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">⚠️ Nettoyer toutes les URLs</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <strong>⚠️ ATTENTION :</strong> Cette action va supprimer <strong>TOUTES</strong> les URLs de la base de données de manière irréversible.
                </div>
                <p>Vous devrez relancer la découverte et réapprouver les URLs après cette action.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form method="POST" action="{{ route('admin.sitemap.clean') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Confirmer le nettoyage
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@extends('layouts.admin')

@section('title', 'Licence FFN — ' . $license->full_name)

@section('content')
<div class="ffn-licenses-admin">
    @include('admin.ffn-licenses._nav', ['active' => 'index'])

    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
        <div>
            <h2 class="h4 fw-bold mb-1">{{ $license->full_name }}</h2>
            <p class="text-muted mb-0">IUF {{ $license->iuf }} · saison FFN {{ $license->season }}
                @if ($license->license_type)
                    · <span class="badge bg-primary">{{ $license->license_type }}</span>
                @endif
                @if ($license->license_status)
                    <span class="badge bg-light text-dark border">{{ $license->license_status }}</span>
                @endif
            </p>
        </div>
        <a href="{{ route('admin.ffn-licenses.index', ['saison' => $license->season]) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>Liste des licenciés
        </a>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h3 class="h6 fw-bold mb-3"><i class="fas fa-user text-primary me-2" aria-hidden="true"></i>Identité et contact</h3>
                    <dl class="row small mb-0">
                        <dt class="col-sm-4 text-muted fw-normal">Civilité</dt>
                        <dd class="col-sm-8">{{ $license->civility ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted fw-normal">Naissance</dt>
                        <dd class="col-sm-8">{{ $license->birth_date->format('d/m/Y') }} ({{ $license->age }} ans)</dd>
                        <dt class="col-sm-4 text-muted fw-normal">Nationalité</dt>
                        <dd class="col-sm-8">{{ $license->nationality ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted fw-normal">Adresse</dt>
                        <dd class="col-sm-8">{{ $license->full_address ?? '—' }}{{ $license->country && $license->country !== 'FRA' ? ' (' . $license->country . ')' : '' }}</dd>
                        <dt class="col-sm-4 text-muted fw-normal">Téléphone(s)</dt>
                        <dd class="col-sm-8">
                            @forelse ($license->phones as $tel)
                                <a href="tel:{{ \App\Models\FfnLicense::phoneHref($tel) }}" class="d-block">{{ $tel }}</a>
                            @empty
                                —
                            @endforelse
                        </dd>
                        <dt class="col-sm-4 text-muted fw-normal">E-mail</dt>
                        <dd class="col-sm-8">
                            @if ($license->email)
                                <a href="mailto:{{ $license->email }}" class="text-break">{{ $license->email }}</a>
                            @else
                                —
                            @endif
                        </dd>
                        <dt class="col-sm-4 text-muted fw-normal">Mails FFN</dt>
                        <dd class="col-sm-8 mb-0">{{ $license->ffn_mail_consent === null ? '—' : ($license->ffn_mail_consent ? 'accepte' : 'refuse') }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h3 class="h6 fw-bold mb-3"><i class="fas fa-id-card text-primary me-2" aria-hidden="true"></i>Licence</h3>
                    <dl class="row small mb-0">
                        <dt class="col-sm-5 text-muted fw-normal">Homologation</dt>
                        <dd class="col-sm-7">{{ optional($license->homologated_on)->format('d/m/Y') ?? '—' }}</dd>
                        <dt class="col-sm-5 text-muted fw-normal">Certificat médical</dt>
                        <dd class="col-sm-7">{{ optional($license->medical_certificate_on)->format('d/m/Y') ?? 'non renseigné' }}</dd>
                        <dt class="col-sm-5 text-muted fw-normal">Activités</dt>
                        <dd class="col-sm-7">
                            @forelse ($license->activity_labels as $activite)
                                <span class="badge bg-light text-dark border mb-1">{{ $activite }}</span>
                            @empty
                                —
                            @endforelse
                        </dd>
                        <dt class="col-sm-5 text-muted fw-normal">Club</dt>
                        <dd class="col-sm-7">{{ $license->club_name ?? '—' }}{{ $license->club_code ? ' (' . $license->club_code . ')' : '' }}</dd>
                        <dt class="col-sm-5 text-muted fw-normal">Région / département</dt>
                        <dd class="col-sm-7">{{ $license->region ?? '—' }} / {{ $license->department ?? '—' }}</dd>
                        @if ($otherSeasons->isNotEmpty())
                            <dt class="col-sm-5 text-muted fw-normal">Autres saisons</dt>
                            <dd class="col-sm-7 mb-0">
                                @foreach ($otherSeasons as $autre)
                                    <a href="{{ route('admin.ffn-licenses.show', $autre->id) }}" class="me-2">{{ $autre->season }}</a>
                                @endforeach
                            </dd>
                        @endif
                    </dl>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="h6 fw-bold mb-3"><i class="fas fa-person-swimming text-primary me-2" aria-hidden="true"></i>Nageur du planning</h3>
                    @if ($license->swimmer)
                        <p class="mb-1">
                            <a href="{{ route('admin.planning.swimmers.edit', $license->swimmer->id) }}">{{ $license->swimmer->full_name }}</a>
                            @foreach ($license->swimmer->groups as $groupe)
                                <span class="badge" style="background: {{ $groupe->background_color }}; color: {{ $groupe->text_color }};">{{ $groupe->name }}</span>
                            @endforeach
                        </p>
                        <p class="small text-muted mb-0">Lien établi à l'import (n° de licence ou nom et année de naissance).</p>
                    @else
                        <p class="small text-muted mb-2">Aucune fiche nageur liée. Renseignez le n° de licence <strong>{{ $license->iuf }}</strong> dans la fiche du nageur puis réimportez, ou créez la fiche :</p>
                        <a href="{{ route('admin.planning.swimmers.create') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus me-1" aria-hidden="true"></i>Ajouter un nageur
                        </a>
                    @endif
                    @if ($license->import)
                        <p class="small text-muted mt-3 mb-0">
                            Importé le {{ $license->import->created_at->format('d/m/Y') }}{{ $license->import->user ? ' par ' . $license->import->user->name : '' }}
                            ({{ $license->import->original_name }}).
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

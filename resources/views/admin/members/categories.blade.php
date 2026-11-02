@extends($layout)

@section('title', 'Catégories et tarifs')

@section('content')
@php $r = fn ($n, $p = []) => route($area . '.members.' . $n, $p); @endphp
<div class="container-lg">
    <h1 class="h3 fw-bold mb-3">Catégories et tarifs</h1>
    @include('admin.members._nav')
    <p class="text-muted">Le tarif d'une catégorie est proposé à la création d'une adhésion. Le modifier ici ne change pas les adhésions déjà enregistrées.</p>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="table-responsive position-relative">
            <table class="table align-middle mb-0">
                <caption class="visually-hidden">Catégories d'adhésion</caption>
                <thead class="table-light"><tr><th scope="col">Code</th><th scope="col">Libellé</th><th scope="col">Tarif (€)</th><th scope="col">Ordre</th><th scope="col">Active</th><th scope="col">Adhésions</th><th scope="col"><span class="visually-hidden">Actions</span></th></tr></thead>
                <tbody>
                    @foreach ($categories as $c)
                        <tr>
                            <td colspan="5" class="p-0">
                                <form method="POST" action="{{ $r('categories.update', $c) }}" class="d-flex flex-wrap gap-2 align-items-center p-2" id="cat-{{ $c->id }}">
                                    @csrf @method('PUT')
                                    <label class="visually-hidden" for="code-{{ $c->id }}">Code</label>
                                    <input id="code-{{ $c->id }}" name="code" value="{{ $c->code }}" maxlength="40" class="form-control form-control-sm text-uppercase" style="width: 170px" required>
                                    <label class="visually-hidden" for="label-{{ $c->id }}">Libellé</label>
                                    <input id="label-{{ $c->id }}" name="label" value="{{ $c->label }}" maxlength="120" class="form-control form-control-sm" style="flex: 1 1 220px" required>
                                    <label class="visually-hidden" for="price-{{ $c->id }}">Tarif</label>
                                    <input id="price-{{ $c->id }}" name="price" value="{{ number_format($c->price_cents / 100, 2, ',', '') }}" inputmode="decimal" class="form-control form-control-sm" style="width: 100px" required>
                                    <label class="visually-hidden" for="order-{{ $c->id }}">Ordre</label>
                                    <input id="order-{{ $c->id }}" name="sort_order" type="number" min="0" value="{{ $c->sort_order }}" class="form-control form-control-sm" style="width: 75px">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="is_active" value="0">
                                        <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" id="act-{{ $c->id }}" @checked($c->is_active)>
                                        <label class="form-check-label visually-hidden" for="act-{{ $c->id }}">Active</label>
                                    </div>
                                </form>
                            </td>
                            <td class="small">{{ $c->memberships_count }}</td>
                            <td class="text-end text-nowrap">
                                <button form="cat-{{ $c->id }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-save" aria-hidden="true"></i><span class="visually-hidden">Enregistrer {{ $c->code }}</span></button>
                                <form method="POST" action="{{ $r('categories.destroy', $c) }}" class="d-inline" onsubmit="return confirm('Supprimer la catégorie {{ $c->code }} ?')">@csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash" aria-hidden="true"></i><span class="visually-hidden">Supprimer {{ $c->code }}</span></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <form method="POST" action="{{ $r('categories.store') }}" class="card border-0 shadow-sm">
        @csrf
        <div class="card-body">
            <h2 class="h6 fw-bold">Ajouter une catégorie</h2>
            <div class="d-flex flex-wrap gap-2">
                <label class="visually-hidden" for="new-code">Code</label>
                <input id="new-code" name="code" placeholder="CODE" maxlength="40" class="form-control text-uppercase" style="width: 170px" required>
                <label class="visually-hidden" for="new-label">Libellé</label>
                <input id="new-label" name="label" placeholder="Libellé" maxlength="120" class="form-control" style="flex: 1 1 220px" required>
                <label class="visually-hidden" for="new-price">Tarif</label>
                <input id="new-price" name="price" placeholder="Tarif (€)" inputmode="decimal" class="form-control" style="width: 110px" required>
                <input type="hidden" name="is_active" value="1">
                <button class="btn btn-primary">Ajouter</button>
            </div>
        </div>
    </form>
</div>
@endsection

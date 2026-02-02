@extends('layouts.admin')

@section('title', 'Modifier une fiche')
@section('page-title', 'Modifier la fiche')
@section('page-description', 'Modification de la fiche : ' . $fiche->title)

@section('content')
<div class="container-fluid">
    <form method="POST" action="{{ route('admin.fiches.update', $fiche) }}">
        @method('PUT')
        @include('admin.fiches.partials.form', [
            'submitLabel' => 'Mettre à jour la fiche',
            'fiche' => $fiche,
            'categories' => $categories,
            'sousCategories' => $sousCategories
        ])
    </form>
</div>
@endsection
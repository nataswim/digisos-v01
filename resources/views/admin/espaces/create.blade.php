@extends('layouts.admin')

@section('title', 'Créer un espace')
@section('page-title', 'Nouvel espace')
@section('page-description', 'Création d\'une nouvelle subdivision thématique')

@section('content')
<div class="container-fluid">
    <form method="POST"
          action="{{ route('admin.espaces.store') }}"
          enctype="multipart/form-data">
        @include('admin.espaces.partials.form', [
            'submitLabel' => 'Créer l\'espace',
            'services'    => $services,
            'structures'  => $structures,
        ])
    </form>
</div>
@endsection

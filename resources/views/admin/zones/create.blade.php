@extends('layouts.admin')

@section('title', 'Créer une zone')
@section('page-title', 'Nouvelle zone')
@section('page-description', 'Création d\'un nouveau lieu physique précis')

@section('content')
<div class="container-fluid">
    <form method="POST"
          action="{{ route('admin.zones.store') }}"
          enctype="multipart/form-data">
        @include('admin.zones.partials.form', [
            'submitLabel' => 'Créer la zone',
            'services'    => $services,
            'structures'  => $structures,
            'espaces'     => $espaces,
        ])
    </form>
</div>
@endsection

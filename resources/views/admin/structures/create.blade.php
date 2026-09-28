@extends('layouts.admin')

@section('title', 'Créer une structure')
@section('page-title', 'Nouvelle structure')
@section('page-description', 'Création d\'une nouvelle division fonctionnelle')

@section('content')
<div class="container-fluid">
    <form method="POST"
          action="{{ route('admin.structures.store') }}"
          enctype="multipart/form-data">
        @include('admin.structures.partials.form', [
            'submitLabel' => 'Créer la structure',
            'services'    => $services,
        ])
    </form>
</div>
@endsection

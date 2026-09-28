@extends('layouts.admin')

@section('title', 'Créer un service')
@section('page-title', 'Nouveau service')
@section('page-description', 'Création d\'une nouvelle installation principale')

@section('content')
<div class="container-fluid">
    <form method="POST"
          action="{{ route('admin.services.store') }}"
          enctype="multipart/form-data">
        @include('admin.services.partials.form', [
            'submitLabel' => 'Créer le service',
        ])
    </form>
</div>
@endsection

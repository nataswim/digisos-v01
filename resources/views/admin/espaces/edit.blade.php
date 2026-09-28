@extends('layouts.admin')

@section('title', 'Modifier l\'espace')
@section('page-title', 'Modifier l\'espace')
@section('page-description', 'Modification : ' . $espace->name)

@section('content')
<div class="container-fluid">
    <form method="POST"
          action="{{ route('admin.espaces.update', $espace) }}"
          enctype="multipart/form-data">
        @method('PUT')
        @include('admin.espaces.partials.form', [
            'submitLabel' => 'Mettre à jour l\'espace',
            'espace'      => $espace,
            'services'    => $services,
            'structures'  => $structures,
        ])
    </form>
</div>
@endsection

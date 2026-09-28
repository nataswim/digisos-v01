@extends('layouts.admin')

@section('title', 'Modifier la structure')
@section('page-title', 'Modifier la structure')
@section('page-description', 'Modification : ' . $structure->name)

@section('content')
<div class="container-fluid">
    <form method="POST"
          action="{{ route('admin.structures.update', $structure) }}"
          enctype="multipart/form-data">
        @method('PUT')
        @include('admin.structures.partials.form', [
            'submitLabel' => 'Mettre à jour la structure',
            'structure'   => $structure,
            'services'    => $services,
        ])
    </form>
</div>
@endsection

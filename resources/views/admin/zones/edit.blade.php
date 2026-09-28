@extends('layouts.admin')

@section('title', 'Modifier la zone')
@section('page-title', 'Modifier la zone')
@section('page-description', 'Modification : ' . $zone->name)

@section('content')
<div class="container-fluid">
    <form method="POST"
          action="{{ route('admin.zones.update', $zone) }}"
          enctype="multipart/form-data">
        @method('PUT')
        @include('admin.zones.partials.form', [
            'submitLabel' => 'Mettre à jour la zone',
            'zone'        => $zone,
            'services'    => $services,
            'structures'  => $structures,
            'espaces'     => $espaces,
        ])
    </form>
</div>
@endsection

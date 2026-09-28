@extends('layouts.admin')

@section('title', 'Modifier le service')
@section('page-title', 'Modifier le service')
@section('page-description', 'Modification : ' . $service->name)

@section('content')
<div class="container-fluid">
    <form method="POST"
          action="{{ route('admin.services.update', $service) }}"
          enctype="multipart/form-data">
        @method('PUT')
        @include('admin.services.partials.form', [
            'submitLabel' => 'Mettre à jour le service',
            'service'     => $service,
        ])
    </form>
</div>
@endsection

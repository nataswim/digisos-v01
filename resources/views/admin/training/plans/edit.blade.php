@extends('layouts.admin')

@section('title', 'Modifier un plan')
@section('page-title', 'Modifier le plan')
@section('page-description', 'Modification du plan : ' . $plan->titre)

@section('content')
<div class="container-fluid">
    <form method="POST" action="{{ route('admin.training.plans.update', $plan) }}">
        @method('PUT')
        @include('admin.training.plans.partials.form', [
            'submitLabel' => 'Mettre à jour le plan',
            'plan' => $plan,
            'cycles' => $cycles
        ])
    </form>
</div>
@endsection

@push('styles')

@endpush
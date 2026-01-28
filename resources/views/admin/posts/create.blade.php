@extends('layouts.admin')

@section('title', 'Creer un article')
@section('page-title', 'Nouvel article')
@section('page-description', 'Creation d\'un nouvel article')

@section('content')
<div class="container-fluid">
    <form method="POST" action="{{ route('admin.posts.store') }}">
        @include('admin.posts.partials.form', [
            'submitLabel' => 'Creer l\'article',
            'categories' => $categories,
            'tags' => $tags
        ])
    </form>
</div>
@endsection

@push('styles')
<style>
.max-height-200 {
    max-height: 200px;
}







.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #10b981 100%);
}
</style>
@endpush


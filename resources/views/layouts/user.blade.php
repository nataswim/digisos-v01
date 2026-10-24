<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Espace Utilisateur</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @include('layouts.partials.espace-head')
    @stack('styles')
</head>
<body class="espace espace--member">
    @include('layouts.partials.user-header')
    
    @if(session('success') || session('error') || session('warning'))
        <div class="container-lg">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    @endif
    
    <main>
        @yield('content')
    </main>
    
    @include('layouts.partials.user-footer')
    
    <script src="{{ mix('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
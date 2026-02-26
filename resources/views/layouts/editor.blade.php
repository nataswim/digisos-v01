<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Espace Éditeur</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    @stack('styles')
</head>
<body style="background-color: rgb(250, 250, 245);background-image: linear-gradient(53deg, rgb(249, 245, 244) 85%, rgb(74 171 202) 70px);background-position: center top;background-attachment: fixed;border-right: 20px solid rgb(74 171 202);">
    @include('layouts.partials.editor-nav-horizontal')
    
    <div class="main-wrapper">
        <div class="container-fluid">
            <main class="py-4">
                @if(session('success') || session('error'))
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    @include('layouts.partials.editor-footer')
    
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script src="{{ asset('js/quill-advanced.js') }}"></script>
    <script src="{{ asset('js/media-selector.js') }}"></script>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
</body>
</html>
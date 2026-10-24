<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mon espace') - CNBB</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @include('layouts.partials.espace-head')
    @stack('styles')
    <meta name="msvalidate.01" content="8D79868FFCAC25E19818E1971977FC3F" />
</head>
<body class="espace espace--member">
    <div class="min-h-screen">
        @include('layouts.partials.user-header')
        @include('layouts.partials.user-nav')

        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main>
            @yield('content')
        </main>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
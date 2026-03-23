<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — {{ config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #fafaf5;
            background-image: linear-gradient(135deg, #fafbf5 85%, #4ccac6 0);
            background-attachment: fixed;
        }

        .error-wrapper {
            text-align: center;
            padding: 2rem;
            max-width: 560px;
            width: 100%;
        }

        .error-code {
            font-size: 7rem;
            font-weight: 700;
            line-height: 1;
            background: linear-gradient(135deg, #4097b5 0%, #4ccac6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0;
        }

        .error-icon {
            font-size: 3rem;
            color: #4097b5;
            margin-bottom: 1rem;
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 0.75rem;
        }

        .error-message {
            color: #6c757d;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .error-divider {
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #4097b5, #4ccac6);
            border-radius: 2px;
            margin: 1.25rem auto;
        }

        .btn-home {
            background: linear-gradient(135deg, #4097b5, #4ccac6);
            border: none;
            color: white;
            padding: .65rem 1.75rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            transition: opacity .2s ease, transform .2s ease;
        }

        .btn-home:hover {
            color: white;
            opacity: .9;
            transform: translateY(-2px);
        }

        .btn-back {
            color: #4097b5;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            margin-left: 1rem;
            transition: color .2s ease;
        }

        .btn-back:hover {
            color: #4ccac6;
        }

        .error-logo {
            margin-bottom: 2rem;
        }

        .error-logo img {
            height: 60px;
            width: auto;
        }

        .error-footer {
            margin-top: 3rem;
            color: #adb5bd;
            font-size: .8rem;
        }
    </style>
</head>
<body>

    <div class="error-wrapper">

 

        {{-- Contenu de la page d'erreur --}}
        @yield('content')

        {{-- Footer minimaliste --}}
        <p class="error-footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}
        </p>

    </div>

</body>
</html>

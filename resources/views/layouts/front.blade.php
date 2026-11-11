<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SehatKita - Blog Kesehatan')</title>
    <meta name="description" content="@yield('description', 'Artikel kesehatan yang mudah dipahami: gizi, kebugaran, tidur, dan kesehatan mental.')">

    <link rel="stylesheet" href="{{ asset('css/beranda.css') }}">
    @stack('styles')
</head>
<body>

    @include('partials.front.icons')
    @include('partials.front.header')

    <main class="wrap">
        @yield('content')
    </main>

    @include('partials.front.footer')

    @stack('scripts')
</body>
</html>

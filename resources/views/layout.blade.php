<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{config('app.name')}}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/assets/css/app.css'])

    @stack('styles')
</head>

<body class="min-h-screen bg-gray-100 dark:bg-zinc-900 pt-12">
    @include('partials.navbar')

    <div class="container">
        @yield('content')
    </div>

    @vite(['resources/assets/js/app.js'])

    @stack('scripts')
</body>

</html>
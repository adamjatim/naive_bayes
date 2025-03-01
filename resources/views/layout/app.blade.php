<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Naive Bayes - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
</head>

<body>
    <div class="flex flex-col min-h-screen">
        @if (Auth::check())
            @include('layout.navbar')
        @endif

        @yield('content')

        @include('layout.footer')
    </div>
</body>

</html>

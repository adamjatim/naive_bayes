<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Naive Bayes - @yield('title')</title>
    {{-- @vite('resources/css/app.css') --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    @auth
        @include('layout.navbar')
    @endauth

    <div class="flex flex-col  min-h-screen">
        @yield('content')

        @include('layout.footer')
    </div>
</body>

</html>

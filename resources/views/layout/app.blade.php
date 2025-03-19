<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Naive Bayes - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/navbar.css', 'resources/js/sweetalert2@11.js', 'resources/js/sweetalert2.all.min.js', 'resources/css/sweetalert2.min.css'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('styles')
</head>

<body>
    <div class="flex flex-col min-h-screen">
        {{-- @include('sweetalert::alert') --}}
        @yield('modals')

        @if (Auth::check())
            @include('layout.navbar')
        @endif

        @yield('content')

        @include('layout.footer')

        @if (session()->has('message'))
            <script type="text/javascript">
                // SweetAlert Toast example
                function showToast(type, message) {
                    const Toast = Sweetalert2.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                    Toast.fire({
                        icon: type,
                        type: type,
                        title: message,
                    });

                }
            </script>
            <script>
                showToast("{{ strtolower(session('message')['type']) }}", "{{ session('message')['text'] }}");
            </script>
        @endif
        @if ($errors->any())
            <script type="text/javascript">
                function showErrorToast(type, message) {
                    const Toast = Sweetalert2.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                    Toast.fire({
                        icon: type,
                        title: message,
                    });

                }
                showErrorToast('warning', '{{ $errors->first() }}');
            </script>
        @endif

        @yield('scripts')
    </div>

</body>

</html>

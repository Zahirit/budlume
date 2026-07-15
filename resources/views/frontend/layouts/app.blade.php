<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Budlume')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body>


    @include('frontend.partials.header')

    <main>
        @yield('content')
    </main>

    @include('frontend.partials.footer')

    @stack('scripts')

    <script>
    function toggleUserMenu() {
        document.getElementById('userDropdown').classList.toggle('show');
    }

    document.addEventListener('click', function (event) {
        const menu = document.querySelector('.header-user-menu');
        const dropdown = document.getElementById('userDropdown');

        if (menu && dropdown && !menu.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    });
</script>

</body>
</html>
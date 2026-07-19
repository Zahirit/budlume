<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    {{-- Primary SEO --}}
    <title>@yield('title', 'Budlume')</title>

    <meta name="description"
          content="@yield('meta_description', 'Discover premium quality products at Budlume. Browse our collection and shop online with ease.')">

    <meta name="robots"
          content="@yield('robots', 'index, follow')">

    {{-- Canonical URL --}}
    <link rel="canonical"
          href="@yield('canonical', url()->current())">


    {{-- Open Graph / Facebook --}}
    <meta property="og:type"
          content="@yield('og_type', 'website')">

    <meta property="og:title"
          content="@yield('og_title', 'Budlume')">

    <meta property="og:description"
          content="@yield('og_description', 'Discover premium quality products at Budlume.')">

    <meta property="og:url"
          content="@yield('canonical', url()->current())">

    <meta property="og:site_name"
          content="Budlume">

    <meta property="og:image"
          content="@yield('og_image', asset('uploads/frontend/budlume-hero-bright.png'))">


    {{-- Twitter / Social Sharing --}}
    <meta name="twitter:card"
          content="summary_large_image">

    <meta name="twitter:title"
          content="@yield('og_title', 'Budlume')">

    <meta name="twitter:description"
          content="@yield('og_description', 'Discover premium quality products at Budlume.')">

    <meta name="twitter:image"
          content="@yield('og_image', asset('uploads/frontend/budlume-hero-bright.png'))">


    {{-- Bootstrap Icons --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    {{-- Application Assets --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])


    {{-- Page Specific Styles --}}
    @stack('styles')


    {{-- Structured Data / Schema --}}
    @stack('schema')

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

            const dropdown =
                document.getElementById('userDropdown');

            if (dropdown) {

                dropdown.classList.toggle('show');

            }

        }


        document.addEventListener('click', function (event) {

            const menu =
                document.querySelector('.header-user-menu');

            const dropdown =
                document.getElementById('userDropdown');


            if (
                menu &&
                dropdown &&
                !menu.contains(event.target)
            ) {

                dropdown.classList.remove('show');

            }

        });

    </script>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VELORA</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body>
    <div class="page-shell">
        <header>
            <button id="theme-toggle" class="theme-toggle" type="button">Ciemny motyw</button>
            <div class="site-brand">
                <img src="{{ asset('images/velora-logo.png') }}" alt="VELORA logo" class="site-logo">
                <div>
                    <h1>VELORA</h1>
                    <p class="site-tagline">Wear your soul</p>
                </div>
            </div>
            @yield('header')
            @include('menu')
        </header>

        <main>
            <div class="main">
                @yield('main')
            </div>
        </main>

        <footer>
            @yield('footer')
        </footer>
    </div>
</body>
</html>
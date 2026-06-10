@vite('resources/css/menu.css')
<div class="menu">
    <center>
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('about') }}">About</a>
        <a href="{{ route('contact') }}">Contact</a>

        @auth
            <a href="{{ route('shop') }}">Sklep</a>
            <a href="{{ route('cart') }}">Koszyk</a>
        @endauth

        @auth
            @if(auth()->user()->is_admin)
                <a href="{{ route('admin.orders') }}">Lista zamówień</a>
            @endif
        @endauth
    </center>
</div>
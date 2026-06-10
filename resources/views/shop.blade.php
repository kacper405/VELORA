@extends('layouts.default')

@section('header')
    <h2>Sklep VELORA</h2>
@endsection

@section('main')
    <p>Wybierz swoje ulubione ubrania i złóż zamówienie. Tę stronę widzisz tylko po zalogowaniu.</p>

    <div class="product-list">
        @foreach($products as $productKey => $product)
            <article class="product-card">
                <div class="product-photo">
                    @if(isset($product['image']))
                        <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}" class="product-image">
                    @else
                        <span>Brak zdjęcia</span>
                    @endif
                </div>

                <div class="product-details">
                    <h2>{{ $product['name'] }}</h2>
                    <p>{{ $product['description'] }}</p>
                    <span>{{ number_format($product['price'], 2, ',', ' ') }} zł</span>

                    <form method="POST" action="{{ route('cart.add') }}">
                        @csrf
                        <input type="hidden" name="product_key" value="{{ $productKey }}">
                        <button type="submit">Dodaj do koszyka</button>
                    </form>
                </div>
            </article>
        @endforeach
    </div>

    <div class="register-link">
        <a href="{{ route('cart') }}">Zobacz koszyk</a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Wyloguj</a>
    </div>

    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
        @csrf
    </form>
@endsection

@section('footer')
    <p>&copy; 2024 VELORA. Wszelkie prawa zastrzeżone.</p>
@endsection
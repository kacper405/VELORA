@extends('layouts.default')

@section('header')
    <h1>Koszyk</h1>
@endsection

@section('main')
    @if(session('status'))
        <div class="status">{{ session('status') }}</div>
    @endif

    @if($errors->any())
        <div class="errors">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(empty($cart))
        <p>Twój koszyk jest pusty. Przejdź do sklepu i dodaj produkty.</p>
    @else
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Produkt</th>
                    <th>Ilość</th>
                    <th>Cena</th>
                    <th>Wartość</th>
                </tr>
            </thead>
            <tbody>
                @php $sum = 0; @endphp
                @foreach($cart as $item)
                    @php $lineTotal = $item['price'] * $item['quantity']; $sum += $lineTotal; @endphp
                    <tr>
                        <td>{{ $item['product_name'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ number_format($item['price'], 2, ',', ' ') }} zł</td>
                        <td>{{ number_format($lineTotal, 2, ',', ' ') }} zł</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"><strong>Suma do zapłaty</strong></td>
                    <td><strong>{{ number_format($sum, 2, ',', ' ') }} zł</strong></td>
                </tr>
            </tfoot>
        </table>

        <div class="cart-actions">
            <form method="POST" action="{{ route('cart.checkout') }}">
                @csrf
                <button type="submit">Zapłać</button>
            </form>

            <a class="secondary-button" href="{{ route('shop') }}">Kontynuuj zakupy</a>

            <form method="POST" action="{{ route('cart.clear') }}">
                @csrf
                <button type="submit" class="secondary-button">Wyczyść koszyk</button>
            </form>
        </div>
    @endif
@endsection

@section('footer')
    <p>&copy; 2024 VELORA. Wszelkie prawa zastrzeżone.</p>
@endsection
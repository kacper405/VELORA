@extends('layouts.default')

@section('header')
    <h1>Lista zamówień</h1>
@endsection

@section('main')
    @if(session('status'))
        <div class="status">{{ session('status') }}</div>
    @endif

    @forelse($orders as $order)
        <section class="order-card">
            <h2>Zamówienie #{{ $order->id }}</h2>
            <p><strong>Użytkownik:</strong> {{ $order->user->name }}</p>
            <p><strong>Data:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>

            <p class="order-status {{ $order->status === 'completed' ? 'completed' : 'pending' }}">
                <strong>Status:</strong>
                {{ $order->status === 'completed' ? 'Zrealizowane' : 'Oczekujące' }}
            </p>

            <ul>
                @foreach($order->items as $item)
                    <li>{{ $item->product_name }} x{{ $item->quantity }} — {{ number_format($item->price, 2, ',', ' ') }} zł</li>
                @endforeach
            </ul>

            <p><strong>Suma do zapłaty:</strong> {{ number_format($order->total, 2, ',', ' ') }} zł</p>

            @if($order->status !== 'completed')
                <div class="order-actions">
                    <form method="POST" action="{{ route('admin.orders.complete', $order) }}">
                        @csrf
                        <button type="submit" class="complete">Zrealizuj</button>
                    </form>

                    <form method="POST" action="{{ route('admin.orders.reject', $order) }}">
                        @csrf
                        <button type="submit" class="reject">Odrzuć</button>
                    </form>
                </div>
            @endif
        </section>
    @empty
        <p>Brak zamówień do wyświetlenia.</p>
    @endforelse
@endsection

@section('footer')
    <p>&copy; 2024 VELORA. Wszelkie prawa zastrzeżone.</p>
@endsection
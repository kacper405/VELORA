@extends('layouts.default')

@section('header')
    <h2>Kontakt</h2>
@endsection

@section('main')
    <div class='contact-card'>
        <h2>VELORA</h2>
        <p>Chcesz się z nami skontaktować? <br> Oto dane kontaktowe:</p>

        <ul>
            <li><strong>Adres:</strong> ul. Przykładowa 12, 00-001 Warszawa</li>
            <li><strong>Telefon:</strong> +48 123 456 789</li>
            <li><strong>Email:</strong> kontakt@velora.pl</li>
        </ul>

        <p>Jesteśmy dostępni od poniedziałku do piątku w godzinach 9:00–17:00.</p>
    </div>
@endsection

@section('footer')
    <p>&copy; 2024 VELORA. Wszelkie prawa zastrzeone.</p>
@endsection

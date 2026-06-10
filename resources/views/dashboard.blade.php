@extends('layouts.default')

@section('header')
    <h2>Panel użytkownika</h2>
@endsection

@section('main')
    <p>Witaj, {{ auth()->user()->name }}!</p>
    <p>Jesteś zalogowany.</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Wyloguj</button>
    </form>
@endsection

@section('footer')
    <p>&copy; 2024 Moja Strona. Wszelkie prawa zastrzeżone.</p>
@endsection
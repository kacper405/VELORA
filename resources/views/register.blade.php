@extends('layouts.default')

@section('header')
    <h2>Rejestracja</h2>
@endsection

@section('main')
    @if($errors->any())
        <div class="errors">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register.submit') }}" class="register-form">
        @csrf

        <div>
            <label for="name">Imię</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
        </div>

        <div>
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label for="password">Hasło</label>
            <input id="password" type="password" name="password" required>
        </div>

        <div>
            <label for="password_confirmation">Potwierdź hasło</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
        </div>

        <div>
            <button type="submit">Załóż konto</button>
        </div>
    </form>
@endsection

@section('footer')
    <p>&copy; 2024 Moja Strona. Wszelkie prawa zastrzeżone.</p>
@endsection
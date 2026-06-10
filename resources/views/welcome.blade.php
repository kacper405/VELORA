@extends('layouts.default') 

@section('header')
    
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

    <form method="POST" action="{{ route('login.submit') }}" class="login-form">
        @csrf

        <div>
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div>
            <label for="password">Hasło</label>
            <input id="password" type="password" name="password" required>
        </div>

        <div>
            <button type="submit">Zaloguj</button>
        </div>
    </form>

    <div class="register-link">
        <a href="{{ route('register') }}">Rejestracja</a>
    </div>
@endsection

@section('footer')
    <p>&copy; 2024 Moja Strona. Wszelkie prawa zastrzeżone.</p>
@endsection
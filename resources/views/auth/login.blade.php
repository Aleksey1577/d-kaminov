{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Вход')

@section('content')
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6">Вход</h1>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="w-full border rounded px-3 py-2" required value="{{ old('email') }}">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Пароль</label>
                <input type="password" name="password" id="password" class="w-full border rounded px-3 py-2" required>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Войти
            </button>
        </form>
        <div class="mt-4 text-center">
            <p class="text-gray-600">Нет аккаунта? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Зарегистрироваться</a></p>
        </div>
    </div>
@endsection
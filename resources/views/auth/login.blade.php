{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Вход')
@section('seo_title', 'Вход в аккаунт | D-Kaminov')
@section('seo_description', 'Авторизация в интернет-магазине каминов и печей D-Kaminov.')

@section('content')
    <div class="min-h-[70vh] flex items-center justify-center">
        <div class="relative w-full max-w-4xl overflow-hidden rounded-2xl bg-white shadow-xl">
            <div class="grid md:grid-cols-2">
                <div class="hidden md:block bg-gradient-to-br from-orange-500 to-amber-600 text-white p-10">
                    <h2 class="text-3xl font-bold mb-4">С возвращением!</h2>
                    <p class="text-sm opacity-90 mb-6">Войдите, чтобы сохранять избранное, сравнивать товары и следить за заказами.</p>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-center gap-2"><span class="inline-block w-2 h-2 rounded-full bg-white"></span> Быстрый доступ к профилю</li>
                        <li class="flex items-center gap-2"><span class="inline-block w-2 h-2 rounded-full bg-white"></span> История заказов и статусы</li>
                        <li class="flex items-center gap-2"><span class="inline-block w-2 h-2 rounded-full bg-white"></span> Избранное и сравнение</li>
                    </ul>
                </div>

                <div class="p-8 md:p-10">
                    <h1 class="text-2xl font-bold mb-2 text-gray-900">Вход</h1>
                    <p class="text-sm text-gray-600 mb-6">Введите данные учётной записи</p>

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="email" required
                                   value="{{ old('email') }}"
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-orange-500 focus:ring focus:ring-orange-200 transition"
                                   autocomplete="email">
                            @error('email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label for="password" class="block text-sm font-medium text-gray-700">Пароль</label>
                            </div>
                            <input type="password" name="password" id="password" required
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-orange-500 focus:ring focus:ring-orange-200 transition"
                                   autocomplete="current-password">
                            @error('password')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Капча</label>
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center justify-center rounded-lg bg-gray-100 px-4 py-2 text-gray-800 font-semibold">
                                    {{ $captchaQuestion ?? '...' }} = ?
                                </span>
                                <input type="number" name="captcha" inputmode="numeric" pattern="[0-9]*" required
                                       class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 focus:border-orange-500 focus:ring focus:ring-orange-200 transition"
                                       placeholder="Ответ">
                            </div>
                            @error('captcha')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                                class="w-full rounded-lg bg-orange text-white py-3 font-semibold shadow hover:bg-orange-500 transition">
                            Войти
                        </button>
                    </form>

                    <div class="mt-6 text-center text-sm text-gray-600">
                        Нет аккаунта?
                        <a href="{{ route('register') }}" class="text-orange font-semibold hover:underline">Зарегистрироваться</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- resources/views/profile.blade.php --}}
@extends('layouts.app')

@section('title', 'Личный кабинет')

@section('content')
<div class="shell space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="eyebrow">Личный кабинет</div>
            <h1 class="section-title text-3xl sm:text-4xl">Здравствуйте, {{ $user->name }}</h1>
            <p class="section-lead text-base">Управляйте заказами, избранным и данными профиля в одном месте.</p>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="flex-shrink-0">
            @csrf
            <button type="submit" class="btn-ghost">Выйти</button>
        </form>
    </div>

    {{-- Быстрые показатели --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="surface p-4 flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-orange/10 text-orange flex items-center justify-center text-lg font-bold">
                {{ ($orders ?? collect())->count() }}
            </div>
            <div>
                <div class="text-sm text-slate-500">Заказов</div>
                <div class="font-semibold text-slate-900">История покупок</div>
            </div>
        </div>
        <a href="{{ route('favorites') }}" class="surface p-4 flex items-center gap-3 hover:-translate-y-0.5 transition">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-orange flex items-center justify-center text-lg font-bold">
                {{ $favoritesCount }}
            </div>
            <div>
                <div class="text-sm text-slate-500">Избранное</div>
                <div class="font-semibold text-slate-900">Сохранённые товары</div>
            </div>
        </a>
        <a href="{{ route('compare') }}" class="surface p-4 flex items-center gap-3 hover:-translate-y-0.5 transition">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-orange flex items-center justify-center text-lg font-bold">
                {{ $compareCount }}
            </div>
            <div>
                <div class="text-sm text-slate-500">Сравнение</div>
                <div class="font-semibold text-slate-900">Товары к выбору</div>
            </div>
        </a>
        <div class="surface p-4 flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold">
                {{ $user->created_at->format('d.m') }}
            </div>
            <div>
                <div class="text-sm text-slate-500">С нами с</div>
                <div class="font-semibold text-slate-900">{{ $user->created_at->format('d.m.Y') }}</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Профиль --}}
        <div class="surface p-6 space-y-4 lg:col-span-1">
            <div>
                <div class="eyebrow">Профиль</div>
                <h2 class="text-xl font-semibold mt-2">Ваши данные</h2>
            </div>
            <div class="space-y-2 text-slate-700">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">Имя</span>
                    <span class="font-semibold">{{ $user->name }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">Email</span>
                    <span class="font-semibold">{{ $user->email }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">Регистрация</span>
                    <span class="font-semibold">{{ $user->created_at->format('d.m.Y H:i') }}</span>
                </div>
            </div>
            <a href="#edit-profile" class="btn-primary w-full text-center">Редактировать профиль</a>
        </div>

        {{-- Заказы --}}
        <div class="surface p-6 space-y-4 lg:col-span-2">
            <div class="flex items-center justify-between">
                <div>
                    <div class="eyebrow">Мои заказы</div>
                    <h2 class="text-xl font-semibold mt-2">История покупок</h2>
                </div>
                <a href="{{ route('catalog') }}" class="btn-ghost text-sm">В каталог</a>
            </div>

            @if (($orders ?? collect())->isEmpty())
                <div class="surface-quiet p-5 text-slate-600">
                    У вас пока нет заказов. Начните с подборки в каталоге.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-slate-500 border-b border-gray-100">
                                <th class="py-3 pr-4">Номер</th>
                                <th class="py-3 pr-4">Дата</th>
                                <th class="py-3 pr-4">Статус</th>
                                <th class="py-3 pr-4">Сумма</th>
                                <th class="py-3">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($orders as $order)
                                <tr>
                                    <td class="py-3 pr-4 font-semibold text-slate-900">#{{ $order->id }}</td>
                                    <td class="py-3 pr-4">{{ $order->created_at->format('d.m.Y') }}</td>
                                    <td class="py-3 pr-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-100">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 pr-4 font-semibold text-slate-900">
                                        {{ number_format((float)($order->total ?? 0), 0, ',', ' ') }} ₽
                                    </td>
                                    <td class="py-3 text-slate-500">
                                        <span class="text-xs">Скоро</span>
                                        {{-- Добавьте ссылку на страницу заказа, когда будет готов маршрут --}}
                                        {{-- <a href="{{ route('order.show', $order) }}" class="text-orange font-semibold hover:underline">Подробнее</a> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Редактирование профиля --}}
    <div id="edit-profile" class="surface p-6 space-y-5">
        <div>
            <div class="eyebrow">Редактирование</div>
            <h2 class="text-xl font-semibold mt-2">Обновите данные</h2>
            <p class="text-sm text-slate-600">Мы не передаём вашу почту и имя третьим лицам.</p>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @csrf
            @method('PUT')

            <div class="space-y-2">
                <label for="name" class="text-sm font-medium text-slate-700">Имя</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    class="w-full rounded-xl border border-amber-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange/60"
                    value="{{ old('name', $user->name) }}"
                    required>
                @error('name')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="email" class="text-sm font-medium text-slate-700">Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="w-full rounded-xl border border-amber-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange/60"
                    value="{{ old('email', $user->email) }}"
                    required>
                @error('email')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2 flex flex-col sm:flex-row gap-3">
                <button type="submit" class="btn-primary flex-1 text-center">Сохранить изменения</button>
                <a href="{{ route('catalog') }}" class="btn-ghost flex-1 text-center">Вернуться в каталог</a>
            </div>
        </form>
    </div>
</div>
@endsection

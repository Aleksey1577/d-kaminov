@extends('layouts.app')

@section('title', 'Поиск товаров')
@section('seo_title', 'Поиск по каталогу каминов и печей | D-Kaminov')
@section('seo_description', request('search')
    ? 'Результаты поиска по запросу "' . e(request('search')) . '" в каталоге D-Kaminov: камины, топки, печи, аксессуары.'
    : 'Поиск по каталогу каминов, топок, печей и аксессуаров в D-Kaminov.')

@section('content')
    <div class="section p-5 sm:p-6 md:p-8 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="eyebrow">Поиск</div>
                <h1 class="section-title text-2xl sm:text-3xl">Результаты поиска</h1>
                @if(request('search'))
                    <p class="text-sm text-slate-600">Запрос: «{{ e(request('search')) }}»</p>
                @endif
            </div>
            <a href="{{ route('catalog') }}" class="btn-ghost text-sm">Вернуться в каталог</a>
        </div>

        @if ($products->isEmpty())
            <div class="surface p-5 text-center text-slate-600">
                Ничего не найдено. Попробуйте изменить запрос или перейти в каталог.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection

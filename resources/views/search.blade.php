@extends('layouts.app')

@section('title', 'Поиск товаров')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Результаты поиска</h1>

    @if ($products->isEmpty())
        <p class="text-gray-600">Ничего не найдено.</p>
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
@endsection
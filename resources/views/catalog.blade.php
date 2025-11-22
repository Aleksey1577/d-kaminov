@extends('layouts.app')

@section('title', $category ? 'Купить ' . $category : 'Каталог товаров')

@section('content')
    <h1 class="text-3xl font-bold mb-6">
        {{ $category ? 'Купить ' . $category : 'Каталог товаров' }}
    </h1>

    <div class="flex flex-col md:flex-row gap-5">
        <!-- Фильтры -->
        @include('components.filters', [
            'proizvoditeli' => $proizvoditeli,
            'v_nalichii_options' => $v_nalichii_options,
            'currentFilters' => request()->all()
        ])

        <!-- Сетка товаров -->
        <div class="w-4/5">
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-3">
                @foreach($products as $product)
                    @include('components.product-card', ['product' => $product])
                @endforeach
            </div>

            <!-- Пагинация -->
            <div class="mt-8">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Каталог товаров</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Каталог товаров</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($products as $product)
                <div class="bg-white rounded-2xl shadow hover:shadow-md transition p-4 flex flex-col">
                    <div class="h-48 w-full flex items-center justify-center overflow-hidden rounded-xl bg-gray-50">
                        @if ($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->naimenovanie }}" class="object-contain h-full">
                        @else
                            <img src="https://via.placeholder.com/200x180?text=Нет+фото" alt="Нет фото" class="object-contain h-full">
                        @endif
                    </div>
                    <h3 class="mt-4 font-semibold text-lg">{{ $product->naimenovanie }}</h3>
                    <div class="mt-auto text-green-600 font-bold text-lg">
                        {{ $product->price }} {{ $product->valyuta }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</body>
</html>

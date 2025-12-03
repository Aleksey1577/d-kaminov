{{-- resources/views/components/professional-installation.blade.php --}}
<section class="py-16 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Профессиональный монтаж</h2>
        <p class="text-lg text-gray-600 mb-10 max-w-3xl mx-auto">
            Мы не просто продаём оборудование — мы устанавливаем его
            <span class="font-semibold text-gray-900">под ключ</span> с официальной гарантией,
            аккуратным монтажом и выездом в удобное для вас время.
        </p>

        @php
            $items = [
                [
                    'icon'  => 'shield-check',
                    'title' => 'Гарантия 3 года',
                    'text'  => 'Официальная гарантия на все монтажные работы. Работаем по прозрачному договору — вы защищены на 100%.',
                ],
                [
                    'icon'  => 'user-group',
                    'title' => 'Бригада специалистов',
                    'text'  => 'Сертифицированные монтажники с опытом от 5 лет. Аккуратно работаем в жилых помещениях и соблюдаем технику безопасности.',
                ],
                [
                    'icon'  => 'bolt',
                    'title' => 'Быстрый выезд',
                    'text'  => 'Монтаж в день заказа или на следующий день — без долгих ожиданий и переносов сроков.',
                ],
            ];
        @endphp

        <div class="grid md:grid-cols-3 gap-6 mt-4">
            @foreach($items as $item)
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-200 p-6 border border-gray-100 text-left">
                    {{-- Нейтральный контейнер под иконку --}}
                    <div class="w-12 h-12 rounded-2xl bg-gray-50 border border-gray-200 flex items-center justify-center mb-4">
                        @switch($item['icon'])
                            @case('shield-check')
                                <x-heroicon-o-shield-check class="w-7 h-7 text-gray-600" />
                                @break

                            @case('user-group')
                                <x-heroicon-o-user-group class="w-7 h-7 text-gray-600" />
                                @break

                            @case('bolt')
                                <x-heroicon-o-bolt class="w-7 h-7 text-gray-600" />
                                @break
                        @endswitch
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        {{ $item['title'] }}
                    </h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        {{ $item['text'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- resources/views/components/professional-installation.blade.php --}}
<section class="py-12 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Профессиональный монтаж</h2>
        <p class="text-lg text-gray-600 mb-10 max-w-3xl mx-auto">
            Мы не просто продаём оборудование — мы устанавливаем его <span class="text-orange-600 font-semibold">под ключ</span> с официальной гарантией, профессиональной бригадой и выездом в день заказа
        </p>

        <div class="grid md:grid-cols-3 gap-6">
            @foreach([
            [
            'color' => 'orange',
            'title' => 'Гарантия 3 года',
            'text' => 'Официальная гарантия на все монтажные работы. Работаем строго по договору, вы защищены на 100%'
            ],
            [
            'color' => 'blue',
            'title' => 'Бригада специалистов',
            'text' => 'Сертифицированные монтажники с опытом от 5 лет. Только проверенные профессионалы — никаких случайных людей'
            ],
            [
            'color' => 'green',
            'title' => 'Быстрый выезд',
            'text' => 'Монтаж в день заказа или на следующий день. Без очередей, задержек и лишних ожиданий'
            ]
            ] as $item)
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="w-14 h-14 bg-{{ $item['color'] }}-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-{{ $item['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($item['color'] === 'orange')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        @elseif($item['color'] === 'blue')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        @endif
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $item['title'] }}</h3>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $item['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
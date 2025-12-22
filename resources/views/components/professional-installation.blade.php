<div class="section p-6 sm:p-8 md:p-10 mb-12">
    <div class="text-center">
        <div class="eyebrow inline-flex">Монтаж</div>
        <h2 class="section-title mt-3">Профессионально устанавливаем под ключ</h2>
        <p class="section-lead mx-auto text-base">
            Своя бригада монтажников, официальная гарантия и аккуратные работы даже в готовом интерьере.
        </p>
    </div>

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

        <div class="grid md:grid-cols-3 gap-6 mt-8">
            @foreach($items as $item)
                <div class="surface hover:-translate-y-1 transition-transform duration-200 p-6 text-left">

                    <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center mb-4">
                        @switch($item['icon'])
                            @case('shield-check')
                                <x-heroicon-o-shield-check class="w-7 h-7 text-amber-700" />
                                @break

                            @case('user-group')
                                <x-heroicon-o-user-group class="w-7 h-7 text-amber-700" />
                                @break

                            @case('bolt')
                                <x-heroicon-o-bolt class="w-7 h-7 text-amber-700" />
                                @break
                        @endswitch
                    </div>

                    <h3 class="text-lg font-semibold text-slate-900 mb-2">
                        {{ $item['title'] }}
                    </h3>
                    <p class="text-sm text-slate-700 leading-relaxed">
                        {{ $item['text'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</div>

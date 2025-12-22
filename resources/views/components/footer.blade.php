@php
    $categoryLinks = [
        'Биокамины' => 'Биокамины',
        'Газовые топки, уличные нагреватели' => 'Газовые топки, уличные нагреватели',
        'Электроочаги' => 'Электроочаги',
        'Дымоходы' => 'Дымоходы',
        'Печи, камины, каминокомплекты' => 'Печи, камины, каминокомплекты',
        'Порталы' => 'Порталы',
        'Топки' => 'Топки',
        'Каминокомплекты' => 'Каминокомплекты',
        'Вентиляция' => 'Вентиляция',
        'Каминное/печное литье' => 'Каминное/печное литье',
    ];
    $infoLinks = [
        ['label' => 'Доставка', 'url' => route('delivery')],
        ['label' => 'Монтаж', 'url' => route('montage')],
        ['label' => 'Портфолио', 'url' => route('portfolio')],
        ['label' => 'Контакты', 'url' => route('contacts')],
        ['label' => 'Политика конфиденциальности', 'url' => route('privacy.policy')],
        ['label' => 'Карта сайта', 'url' => route('sitemap')],
    ];
    $footerLinks = [
        ['label' => 'Политика конфиденциальности', 'url' => route('privacy.policy')],
        ['label' => 'Карта сайта', 'url' => route('sitemap')],
    ];
    $payments = ['VISA', 'MC', 'МИР'];
@endphp

<footer class="bg-slate-900 text-white mt-10 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-orange/10 via-transparent to-amber-100/10 pointer-events-none"></div>
    <div class="shell relative py-12 space-y-10 text-slate-100">
        <div class="flex flex-col md:flex-row items-start md:items-center gap-4 justify-between bg-white/5 border border-white/10 rounded-xl p-4">
            <div>
                <p class="text-sm text-slate-200 uppercase tracking-widest">Нужна консультация?</p>
                <h3 class="text-xl font-semibold text-slate-50">Подберём камин под ваши задачи и бюджет</h3>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="tel:+79179535850" class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-orange text-white hover:bg-orange-white transition text-sm">
                    Позвонить
                </a>
                <button @click="$dispatch('open-callback')" class="inline-flex items-center gap-2 px-4 py-2 rounded-md border border-white/30 text-sm hover:border-white text-slate-50">
                    Заказать звонок
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-3">
                <h3 class="text-lg font-semibold mb-2 text-slate-50">Дом каминов</h3>
                <p class="text-sm text-slate-100/85 leading-relaxed">
                    Помогаем выбрать, доставить и смонтировать камины и печи. Подберём решение под интерьер и бюджет.
                </p>
                <div class="flex items-center gap-2 text-xs text-slate-100/80">
                    <span class="px-2 py-1 rounded bg-white/5 border border-white/10">Доставка по РФ</span>
                    <span class="px-2 py-1 rounded bg-white/5 border border-white/10">Монтаж</span>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4 text-slate-50">Категории</h3>
                <ul class="space-y-2 text-sm text-slate-100/90">
                    @foreach ($categoryLinks as $label => $category)
                        <li>
                            <a href="{{ route('catalog', ['category' => \Illuminate\Support\Str::slug($category)]) }}" class="hover:text-orange-200 text-slate-100">
                                {{ $label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4 text-slate-50">Информация</h3>
                <ul class="space-y-2 text-sm text-slate-100/90">
                    @foreach ($infoLinks as $link)
                        <li>
                            <a href="{{ $link['url'] }}" class="hover:text-orange-200 text-slate-100">
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="text-sm text-white space-y-2">
                <h3 class="text-lg font-semibold mb-2 text-white">Контакты</h3>
                <p class="text-white">Телефон: <a href="tel:+79179535850" class="hover:text-orange-200 text-white font-semibold">+7 (917) 953-58-50</a></p>
                <p class="text-white">Email: <a href="mailto:info@d-kaminov.com" class="hover:text-orange-200 text-white font-semibold">info@d-kaminov.com</a></p>
                <p class="text-white">График: 10:00–19:30 без выходных</p>
                <div class="pt-2">
                    <h4 class="text-xs uppercase tracking-widest text-slate-50 mb-1">Юр. данные</h4>
                    <p class="leading-relaxed text-slate-50">
                        ИП Краснянский М.А.<br>
                        ИНН 631200408027<br>
                        ОГРНИП 325632700010391
                    </p>
                </div>
                <div class="pt-2">
                    <p class="text-xs text-slate-100/70">Мы принимаем:</p>
                    <div class="flex gap-2 mt-1 text-xs text-slate-100/80">
                        @foreach ($payments as $label)
                            <span class="px-2 py-1 rounded bg-white/5 border border-white/10">{{ $label }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-white/10 flex flex-col sm:flex-row gap-3 sm:gap-0 sm:justify-between text-sm text-white">
            <p class="text-white">©2010-{{ now()->year }} Дом каминов. Все права защищены.</p>
            <div class="flex items-center gap-4">
                @foreach ($footerLinks as $link)
                    <a href="{{ $link['url'] }}" class="hover:text-orange-200 text-white">{{ $link['label'] }}</a>
                @endforeach
            </div>
        </div>
    </div>
</footer>

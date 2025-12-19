@php
    $categoryName = $categoryName ?? '';
@endphp

<div x-data="{ open: false }" class="md:w-1/5">
    <!-- Кнопка для мобильных -->
    <button @click="open = !open"
        class="md:hidden w-full btn-primary justify-between mb-4">
        <span>Фильтры</span>
        <svg :class="{ 'rotate-180': open }" class="w-5 h-5 transition-transform"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open || window.innerWidth >= 768" x-cloak class="surface p-6 md:block">
        <form method="GET" action="{{ route('catalog') }}">
            <div class="space-y-5">
	                {{-- Скрытый category --}}
	                @if(request()->category)
	                <input type="hidden" name="category" value="{{ request()->category }}">
	                @endif
	
	                {{-- Скрытый sort (чтобы сохранялся при сабмите фильтров) --}}
	                @if(request()->filled('sort'))
	                    <input type="hidden" name="sort" value="{{ request()->sort }}">
	                @endif

	                {{-- Цена: диапазон от/до --}}
	                <div>
	                    <label class="block text-sm font-semibold text-slate-800 mb-2">Цена, ₽</label>
	                    <div class="flex space-x-2">
                        <input type="number" name="price_min" placeholder="От"
                            class="w-1/2 rounded-lg border border-amber-200 px-3 py-2 focus:border-orange focus:ring focus:ring-orange/20 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            value="{{ request()->price_min }}">
                        <input type="number" name="price_max" placeholder="До"
                            class="w-1/2 rounded-lg border border-amber-200 px-3 py-2 focus:border-orange focus:ring focus:ring-orange/20 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            value="{{ request()->price_max }}">
                    </div>
                </div>

                {{-- Производитель (общий) --}}
                <div>
                    <label for="proizvoditel" class="block text-sm font-semibold text-slate-800 mb-2">Производитель</label>
                    <select name="proizvoditel" id="proizvoditel"
                        class="w-full rounded-lg border border-amber-200 px-3 py-2 bg-white focus:border-orange focus:ring focus:ring-orange/20" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($proizvoditeli as $p)
                        <option value="{{ $p }}" @selected(request()->proizvoditel == $p)>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Категорийные фильтры --}}
                @php $opts = $filterOptions @endphp

                {{-- Биокамин --}}
                @if($categoryName === 'Биокамины')
                <div>
                    <label for="tip_tovara" class="block text-sm font-semibold text-slate-800 mb-2">Тип товара</label>
                    <select name="tip_tovara" id="tip_tovara"
                        class="w-full rounded-lg border border-amber-200 px-3 py-2 bg-white focus:border-orange focus:ring focus:ring-orange/20" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['tip_tovara'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->tip_tovara == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="obem_zalivaemogo_topliva" class="block text-sm font-semibold text-slate-800 mb-2">Объем топлива</label>
                    <select name="obem_zalivaemogo_topliva" id="obem_zalivaemogo_topliva"
                        class="w-full rounded-lg border border-amber-200 px-3 py-2 bg-white focus:border-orange focus:ring focus:ring-orange/20" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['obem_zalivaemogo_topliva'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->obem_zalivaemogo_topliva == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Каминное/печное литье --}}
                @if($categoryName === 'Каминное/печное литье')
                <div>
                    <label for="tip_tovara" class="block text-sm font-semibold text-slate-800 mb-2">Тип товара</label>
                    <select name="tip_tovara" id="tip_tovara"
                        class="w-full rounded-lg border border-amber-200 px-3 py-2 bg-white focus:border-orange focus:ring focus:ring-orange/20" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['tip_tovara'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->tip_tovara == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="material" class="block text-sm font-semibold text-slate-800 mb-2">Материал</label>
                    <select name="material" id="material"
                        class="w-full rounded-lg border border-amber-200 px-3 py-2 bg-white focus:border-orange focus:ring focus:ring-orange/20" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['material'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->material == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>

                @endif

                {{-- Газовые топки, уличные нагреватели --}}
                @if($categoryName === 'Газовые топки, уличные нагреватели')

                <div>
                    <label for="tip_gaza" class="block text-sm font-semibold text-slate-800 mb-2">Тип газа</label>
                    <select name="tip_gaza" id="tip_gaza"
                        class="w-full rounded-lg border border-amber-200 px-3 py-2 bg-white focus:border-orange focus:ring focus:ring-orange/20" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['tip_gaza'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->tip_gaza == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="tip_ustroystva" class="block text-sm font-semibold text-slate-800 mb-2">Тип устройства</label>
                    <select name="tip_ustroystva" id="tip_ustroystva"
                        class="w-full rounded-lg border border-amber-200 px-3 py-2 bg-white focus:border-orange focus:ring focus:ring-orange/20" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['tip_ustroystva'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->tip_ustroystva == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Вентиляция --}}
                @if($categoryName === 'Вентиляция')

                <div>
                    <label for="tip_tovara" class="block text-sm font-semibold text-slate-800 mb-2">Тип товара</label>
                    <select name="tip_tovara" id="tip_tovara"
                        class="w-full rounded-lg border border-amber-200 px-3 py-2 bg-white focus:border-orange focus:ring focus:ring-orange/20" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['tip_tovara'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->tip_tovara == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Каминокомплекты --}}
                @if($categoryName === 'Каминокомплекты')

                <!-- Размеры -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-800">Высота (мм)</label>
                    <div class="flex gap-2">
                        <input type="number" name="vysota_min" placeholder="От" class="w-1/2 rounded-lg border border-amber-200 px-3 py-2 focus:border-orange focus:ring focus:ring-orange/20 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" value="{{ request()->vysota_min }}">
                        <input type="number" name="vysota_max" placeholder="До" class="w-1/2 rounded-lg border border-amber-200 px-3 py-2 focus:border-orange focus:ring focus:ring-orange/20 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" value="{{ request()->vysota_max }}">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-800">Ширина (мм)</label>
                    <div class="flex gap-2">
                        <input type="number" name="shirina_min" placeholder="От" class="w-1/2 rounded-lg border border-amber-200 px-3 py-2 focus:border-orange focus:ring focus:ring-orange/20 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" value="{{ request()->shirina_min }}">
                        <input type="number" name="shirina_max" placeholder="До" class="w-1/2 rounded-lg border border-amber-200 px-3 py-2 focus:border-orange focus:ring focus:ring-orange/20 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" value="{{ request()->shirina_max }}">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-800">Глубина (мм)</label>
                    <div class="flex gap-2">
                        <input type="number" name="glubina_min" placeholder="От" class="w-1/2 rounded-lg border border-amber-200 px-3 py-2 focus:border-orange focus:ring focus:ring-orange/20 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" value="{{ request()->glubina_min }}">
                        <input type="number" name="glubina_max" placeholder="До" class="w-1/2 rounded-lg border border-amber-200 px-3 py-2 focus:border-orange focus:ring focus:ring-orange/20 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" value="{{ request()->glubina_max }}">
                    </div>
                </div>

                @endif

                {{-- Электроочаги --}}
                @if($categoryName === 'Электроочаги')

                <div>
                    <label for="tip_ochaga" class="block text-sm font-semibold text-slate-800 mb-2">Тип очага</label>
                    <select name="tip_ochaga" id="tip_ochaga"
                        class="w-full rounded-lg border border-amber-200 px-3 py-2 bg-white focus:border-orange focus:ring focus:ring-orange/20" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['tip_ochaga'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->tip_ochaga == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="pult_du" class="block text-sm font-semibold text-slate-800 mb-2">Пульт ду</label>
                    <select name="pult_du" id="pult_du"
                        class="w-full rounded-lg border border-amber-200 px-3 py-2 bg-white focus:border-orange focus:ring focus:ring-orange/20" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['pult_du'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->pult_du == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Печи, камины, каминокомплекты --}}
                @if($categoryName === 'Печи, камины, каминокомплекты')

                <div>
                    <label for="diametr_dymokhoda" class="block text-sm font-semibold text-slate-800 mb-2">Диаметр дымохода</label>
                    <select name="diametr_dymokhoda" id="diametr_dymokhoda"
                        class="w-full rounded-lg border border-amber-200 px-3 py-2 bg-white focus:border-orange focus:ring focus:ring-orange/20" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['diametr_dymokhoda'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->diametr_dymokhoda == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="prisoyedinenie_dymokhoda" class="block text-sm font-semibold text-slate-800 mb-2">Присоединение дымохода</label>
                    <select name="prisoyedinenie_dymokhoda" id="prisoyedinenie_dymokhoda"
                        class="w-full rounded-lg border border-amber-200 px-3 py-2 bg-white focus:border-orange focus:ring focus:ring-orange/20" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['prisoyedinenie_dymokhoda'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->prisoyedinenie_dymokhoda == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="forma_stekla_i_dverey" class="block text-sm font-semibold text-slate-800 mb-2">Форма дверки/стекла</label>
                    <select name="forma_stekla_i_dverey" id="forma_stekla_i_dverey"
                        class="w-full rounded-lg border border-amber-200 px-3 py-2 bg-white focus:border-orange focus:ring focus:ring-orange/20" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['forma_stekla_i_dverey'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->forma_stekla_i_dverey == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-800">Мощность</label>
                    <div class="flex gap-2">
                        <input type="number" name="moshchnost_min" placeholder="От" class="w-1/2 rounded-lg border border-amber-200 px-3 py-2 focus:border-orange focus:ring focus:ring-orange/20 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" value="{{ request()->moshchnost_min }}">
                        <input type="number" name="moshchnost_max" placeholder="До" class="w-1/2 rounded-lg border border-amber-200 px-3 py-2 focus:border-orange focus:ring focus:ring-orange/20 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" value="{{ request()->moshchnost_max }}">
                    </div>
                </div>

                <div>
                    <label for="sposob_otkrytiya_dvertsy" class="block text-sm font-semibold text-slate-800 mb-2">Способ открывания</label>
                    <select name="sposob_otkrytiya_dvertsy" id="sposob_otkrytiya_dvertsy"
                        class="w-full rounded-lg border border-amber-200 px-3 py-2 bg-white focus:border-orange focus:ring focus:ring-orange/20" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['sposob_otkrytiya_dvertsy'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->sposob_otkrytiya_dvertsy == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Остальные категории (Каминокомплекты, Электроочаги и т.п.) --}}
                @if(in_array($categoryName, [
                    'Каминокомплекты',
                    'Электроочаги',
                    'Порталы',
                    'Топки',
                    'Вентиляция',
                    'Печи, камины, каминокомплекты',
                    'Дымоходы',
                ], true))
                {{-- Для всех этих достаточно общих price+proizvoditel --}}
                {{-- (Мы уже показали их выше) --}}
                @endif

                {{-- Кнопка Применить --}}
                <button type="submit"
                    class="w-full btn-primary justify-center">
                    Применить
                </button>
            </div>
        </form>
    </div>
</div>

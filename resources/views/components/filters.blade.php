<div x-data="{ open: false }" class="md:w-1/5">
    <!-- Кнопка для мобильных -->
    <button @click="open = !open"
        class="md:hidden w-full bg-orange text-white px-4 py-2 rounded mb-4 flex justify-between">
        <span>Фильтры</span>
        <svg :class="{ 'rotate-180': open }" class="w-5 h-5 transition-transform"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open || window.innerWidth >= 768" x-cloak
        class="bg-white rounded-lg shadow-md p-6 md:block">
        <form method="GET" action="{{ route('catalog') }}">
            <div class="space-y-4">
                {{-- Скрытый category --}}
                @if(request()->category)
                <input type="hidden" name="category" value="{{ request()->category }}">
                @endif

                {{-- Цена: диапазон от/до --}}
                <div>
                    <label class="block text-gray-700 mb-2">Цена, ₽</label>
                    <div class="flex space-x-2">
                        <input type="number" name="price_min" placeholder="От"
                            class="w-1/2 border border-gray-500 rounded px-2 py-1 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            value="{{ request()->price_min }}">
                        <input type="number" name="price_max" placeholder="До"
                            class="w-1/2 border border-gray-500 rounded px-2 py-1 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            value="{{ request()->price_max }}">
                    </div>
                </div>

                {{-- Производитель (общий) --}}
                <div>
                    <label for="proizvoditel" class="block text-gray-700 mb-2">Производитель</label>
                    <select name="proizvoditel" id="proizvoditel"
                        class="w-full border border-gray-500 rounded px-3 py-2" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($proizvoditeli as $p)
                        <option value="{{ $p }}" @selected(request()->proizvoditel == $p)>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Категорийные фильтры --}}
                @php $opts = $filterOptions @endphp

                {{-- Биокамин --}}
                @if(request()->category === 'Биокамины')
                <div>
                    <label for="tip_tovara" class="block text-gray-700 mb-2">Тип товара</label>
                    <select name="tip_tovara" id="tip_tovara"
                        class="w-full border border-gray-500 rounded px-3 py-2" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['tip_tovara'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->tip_tovara == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="obem_zalivaemogo_topliva" class="block text-gray-700 mb-2">Объем топлива</label>
                    <select name="obem_zalivaemogo_topliva" id="obem_zalivaemogo_topliva"
                        class="w-full border border-gray-500 rounded px-3 py-2" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['obem_zalivaemogo_topliva'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->obem_zalivaemogo_topliva == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Каминное/печное литье --}}
                @if(request()->category === 'Каминное/печное литье')
                <div>
                    <label for="tip_tovara" class="block text-gray-700 mb-2">Тип товара</label>
                    <select name="tip_tovara" id="tip_tovara"
                        class="w-full border border-gray-500 rounded px-3 py-2" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['tip_tovara'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->tip_tovara == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="material" class="block text-gray-700 mb-2">Материал</label>
                    <select name="material" id="material"
                        class="w-full border border-gray-500 rounded px-3 py-2" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['material'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->material == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>

                @endif

                {{-- Газовые топки, уличные нагреватели --}}
                @if(request()->category === 'Газовые топки, уличные нагреватели')

                <div>
                    <label for="tip_gaza" class="block text-gray-700 mb-2">Тип газа</label>
                    <select name="tip_gaza" id="tip_gaza"
                        class="w-full border border-gray-500 rounded px-3 py-2" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['tip_gaza'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->tip_gaza == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="tip_ustroystva" class="block text-gray-700 mb-2">Тип устройства</label>
                    <select name="tip_ustroystva" id="tip_ustroystva"
                        class="w-full border border-gray-500 rounded px-3 py-2" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['tip_ustroystva'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->tip_ustroystva == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Вентиляция --}}
                @if(request()->category === 'Вентиляция')

                <div>
                    <label for="tip_tovara" class="block text-gray-700 mb-2">Тип товара</label>
                    <select name="tip_tovara" id="tip_tovara"
                        class="w-full border border-gray-500 rounded px-3 py-2" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['tip_tovara'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->tip_tovara == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Каминокомплекты --}}
                @if(request()->category === 'Каминокомплекты')

                <!-- Размеры -->
                <div class="space-y-2">
                    <label class="block text-gray-700 font-medium">Высота (мм)</label>
                    <div class="flex gap-2">
                        <input type="number" name="vysota_min" placeholder="От" class="w-1/2 border border-gray-500 rounded px-3 py-2 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" value="{{ request()->vysota_min }}">
                        <input type="number" name="vysota_max" placeholder="До" class="w-1/2 border border-gray-500 rounded px-3 py-2 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" value="{{ request()->vysota_max }}">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-gray-700 font-medium">Ширина (мм)</label>
                    <div class="flex gap-2">
                        <input type="number" name="shirina_min" placeholder="От" class="w-1/2 border border-gray-500 rounded px-3 py-2 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" value="{{ request()->shirina_min }}">
                        <input type="number" name="shirina_max" placeholder="До" class="w-1/2 border border-gray-500 rounded px-3 py-2 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" value="{{ request()->shirina_max }}">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-gray-700 font-medium">Глубина (мм)</label>
                    <div class="flex gap-2">
                        <input type="number" name="glubina_min" placeholder="От" class="w-1/2 border border-gray-500 rounded px-3 py-2 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" value="{{ request()->glubina_min }}">
                        <input type="number" name="glubina_max" placeholder="До" class="w-1/2 border border-gray-500 rounded px-3 py-2 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" value="{{ request()->glubina_max }}">
                    </div>
                </div>

                @endif

                {{-- Электроочаги --}}
                @if(request()->category === 'Электроочаги')

                <div>
                    <label for="tip_ochaga" class="block text-gray-700 mb-2">Тип очага</label>
                    <select name="tip_ochaga" id="tip_ochaga"
                        class="w-full border border-gray-500 rounded px-3 py-2" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['tip_ochaga'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->tip_ochaga == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="pult_du" class="block text-gray-700 mb-2">Пульт ду</label>
                    <select name="pult_du" id="pult_du"
                        class="w-full border border-gray-500 rounded px-3 py-2" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['pult_du'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->pult_du == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Печи, камины, каминокомплекты --}}
                @if(request()->category === 'Печи, камины, каминокомплекты')

                <div>
                    <label for="diametr_dymokhoda" class="block text-gray-700 mb-2">Диаметр дымохода</label>
                    <select name="diametr_dymokhoda" id="diametr_dymokhoda"
                        class="w-full border border-gray-500 rounded px-3 py-2" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['diametr_dymokhoda'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->diametr_dymokhoda == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="prisoyedinenie_dymokhoda" class="block text-gray-700 mb-2">Присоединение дымохода</label>
                    <select name="prisoyedinenie_dymokhoda" id="prisoyedinenie_dymokhoda"
                        class="w-full border border-gray-500 rounded px-3 py-2" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['prisoyedinenie_dymokhoda'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->prisoyedinenie_dymokhoda == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="forma_stekla_i_dverey" class="block text-gray-700 mb-2">Форма дверки/стекла</label>
                    <select name="forma_stekla_i_dverey" id="forma_stekla_i_dverey"
                        class="w-full border border-gray-500 rounded px-3 py-2" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['forma_stekla_i_dverey'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->forma_stekla_i_dverey == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-gray-700 font-medium">Мощность</label>
                    <div class="flex gap-2">
                        <input type="number" name="moshchnost_min" placeholder="От" class="w-1/2 border border-gray-500 rounded px-3 py-2 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" value="{{ request()->moshchnost_min }}">
                        <input type="number" name="moshchnost_max" placeholder="До" class="w-1/2 border border-gray-500 rounded px-3 py-2 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" value="{{ request()->moshchnost_max }}">
                    </div>
                </div>

                <div>
                    <label for="sposob_otkrytiya_dvertsy" class="block text-gray-700 mb-2">Способ открывания</label>
                    <select name="sposob_otkrytiya_dvertsy" id="sposob_otkrytiya_dvertsy"
                        class="w-full border border-gray-500 rounded px-3 py-2" @change="$el.form.submit()">
                        <option value="">Все</option>
                        @foreach($opts['sposob_otkrytiya_dvertsy'] ?? [] as $v)
                        <option value="{{ $v }}" @selected(request()->sposob_otkrytiya_dvertsy == $v)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Остальные категории (Каминокомплекты, Электроочаги и т.п.) --}}
                @if(in_array(request()->category, [
                'Каминокомплекты','Электроочаги','Порталы','Топки',
                'Вентиляция','Печи, камины, каминокомплекты','Дымоходы'
                ]))
                {{-- Для всех этих достаточно общих price+proizvoditel --}}
                {{-- (Мы уже показали их выше) --}}
                @endif

                {{-- Кнопка Применить --}}
                <button type="submit"
                    class="w-full bg-orange text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Применить
                </button>
            </div>
        </form>
    </div>
</div>
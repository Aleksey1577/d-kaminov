@extends('layouts.app')

@section('title', 'Сравнение')

@section('content')
@php
    $fmtNum   = fn($n) => number_format((float)$n, 0, '', ' ');
    $fmtMoney = fn($n) => $n === null ? null : ($fmtNum($n) . ' ₽');

    $hasProducts = $products->count() > 0;

    $allFields = [
        'tip_tovara',
        'sku','proizvoditel','strana','garantiya','vysota','shirina','glubina','ves','tsvet',
        'material','tolshchina_materiala','gabarity','toplivo','kolichestvo_konteynerov','obshchaya_liniya_ognya','vysota_vstraivayemaya',
        'shirina_vstraivayemaya','glubina_vstraivayemaya','maksimalnoe_vremya_goreniya','seriya_kaminov','raskhod_biotopliva',
        'material_tb','tip_biokamina','tolshchina_ochaga','tolshchina_kryshki_toplivnogo_bloka','seriya_toplivnogo_bloka',
        'obshchiy_razmer_tb','obem_toplivnogo_bloka','obem_zalivaemogo_topliva','klass_rashoda_biotopliva','vid_ognya','tolshchina_stekla',
        'material_ochaga','v_nalichii_na_sklade','kolichestvo_rezhimov_ognya','tip_upravleniya','sposob_upravleniya',
        'maksimalnaya_potrebljayemaya_moshchnost','tip_gaza','nominalnoe_vkhodnoe_davlenie','minimalnoe_vkhodnoe_davlenie',
        'maksimalnoe_vkhodnoe_davlenie','avtomaticheskoe_upravlenie','tip_ustroystva','shirina_dveri','vysota_dveri','diametr_dymokhoda',
        'prisoyedinenie_dymokhoda','forma_stekla_i_dverey','moshchnost','maksimalnaya_dlinna_drov','kpd','sposob_otkrytiya_dvertsy',
        'nalichie_dymosbornika','futerovka','nalichie_zolnika','teplooobmenik','moshchnost_teplooobmenika','tip_dverki',
        'podacha_vozdushka_izvne','raspolozhenie_v_pomeshchenii','dizayn_i_ispolnenie','material_oblitcovki','tip_nagrevanija',
        'optsii_kaminov_i_pechey','vysota_kaminokomplekta','nalichie_balki','three_d','vtorichnyy_dozhig','sistema_chistogo_stekla',
        'klass_energoemkosti','rezerv3','tekhnologiya_plameni','tip_ochaga','obem','moshchnost_obogreva','pult_du','otlichitelnye_osobennosti',
        'optsii','tsvet_ochaga','tsvet_dereva','tsvet_kamnya','material_korpusa','rezerv','diametr_dymokhoda_dymokhody','rezerv1',
        'image_url','image_url_1','image_url_2','image_url_3','image_url_4','image_url_5','image_url_6','image_url_7','image_url_8','image_url_9',
        'image_url_10','image_url_11','image_url_12','image_url_13','image_url_14','image_url_15','image_url_16','image_url_17','image_url_18','image_url_19','image_url_20',
        'created_at','updated_at',
    ];

    $skip = [
        'image_url','image_url_1','image_url_2','image_url_3','image_url_4','image_url_5','image_url_6','image_url_7','image_url_8','image_url_9',
        'image_url_10','image_url_11','image_url_12','image_url_13','image_url_14','image_url_15','image_url_16','image_url_17','image_url_18','image_url_19','image_url_20',
        'created_at','updated_at',
        'naimenovanie','price',
    ];

    $labels = [
        'kategoriya' => 'Категория (строка)',
        'tip_tovara' => 'Тип товара',
        'sku' => 'SKU',
        'postavshik' => 'Поставщик',
        'proizvoditel' => 'Производитель',
        'strana' => 'Страна',
        'garantiya' => 'Гарантия',
        'vysota' => 'Высота',
        'shirina' => 'Ширина',
        'glubina' => 'Глубина',
        'ves' => 'Вес',
        'tsvet' => 'Цвет',
        'material' => 'Материал',
        'tolshchina_materiala' => 'Толщина материала',
        'gabarity' => 'Габариты',
        'toplivo' => 'Топливо',
        'kolichestvo_konteynerov' => 'Кол-во контейнеров',
        'obshchaya_liniya_ognya' => 'Общая линия огня',
        'vysota_vstraivayemaya' => 'Высота встраиваемая',
        'shirina_vstraivayemaya' => 'Ширина встраиваемая',
        'glubina_vstraivayemaya' => 'Глубина встраиваемая',
        'maksimalnoe_vremya_goreniya' => 'Макс. время горения',
        'seriya_kaminov' => 'Серия каминов',
        'raskhod_biotopliva' => 'Расход биотоплива',
        'material_tb' => 'Материал ТБ',
        'tip_biokamina' => 'Тип биокамина',
        'tolshchina_ochaga' => 'Толщина очага',
        'tolshchina_kryshki_toplivnogo_bloka' => 'Толщина крышки ТБ',
        'seriya_toplivnogo_bloka' => 'Серия ТБ',
        'obshchiy_razmer_tb' => 'Общий размер ТБ',
        'obem_toplivnogo_bloka' => 'Объем ТБ',
        'obem_zalivaemogo_topliva' => 'Объем заливаемого топлива',
        'klass_rashoda_biotopliva' => 'Класс расхода биотоплива',
        'vid_ognya' => 'Вид огня',
        'tolshchina_stekla' => 'Толщина стекла',
        'material_ochaga' => 'Материал очага',
        'v_nalichii_na_sklade' => 'Наличие',
        'kolichestvo_rezhimov_ognya' => 'Кол-во режимов огня',
        'tip_upravleniya' => 'Тип управления',
        'sposob_upravleniya' => 'Способ управления',
        'maksimalnaya_potrebljayemaya_moshchnost' => 'Макс. потребляемая мощность',
        'tip_gaza' => 'Тип газа',
        'nominalnoe_vkhodnoe_davlenie' => 'Номинальное входное давление',
        'minimalnoe_vkhodnoe_davlenie' => 'Мин. входное давление',
        'maksimalnoe_vkhodnoe_davlenie' => 'Макс. входное давление',
        'avtomaticheskoe_upravlenie' => 'Автоматическое управление',
        'tip_ustroystva' => 'Тип устройства',
        'shirina_dveri' => 'Ширина двери',
        'vysota_dveri' => 'Высота двери',
        'diametr_dymokhoda' => 'Диаметр дымохода',
        'prisoyedinenie_dymokhoda' => 'Присоединение дымохода',
        'forma_stekla_i_dverey' => 'Форма стекла и дверей',
        'moshchnost' => 'Мощность',
        'maksimalnaya_dlinna_drov' => 'Макс. длина дров',
        'kpd' => 'КПД',
        'sposob_otkrytiya_dvertsy' => 'Способ открытия дверцы',
        'nalichie_dymosbornika' => 'Наличие дымосборника',
        'futerovka' => 'Футеровка',
        'nalichie_zolnika' => 'Наличие зольника',
        'teplooobmenik' => 'Теплообменник',
        'moshchnost_teplooobmenika' => 'Мощн. теплообменника',
        'tip_dverki' => 'Тип дверки',
        'podacha_vozdushka_izvne' => 'Подача воздуха извне',
        'raspolozhenie_v_pomeshchenii' => 'Расположение',
        'dizayn_i_ispolnenie' => 'Дизайн и исполнение',
        'material_oblitcovki' => 'Материал облицовки',
        'tip_nagrevanija' => 'Тип нагревания',
        'optsii_kaminov_i_pechey' => 'Опции каминов/печей',
        'vysota_kaminokomplekta' => 'Высота каминокомплекта',
        'nalichie_balki' => 'Наличие балки',
        'three_d' => '3D',
        'vtorichnyy_dozhig' => 'Вторичный дожиг',
        'sistema_chistogo_stekla' => 'Система чистого стекла',
        'klass_energoemkosti' => 'Класс энергоёмкости',
        'tekhnologiya_plameni' => 'Технология пламени',
        'tip_ochaga' => 'Тип очага',
        'обem' => 'Объём',
        'moshchnost_obogreva' => 'Мощность обогрева',
        'pult_du' => 'Пульт ДУ',
        'otlichitelnye_osobennosti' => 'Отличительные особенности',
        'optsii' => 'Опции',
        'tsvet_ochaga' => 'Цвет очага',
        'tsvet_dereva' => 'Цвет дерева',
        'tsvet_kamnya' => 'Цвет камня',
        'material_korpusa' => 'Материал корпуса',
        'diametr_dymokhoda_dymokhody' => 'Диаметр дымохода (Дымоходы)',
    ];

    $fieldsToShow = [];
    foreach ($allFields as $key) {
        if (in_array($key, $skip, true)) continue;
        $hasAny = false;
        foreach ($products as $p) {
            $val = data_get($p, $key);
            if ($key === 'price') { $val = $p->display_price ?? $val; }
            if ($val !== null && !(is_string($val) && trim($val) === '')) { $hasAny = true; break; }
        }
        if ($hasAny) $fieldsToShow[] = $key;
    }

    $render = function($p, $key) use ($fmtNum, $fmtMoney) {
        $val = data_get($p, $key);

        if ($key === 'price') {
            $price = $p->display_price ?? $val;
            return $fmtMoney($price);
        }
        if ($key === 'v_nalichii_na_sklade') {
            if ($p->v_nalichii_na_sklade === 'Да') return 'В наличии';
            if ($p->v_nalichii_na_sklade === 'Нет') return 'Под заказ';
        }
        if (in_array($key, ['kpd'])) {
            return is_null($val) ? null : (rtrim(rtrim((string)$val, '0'), '.') . ' %');
        }
        $numericKeys = [
            'vysota','shirina','glubina','ves','tolshchina_materiala','obshchaya_liniya_ognya',
            'vysota_vstraivayemaya','shirina_vstraivayemaya','glubina_vstraivayemaya',
            'maksimalnoe_vremya_goreniya','raskhod_biotopliva','tolshchina_ochaga',
            'tolshchina_kryshki_toplivnogo_bloka','obshchiy_razmer_tb','obem_toplivnogo_bloka',
            'obem_zalivaemogo_topliva','klass_rashoda_biotopliva','tolshchina_stekla',
            'kolichestvo_rezhimov_ognya','maksimalnaya_potrebljayemaya_moshchnost',
            'nominalnoe_vkhodnoe_davlenie','minimalnoe_vkhodnoe_davlenie','maksimalnoe_vkhodnoe_davlenie',
            'shirina_dveri','vysota_dveri','diametr_dymokhoda','maksimalnaya_dlinna_drov',
            'moshchnost','moshchnost_teplooobmenika','obem','moshchnost_obogreva','diametr_dymokhoda_dymokhody',
        ];
        if (in_array($key, $numericKeys, true) && is_numeric($val)) {
            return $fmtNum($val);
        }

        $boollike = [
            'avtomaticheskoe_upravlenie','nalichie_dymosbornika','nalichie_zolnika',
            'three_d','vtorichnyy_dozhig','sistema_chistogo_stekla','podacha_vozdushka_izvne','pult_du','nalichie_balki'
        ];
        if (in_array($key, $boollike, true)) {
            if ($val === null || $val === '') return null;
            $v = mb_strtolower(trim((string)$val));
            if (in_array($v, ['1','да','yes','true','есть'])) return 'Да';
            if (in_array($v, ['0','нет','no','false','нету','отсутствует'])) return 'Нет';
            return (string)$val;
        }

        return ($val === null || (is_string($val) && trim($val) === '')) ? null : (string)$val;
    };

    $label = fn($k) => $labels[$k] ?? ucfirst(str_replace('_', ' ', $k));
@endphp

<h1 class="text-2xl font-semibold mb-4">Сравнение товаров</h1>

@if($hasProducts)
    <div class="flex items-center justify-between mb-3">
        <div></div>
        <label class="inline-flex items-center gap-2 text-sm text-gray-700 select-none">
            <input id="diffOnlyToggle" type="checkbox" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
            Показывать только различия
        </label>
    </div>

    <style>
        /* Липкий первый столбец "Характеристика" */
        .cmp-sticky-first thead th:first-child,
        .cmp-sticky-first tbody td:first-child {
            position: sticky;
            left: 0;
            z-index: 1;
            background: #fff; /* совпадает с bg-white таблицы */
        }
        /* чтобы заголовочная ячейка была поверх содержимого */
        .cmp-sticky-first thead th:first-child { z-index: 2; }
    </style>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full border-collapse min-w-[1000px] cmp-sticky-first" id="compareTable">
            <thead>
                <tr class="bg-gray-50 text-left text-sm text-gray-600">
                    <th class="border-b p-4 font-semibold w-64">Характеристика</th>
                    @foreach($products as $product)
                        <th class="border-b p-4 align-bottom">
                            <div class="flex items-start gap-3">
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-900">
                                        {{ $product->naimenovanie }}
                                    </div>
                                    @php $priceCell = $product->display_price ?? $product->price ?? null; @endphp
                                    @if(!is_null($priceCell))
                                        <div class="mt-1 text-gray-500 text-sm">
                                            {{ $fmtMoney($priceCell) }}
                                        </div>
                                    @endif
                                    @if(!empty($product->v_nalichii_na_sklade))
                                        <div class="mt-1 text-xs">
                                            @if($product->v_nalichii_na_sklade === 'Да')
                                                <span class="inline-block px-2 py-0.5 rounded bg-green-50 text-green-700 border border-green-200">В наличии</span>
                                            @elseif($product->v_nalichii_na_sklade === 'Нет')
                                                <span class="inline-block px-2 py-0.5 rounded bg-amber-50 text-amber-700 border border-amber-200">Под заказ</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <form action="{{ route('compare.remove', $product->product_id) }}" method="POST"
                                      onsubmit="sessionStorage.setItem('scrollPos', window.pageYOffset); return true;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 text-sm">Убрать</button>
                                </form>
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody class="text-sm">
                @php $hasAnyImage = $products->contains(fn($p) => !empty($p->image_url)); @endphp
                @if($hasAnyImage)
                    <tr class="cmp-row" data-same="0"><!-- изображения всегда показываем -->
                        <td class="border-b p-4 font-medium text-gray-700">Изображение</td>
                        @foreach($products as $product)
                            <td class="border-b p-4">
                                @if(!empty($product->image_url))
                                    <img src="{{ $product->image_url }}" alt="{{ $product->naimenovanie }}"
                                         class="w-24 h-24 object-contain rounded border bg-white">
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endif

                @foreach($fieldsToShow as $field)
                    @php
                        // Проверим, есть ли отображаемые значения
                        $rowHasValue = false;
                        $vals = [];
                        foreach ($products as $p) {
                            $v = $render($p, $field);
                            $vals[] = is_null($v) ? '' : trim((string)$v);
                            if ($v !== null && $v !== '') $rowHasValue = true;
                        }
                        // различия: более одного уникального непустого значения
                        $unique = collect($vals)->filter(fn($v) => $v !== '')->unique()->values();
                        $isSame = $rowHasValue ? ($unique->count() <= 1) : true;
                    @endphp

                    @if($rowHasValue)
                        <tr class="cmp-row" data-same="{{ $isSame ? '1' : '0' }}">
                            <td class="border-b p-4 font-medium text-gray-700">{{ $label($field) }}</td>
                            @foreach($products as $product)
                                @php $val = $render($product, $field); @endphp
                                <td class="border-b p-4 align-top">
                                    @if(!is_null($val) && $val !== '')
                                        {{ $val }}
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="bg-white p-6 rounded shadow text-center">
        <p class="text-gray-600 mb-4">В сравнении пока нет товаров.</p>
        <a href="{{ route('catalog') }}"
           class="inline-block px-6 py-3 bg-orange text-white rounded hover:bg-orange-white transition">
            Перейти в каталог
        </a>
    </div>
@endif

<script>
    // восстановление позиции скролла после удаления
    document.addEventListener('DOMContentLoaded', () => {
        const pos = sessionStorage.getItem('scrollPos');
        if (pos) { window.scrollTo({ top: +pos, behavior: 'instant' }); sessionStorage.removeItem('scrollPos'); }

        // логика "Показывать только различия"
        const toggle = document.getElementById('diffOnlyToggle');
        const rows = document.querySelectorAll('#compareTable .cmp-row');

        function applyFilter() {
            const onlyDiff = toggle && toggle.checked;
            rows.forEach(tr => {
                const same = tr.getAttribute('data-same') === '1';
                // строки с data-same="1" скрываем при активном фильтре
                tr.style.display = (onlyDiff && same) ? 'none' : '';
            });
        }

        if (toggle) {
            toggle.addEventListener('change', applyFilter);
            applyFilter();
        }
    });
</script>
@endsection

@extends('layouts.admin')

@section('title', 'Редактировать товар')

@section('content')
<div class="bg-white shadow-md rounded-lg p-4 md:p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold mb-1">Редактировать товар</h1>
            <p class="text-sm text-gray-500">
                <span class="font-semibold">product_id:</span> {{ $product->product_id }}
                @if($product->sku)
                    • <span class="font-semibold">SKU:</span> {{ $product->sku }}
                @endif
            </p>
        </div>

        <a href="{{ url()->previous() }}"
           class="inline-flex items-center gap-2 text-sm px-3 py-2 rounded-md border border-gray-200 hover:bg-gray-50">
            <span class="text-lg">←</span>
            <span>Назад</span>
        </a>
    </div>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <section class="border border-gray-100 rounded-lg p-4">
                <h2 class="text-base font-semibold mb-3">Основная информация</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @php
                        $tip = old('tip_stroki', $product->tip_stroki);
                        $simpleFields = [
                            'naimenovanie_artikula' => 'Название артикула (варианта)',
                            'naimenovanie'          => 'Название товара*',
                        ];
                        $dropdowns = [
                            'postavshik'   => ['label' => 'Поставщик',     'list' => $suppliers ?? []],
                            'proizvoditel' => ['label' => 'Производитель', 'list' => $manufacturers ?? []],
                            'strana'       => ['label' => 'Страна',        'list' => $countries ?? []],
                        ];
                        $currentCurrency  = old('valyuta',   $product->valyuta);
                        $currentCategory  = old('kategoriya',$product->kategoriya);
                        $currentType      = old('tip_tovara',$product->tip_tovara);
                        $stock            = old('v_nalichii_na_sklade',$product->v_nalichii_na_sklade ?? 'Да');
                    @endphp

                    <div class="md:col-span-2">
                        <label class="block text-gray-700 mb-1 text-sm font-medium" for="tip_stroki">
                            Тип строки (tip_stroki)
                        </label>
                        <select name="tip_stroki" id="tip_stroki" class="w-full border rounded-md p-2.5 text-sm">
                            <option value="product"         {{ $tip === 'product' ? 'selected' : '' }}>Основной товар (product)</option>
                            <option value="product_variant" {{ $tip === 'product_variant' ? 'selected' : '' }}>Товар варианта (product_variant)</option>
                            <option value="variant"         {{ $tip === 'variant' ? 'selected' : '' }}>Вариант (variant)</option>
                        </select>
                    </div>

                    @foreach($simpleFields as $name => $label)
                        <div>
                            <label class="block text-gray-700 mb-1 text-sm font-medium" for="{{ $name }}">{{ $label }}</label>
                            <input  type="text"
                                    name="{{ $name }}"
                                    id="{{ $name }}"
                                    value="{{ old($name, $product->$name) }}"
                                    @if($name === 'naimenovanie') required @endif
                                    class="w-full border rounded-md p-2.5 text-sm">
                        </div>
                    @endforeach

                    <div>
                        <label class="block text-gray-700 mb-1 text-sm font-medium" for="price">Цена*</label>
                        <input  type="number"
                                name="price"
                                id="price"
                                value="{{ old('price', $product->price) }}"
                                min="0"
                                step="0.01"
                                required
                                class="w-full border rounded-md p-2.5 text-sm">
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-1 text-sm font-medium" for="valyuta">Валюта</label>
                        @if(!empty($currencies ?? []))
                            <select name="valyuta" id="valyuta" class="w-full border rounded-md p-2.5 text-sm">
                                <option value="">— Не выбрано —</option>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency }}" {{ $currentCurrency === $currency ? 'selected' : '' }}>
                                        {{ $currency }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <input  type="text"
                                    name="valyuta"
                                    id="valyuta"
                                    value="{{ $currentCurrency }}"
                                    class="w-full border rounded-md p-2.5 text-sm">
                        @endif
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-1 text-sm font-medium" for="kategoriya">Категория*</label>
                        @if(!empty($categories ?? []))
                            <select name="kategoriya" id="kategoriya" class="w-full border rounded-md p-2.5 text-sm">
                                <option value="">— Не выбрано —</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ $currentCategory === $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <input  type="text"
                                    name="kategoriya"
                                    id="kategoriya"
                                    value="{{ $currentCategory }}"
                                    required
                                    class="w-full border rounded-md p-2.5 text-sm">
                        @endif
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-1 text-sm font-medium" for="tip_tovara">Тип товара</label>
                        <select name="tip_tovara" id="tip_tovara" class="w-full border rounded-md p-2.5 text-sm">
                            <option value="">— Не выбрано —</option>
                            @foreach($productTypes ?? [] as $type)
                                <option value="{{ $type }}" {{ $currentType === $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-1 text-sm font-medium" for="v_nalichii_na_sklade">В наличии на складе</label>
                        <select name="v_nalichii_na_sklade" id="v_nalichii_na_sklade" class="w-full border rounded-md p-2.5 text-sm">
                            <option value="Да"  {{ $stock === 'Да'  ? 'selected' : '' }}>Да</option>
                            <option value="Нет" {{ $stock === 'Нет' ? 'selected' : '' }}>Нет</option>
                        </select>
                    </div>

                    @foreach($dropdowns as $name => $cfg)
                        @php $current = old($name, $product->$name); @endphp
                        <div>
                            <label class="block text-gray-700 mb-1 text-sm font-medium" for="{{ $name }}">
                                {{ $cfg['label'] }}
                            </label>
                            @if(!empty($cfg['list']))
                                <select name="{{ $name }}" id="{{ $name }}" class="w-full border rounded-md p-2.5 text-sm">
                                    <option value="">— Не выбрано —</option>
                                    @foreach($cfg['list'] as $val)
                                        <option value="{{ $val }}" {{ $current === $val ? 'selected' : '' }}>
                                            {{ $val }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input  type="text"
                                        name="{{ $name }}"
                                        id="{{ $name }}"
                                        value="{{ $current }}"
                                        class="w-full border rounded-md p-2.5 text-sm">
                            @endif
                        </div>
                    @endforeach

                    <div class="md:col-span-2">
                        <label class="block text-gray-700 mb-1 text-sm font-medium" for="opisanije">Описание</label>
                        <textarea name="opisanije" id="opisanije" rows="3"
                                  class="w-full border rounded-md p-2.5 text-sm">{{ old('opisanije', $product->opisanije) }}</textarea>
                    </div>
                </div>
            </section>

            <section class="border border-gray-100 rounded-lg p-4">
                <h2 class="text-base font-semibold mb-3">Основные характеристики</h2>

                @php
                    $mainFields = [
                        'sku'          => 'Артикул (SKU)',
                        'supplier_sku' => 'Артикул поставщика',
                        'price2'       => 'Цена 2',
                        'material'     => 'Материал',
                        'tsvet'        => 'Цвет',
                        'vysota'       => 'Высота',
                        'shirina'      => 'Ширина',
                        'glubina'      => 'Глубина',
                        'ves'          => 'Вес',
                    ];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($mainFields as $name => $label)
                        <div>
                            <label class="block text-gray-700 mb-1 text-sm font-medium" for="{{ $name }}">{{ $label }}</label>
                            <input
                                @if($name === 'price2') type="number" min="0" step="0.01" @else type="text" @endif
                                name="{{ $name }}"
                                id="{{ $name }}"
                                value="{{ old($name, $product->$name) }}"
                                class="w-full border rounded-md p-2.5 text-sm">
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

        @php
            $extraFields = [
                'tolshchina_materiala','gabarity','toplivo','kolichestvo_konteynerov','obshchaya_liniya_ognya',
                'vysota_vstraivayemaya','shirina_vstraivayemaya','glubina_vstraivayemaya','maksimalnoe_vremya_goreniya',
                'seriya_kaminov','raskhod_biotopliva','material_tb','tip_biokamina','tolshchina_ochaga',
                'tolshchina_kryshki_toplivnogo_bloka','seriya_toplivnogo_bloka','obshchiy_razmer_tb','obem_toplivnogo_bloka',
                'obem_zalivaemogo_topliva','klass_rashoda_biotopliva','vid_ognya','tolshchina_stekla','material_ochaga',
                'kolichestvo_rezhimov_ognya','tip_upravleniya','sposob_upravleniya',
                'maksimalnaya_potrebljayemaya_moshchnost','tip_gaza','nominalnoe_vkhodnoe_davlenie',
                'minimalnoe_vkhodnoe_davlenie','maksimalnoe_vkhodnoe_davlenie','avtomaticheskoe_upravlenie',
                'tip_ustroystva','shirina_dveri','vysota_dveri','diametr_dymokhoda','prisoyedinenie_dymokhoda',
                'forma_stekla_i_dverey','moshchnost','maksimalnaya_dlinna_drov','kpd','sposob_otkrytiya_dvertsy',
                'nalichie_dymosbornika','futerovka','nalichie_zolnika','teplooobmenik','moshchnost_teplooobmenika',
                'tip_dverki','podacha_vozdushka_izvne','raspolozhenie_v_pomeshchenii','dizayn_i_ispolnenie',
                'material_oblitcovki','tip_nagrevanija','optsii_kaminov_i_pechey','vysota_kaminokomplekta',
                'nalichie_balki','three_d','vtorichnyy_dozhig','sistema_chistogo_stекла','klass_energoemkosti',
                'rezerv3','tekhnologiya_plameni','tip_ochaga','obem','moshchnost_obogreva','pult_du',
                'otlichitelnye_osobennosti','optsii','tsvet_ochaga','tsvet_dereva','tsvet_kamnya',
                'material_korpusa','rezerv','diametr_dymokhoda_dymokhody','rezerv1',
            ];
        @endphp

        <section class="border border-gray-100 rounded-lg p-4">
            <h2 class="text-base font-semibold mb-3">Дополнительные характеристики</h2>

            <div class="border rounded-md max-h-80 overflow-y-auto p-3 bg-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($extraFields as $field)
                        @php
                            $value = old($field, $product->$field);
                            $label = \App\Models\Product::labelFor($field);
                        @endphp
                        <div>
                            <label class="block text-gray-700 mb-1 text-sm font-medium" for="{{ $field }}">{{ $label }}</label>
                            <input  type="text"
                                    name="{{ $field }}"
                                    id="{{ $field }}"
                                    value="{{ $value }}"
                                    class="w-full border rounded-md p-2.5 text-sm">
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        @php
            $imageConfigs   = [['urlField' => 'image_url', 'fileField' => 'image_file', 'label' => 'Основное фото']];
            for ($i = 1; $i <= 20; $i++) {
                $imageConfigs[] = [
                    'urlField'  => "image_url_{$i}",
                    'fileField' => "image_file_{$i}",
                    'label'     => "Фото {$i}",
                ];
            }
        @endphp

        <section class="border border-gray-100 rounded-lg p-4 space-y-3">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-base font-semibold">Картинки товара</h2>
                    <p class="text-xs text-gray-500 mt-1">
                        Короткий превью-ряд снизу, детали — по клику.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button"
                            data-add-image
                            class="text-sm inline-flex items-center gap-2 px-3 py-1.5 rounded-md border border-amber-200 bg-amber-50 hover:bg-amber-100 transition">
                        Добавить фото
                    </button>
                    <button type="button"
                            data-gallery-toggle
                            class="text-sm inline-flex items-center gap-2 px-3 py-1.5 rounded-md border border-gray-200 bg-gray-50 hover:bg-gray-100 transition">
                        <span class="toggle-text">Показать блок</span>
                        <span aria-hidden="true">▼</span>
                    </button>
                </div>
            </div>

            <div id="gallery-summary" class="flex items-center gap-2 overflow-x-auto py-1"></div>

            <div class="space-y-4 hidden" data-gallery-body>
                <div class="border border-dashed border-gray-300 rounded-md p-3 bg-gray-50">
                    <label class="block text-gray-700 mb-1 text-sm font-medium">Массовая загрузка изображений</label>
                    <input  type="file"
                            name="images_multi[]"
                            multiple
                            accept="image/*"
                            class="w-full border rounded-md p-2.5 text-sm bg-white">
                    <p class="text-xs text-gray-500 mt-1">
                        Можно выбрать несколько файлов — они заполнят свободные слоты (основное фото, Фото 1…20).
                    </p>
                </div>

                <div id="images-sortable" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($imageConfigs as $cfg)
                        @php
                            $urlField  = $cfg['urlField'];
                            $fileField = $cfg['fileField'];
                            $label     = $cfg['label'];
                            $urlVal    = old($urlField, $product->$urlField);
                        @endphp

                        @php
                            $previewSrc = null;
                            if ($urlVal) {
                                $previewSrc = \Illuminate\Support\Str::startsWith($urlVal, ['http://', 'https://', '//', 'data:'])
                                    ? $urlVal
                                    : asset(ltrim($urlVal, '/'));
                            }
                        @endphp

                        <div class="draggable-row flex gap-3 bg-gray-50 rounded-md p-3 items-stretch {{ $urlVal ? '' : 'hidden' }}"
                             draggable="true"
                             data-image-card="{{ $urlField }}">
                            <div class="w-28 flex flex-col items-center">
                                <button type="button"
                                        class="text-gray-400 text-lg cursor-move"
                                        title="Перетащите, чтобы изменить порядок">☰</button>

                                <div class="mt-2 w-24 h-24 border rounded bg-white flex items-center justify-center overflow-hidden">
                                    @if($urlVal)
                                        <img src="{{ $previewSrc }}"
                                             alt="{{ $label }}"
                                             class="w-full h-full object-cover"
                                             data-preview="{{ $urlField }}">
                                    @else
                                        <img src=""
                                             alt=""
                                             class="w-full h-full object-cover hidden"
                                             data-preview="{{ $urlField }}">
                                        <span class="text-[10px] text-gray-400 text-center px-1">Нет изображения</span>
                                    @endif
                                </div>
                                <input type="hidden" name="image_positions[]" value="{{ $urlField }}">
                                <button type="button"
                                        class="mt-2 text-xs text-red-600 hover:text-red-700"
                                        data-remove-image="{{ $urlField }}">
                                    Удалить
                                </button>
                            </div>

                            <div class="flex-1 space-y-3">
                                <div>
                                    <label class="block text-gray-700 mb-1 text-sm font-medium">{{ $label }} (файл)</label>
                                    <input  type="file"
                                            name="{{ $fileField }}"
                                            accept="image/*"
                                            class="w-full border rounded-md p-2.5 text-sm bg-white"
                                            data-preview-target="{{ $urlField }}">
                                    <p class="text-[11px] text-gray-500 mt-1">
                                        При загрузке файла URL ниже будет перезаписан.
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-gray-700 mb-1 text-sm font-medium" for="{{ $urlField }}">
                                        {{ $label }} (URL)
                                    </label>
                                    <input  type="text"
                                            name="{{ $urlField }}"
                                            id="{{ $urlField }}"
                                            value="{{ $urlVal }}"
                                            class="w-full border rounded-md p-2.5 text-sm"
                                            placeholder="/assets/... или https://..."
                                            data-preview-target="{{ $urlField }}">
                                    @if($urlField === 'image_url')
                                        <p class="text-xs text-gray-500 mt-1">
                                            Можно указать URL или загрузить файл — если будет файл, он будет использован приоритетно.
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <div class="flex items-center justify-between pt-2">
            <a href="{{ url()->previous() }}"
               class="inline-flex items-center gap-2 text-sm px-4 py-2 rounded-md border border-gray-300 hover:bg-gray-50">
                <span class="text-lg">←</span>
                <span>Назад к списку</span>
            </a>

            <button type="submit"
                    class="inline-flex items-center gap-2 bg-orange text-white px-5 py-2.5 rounded-md text-sm font-semibold hover:bg-orange-white transition-colors">
                Обновить товар
            </button>
        </div>
    </form>
</div>

<div id="js-types-by-category"
    data-json='@json($typesByCategory ?? [])'
    class="hidden"></div>
<div id="js-current-type"
    data-value="{{ e(old('tip_tovara', $product->tip_tovara)) }}"
    class="hidden"></div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('images-sortable');
    if (container) {
        let dragEl = null;

        container.querySelectorAll('.draggable-row').forEach(row => {
            row.addEventListener('dragstart', e => {
                dragEl = row;
                row.classList.add('opacity-50');
                e.dataTransfer.effectAllowed = 'move';
            });

            row.addEventListener('dragend', () => {
                if (dragEl) dragEl.classList.remove('opacity-50');
                dragEl = null;
            });

            row.addEventListener('dragover', e => {
                e.preventDefault();
                if (!dragEl || dragEl === row) return;

                const rect = row.getBoundingClientRect();
                const offset = e.clientY - rect.top;
                if (offset > rect.height / 2) row.after(dragEl);
                else row.before(dragEl);
            });
        });
    }

    const previews = {};
    document.querySelectorAll('[data-preview]').forEach(img => {
        const key = img.dataset.preview;
        if (key) previews[key] = img;
    });

    const cardList = Array.from(document.querySelectorAll('[data-image-card]'));
    const cardByKey = {};
    cardList.forEach(card => {
        const key = card.dataset.imageCard;
        if (key) cardByKey[key] = card;
    });

    const fieldMap = {};
    document.querySelectorAll('[data-preview-target]').forEach(input => {
        const key = input.dataset.previewTarget;
        if (!key) return;
        if (!fieldMap[key]) fieldMap[key] = {};
        if (input.type === 'file') fieldMap[key].fileInput = input;
        else fieldMap[key].urlInput = input;
    });

    const galleryBody = document.querySelector('[data-gallery-body]');
    const galleryToggle = document.querySelector('[data-gallery-toggle]');
    const gallerySummary = document.getElementById('gallery-summary');
    const addImageButton = document.querySelector('[data-add-image]');
    let toggleText = null;
    let toggleArrow = null;

    function renderGallerySummary() {
        if (!gallerySummary) return;
        gallerySummary.innerHTML = '';

        const filled = Object.values(previews).filter(img => img && !img.classList.contains('hidden') && img.src);
        if (!filled.length) {
            const placeholder = document.createElement('p');
            placeholder.className = 'text-xs text-gray-500';
            placeholder.textContent = 'Пока без изображений — добавьте файл или URL.';
            gallerySummary.appendChild(placeholder);
            return;
        }

        filled.slice(0, 8).forEach(img => {
            const thumb = document.createElement('div');
            thumb.className = 'w-12 h-12 rounded border bg-white shadow-sm flex-shrink-0 overflow-hidden';
            thumb.style.backgroundImage = `url('${img.src}')`;
            thumb.style.backgroundSize = 'cover';
            thumb.style.backgroundPosition = 'center';
            gallerySummary.appendChild(thumb);
        });

        if (filled.length > 8) {
            const more = document.createElement('div');
            more.className = 'w-12 h-12 rounded border border-dashed flex-shrink-0 flex items-center justify-center text-xs text-gray-500 bg-white';
            more.textContent = `+${filled.length - 8}`;
            gallerySummary.appendChild(more);
        }
    }

    if (galleryToggle && galleryBody) {
        toggleText = galleryToggle.querySelector('.toggle-text');
        toggleArrow = galleryToggle.querySelector('[aria-hidden]');
        galleryToggle.addEventListener('click', () => {
            const hidden = galleryBody.classList.toggle('hidden');
            if (toggleText) toggleText.textContent = hidden ? 'Показать блок' : 'Скрыть блок';
            if (toggleArrow) toggleArrow.textContent = hidden ? '▼' : '▲';
        });
    }

    function setGalleryOpen(open) {
        if (!galleryBody) return;
        galleryBody.classList.toggle('hidden', !open);
        if (toggleText) toggleText.textContent = open ? 'Скрыть блок' : 'Показать блок';
        if (toggleArrow) toggleArrow.textContent = open ? '▲' : '▼';
    }

    function setCardVisible(key, visible) {
        const card = cardByKey[key];
        if (!card) return;
        if (visible) card.classList.remove('hidden');
        else card.classList.add('hidden');
    }

    function updateAddButtonState() {
        if (!addImageButton) return;
        const hasHidden = cardList.some(card => card.classList.contains('hidden'));
        addImageButton.disabled = !hasHidden;
        addImageButton.classList.toggle('opacity-50', !hasHidden);
        addImageButton.classList.toggle('cursor-not-allowed', !hasHidden);
    }

    if (addImageButton) {
        addImageButton.addEventListener('click', () => {
            const nextCard = cardList.find(card => card.classList.contains('hidden'));
            if (!nextCard) return;
            setGalleryOpen(true);
            nextCard.classList.remove('hidden');
            const key = nextCard.dataset.imageCard;
            const inputs = fieldMap[key] || {};
            if (inputs.urlInput) inputs.urlInput.focus();
            else if (inputs.fileInput) inputs.fileInput.focus();
            updateAddButtonState();
        });
    }

    function updatePreviewFromUrl(key, url) {
        const img = previews[key];
        if (!img) return;

        const wrapper = img.parentElement;
        const emptyText = wrapper ? wrapper.querySelector('span') : null;

        if (!url || !url.trim()) {
            img.src = '';
            img.classList.add('hidden');
            if (emptyText) emptyText.classList.remove('hidden');
            return;
        }

        let src = url.trim();
        if (!src.startsWith('http') && !src.startsWith('data:') && !src.startsWith('//')) {
            src = src.charAt(0) === '/' ? src : '/' + src;
        }

        img.src = src;
        img.classList.remove('hidden');
        if (emptyText) emptyText.classList.add('hidden');
        setCardVisible(key, true);
    }

    document.querySelectorAll('[data-preview-target]').forEach(input => {
        const key = input.dataset.previewTarget;
        if (!key) return;

        if (input.type === 'file') {
            input.addEventListener('change', () => {
                const img = previews[key];
                if (!img) return;
                const wrapper = img.parentElement;
                const emptyText = wrapper ? wrapper.querySelector('span') : null;

                const file = input.files && input.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = e => {
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    if (emptyText) emptyText.classList.add('hidden');
                    setCardVisible(key, true);
                    renderGallerySummary();
                    updateAddButtonState();
                };
                reader.readAsDataURL(file);
            });
        } else {
            const handler = (event) => {
                updatePreviewFromUrl(key, input.value);
                renderGallerySummary();
                const inputs = fieldMap[key] || {};
                const hasFile = inputs.fileInput && inputs.fileInput.files && inputs.fileInput.files.length;
                if (event.type === 'change' && !input.value.trim() && !hasFile) {
                    setCardVisible(key, false);
                }
                updateAddButtonState();
            };
            input.addEventListener('input', handler);
            input.addEventListener('change', handler);
        }
    });

    document.querySelectorAll('[data-remove-image]').forEach(button => {
        button.addEventListener('click', () => {
            const key = button.dataset.removeImage;
            const inputs = fieldMap[key] || {};
            if (inputs.urlInput) inputs.urlInput.value = '';
            if (inputs.fileInput) inputs.fileInput.value = '';
            updatePreviewFromUrl(key, '');
            setCardVisible(key, false);
            renderGallerySummary();
            updateAddButtonState();
        });
    });

    document.querySelectorAll('[data-preview-target]').forEach(input => {
        if (input.type === 'text' && input.value) {
            const key = input.dataset.previewTarget;
            if (key) updatePreviewFromUrl(key, input.value);
        }
    });

    renderGallerySummary();
    updateAddButtonState();

    /* ---------- Категория → Тип товара ---------- */
    const typesDiv = document.getElementById('js-types-by-category');
    const currentTypeDiv = document.getElementById('js-current-type');
    const selectCategory = document.getElementById('kategoriya');
    const selectType = document.getElementById('tip_tovara');

    if (typesDiv && currentTypeDiv && selectCategory && selectType) {
        let map = {};
        try {
            map = JSON.parse(typesDiv.dataset.json || '{}');
        } catch (e) {
            map = {};
        }

        let initialType = currentTypeDiv.dataset.value || '';

        function rebuildTypeOptions(resetSelected = false) {
            const cat = selectCategory.value || '';
            const list = Array.isArray(map[cat]) ? map[cat] : [];

            const prevValue = resetSelected ? '' : (selectType.value || initialType);

            selectType.innerHTML = '';
            const emptyOption = document.createElement('option');
            emptyOption.value = '';
            emptyOption.textContent = '— Не выбрано —';
            selectType.appendChild(emptyOption);

            list.forEach((value) => {
                const opt = document.createElement('option');
                opt.value = value;
                opt.textContent = value;
                if (value === prevValue) {
                    opt.selected = true;
                }
                selectType.appendChild(opt);
            });
        }

        rebuildTypeOptions();

        selectCategory.addEventListener('change', () => {
            initialType = '';
            rebuildTypeOptions(true);
        });
    }
});
</script>
@endsection

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /* ---------- CRUD ---------- */

    public function index(Request $request)
    {
        // валидируем входные фильтры (GET)
        $filters = $request->validate([
            'search'         => 'nullable|string|max:255',
            'kategoriya'     => 'nullable|string|max:255',
            'tip_tovara'     => 'nullable|string|max:255',
            'postavshik'     => 'nullable|string|max:255',
            'proizvoditel'   => 'nullable|string|max:255',
            'price_min'      => 'nullable|numeric|min:0',
            'price_max'      => 'nullable|numeric|min:0',
        ]);

        $query = Product::query();

        // Поиск (название/sku)
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('naimenovanie', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Фильтры по равенству
        if (!empty($filters['kategoriya'])) {
            $query->where('kategoriya', $filters['kategoriya']);
        }
        if (!empty($filters['tip_tovara'])) {
            $query->where('tip_tovara', $filters['tip_tovara']);
        }
        if (!empty($filters['postavshik'])) {
            $query->where('postavshik', $filters['postavshik']);
        }
        if (!empty($filters['proizvoditel'])) {
            $query->where('proizvoditel', $filters['proizvoditel']);
        }

        // Фильтр по цене (min/max, можно указывать один из них)
        $min = $filters['price_min'] ?? null;
        $max = $filters['price_max'] ?? null;

        if ($min !== null && $max !== null) {
            if ($min > $max) {
                [$min, $max] = [$max, $min]; // авто-перестановка, если перепутали
            }
            $query->whereBetween('price', [$min, $max]);
        } elseif ($min !== null) {
            $query->where('price', '>=', $min);
        } elseif ($max !== null) {
            $query->where('price', '<=', $max);
        }

        $products = $query->orderByDesc('product_id')
            ->paginate(50)
            ->withQueryString();

        // Справочники для селектов
        $categories    = $this->getDistinctValues('kategoriya');
        $productTypes = !empty($filters['kategoriya'])
            ? Product::query()
                ->where('kategoriya', $filters['kategoriya'])
                ->whereNotNull('tip_tovara')
                ->where('tip_tovara', '!=', '')
                ->distinct()
                ->orderBy('tip_tovara')
                ->pluck('tip_tovara')
                ->toArray()
            : $this->getDistinctValues('tip_tovara');
        $suppliers     = $this->getDistinctValues('postavshik');
        $manufacturers = $this->getDistinctValues('proizvoditel');

        return view('admin.products.index', compact('products', 'categories', 'productTypes', 'suppliers', 'manufacturers'));
    }

    public function create()
    {
        return view('admin.products.create', $this->getFormOptions());
    }

    public function store(Request $request)
    {
        // 1) валидируем текстовые поля (только то, что есть в БД)
        $data = $this->validateData($request);

        // 2) валидируем файлы (но НЕ кладём их в $data)
        $this->validateImages($request);

        // 3) обрабатываем загрузку файлов и выставляем image_url*
        $this->handleImages($request, $data);

        Product::create($data);

        return redirect()->route('admin.products')->with('success', 'Товар создан');
    }

    public function edit(Product $product)
    {
        $options = $this->getFormOptions($product);

        return view('admin.products.edit', array_merge(
            compact('product'),
            $options
        ));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateData($request);
        $this->validateImages($request);
        $this->handleImages($request, $data);

        $product->update($data);

        return redirect()->route('admin.products')->with('success', 'Товар обновлён');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Товар удалён');
    }

    /* ---------- Служебные методы для форм (списки значений) ---------- */

    protected function getFormOptions(?Product $product = null): array
    {
        $cols = [
            'categories'    => 'kategoriya',
            'suppliers'     => 'postavshik',
            'manufacturers' => 'proizvoditel',
            'countries'     => 'strana',
            'productTypes'  => 'tip_tovara',
            'currencies'    => 'valyuta',
        ];

        $options = [];
        foreach ($cols as $name => $column) {
            $options[$name] = $this->getDistinctValues($column);
        }

        // карта: категория -> [типы товара]
        $typesByCategory = Product::whereNotNull('kategoriya')
            ->where('kategoriya', '!=', '')
            ->whereNotNull('tip_tovara')
            ->where('tip_tovara', '!=', '')
            ->get(['kategoriya', 'tip_tovara'])
            ->groupBy('kategoriya')
            ->map(fn($group) => $group->pluck('tip_tovara')->unique()->values()->all())
            ->toArray();

        // при редактировании — гарантируем, что значения текущего товара есть в списках
        if ($product) {
            $this->prependIfMissing($options['categories'],    $product->kategoriya);
            $this->prependIfMissing($options['suppliers'],     $product->postavshik);
            $this->prependIfMissing($options['manufacturers'], $product->proizvoditel);
            $this->prependIfMissing($options['countries'],     $product->strana);
            $this->prependIfMissing($options['productTypes'],  $product->tip_tovara);
            $this->prependIfMissing($options['currencies'],    $product->valyuta);

            if ($product->kategoriya && $product->tip_tovara) {
                if (!isset($typesByCategory[$product->kategoriya])) {
                    $typesByCategory[$product->kategoriya] = [];
                }
                if (!in_array($product->tip_tovara, $typesByCategory[$product->kategoriya], true)) {
                    array_unshift($typesByCategory[$product->kategoriya], $product->tip_tovara);
                }
            }
        }

        $options['typesByCategory'] = $typesByCategory;

        return $options;
    }

    protected function getDistinctValues(string $column): array
    {
        return Product::whereNotNull($column)
            ->where($column, '!=', '')
            ->distinct()
            ->orderBy($column)
            ->pluck($column)
            ->toArray();
    }

    protected function prependIfMissing(array &$array, ?string $value): void
    {
        if ($value !== null && $value !== '' && !in_array($value, $array, true)) {
            array_unshift($array, $value);
        }
    }

    /* ---------- Валидация данных (только поля БД, без файлов) ---------- */

    protected function validateData(Request $request): array
    {
        // обязательные поля
        $rules = [
            'tip_stroki'            => 'required|in:product,product_variant,variant',
            'naimenovanie'          => 'required|string|max:255',
            'price'                 => 'required|numeric|min:0',
            'kategoriya'            => 'required|string|max:255',
            'v_nalichii_na_sklade'  => 'required|in:Да,Нет',
        ];

        // куча nullable string(255)
        $nullable255 = [
            'naimenovanie_artikula',
            'opisanije',
            'valyuta',
            'tip_tovara',
            'sku',
            'supplier_sku',
            'postavshik',
            'proizvoditel',
            'strana',
            'garantiya',
            'vysota',
            'shirina',
            'glubina',
            'ves',
            'tsvet',
            'material',
            'tolshchina_materiala',
            'gabarity',
            'toplivo',
            'kolichestvo_konteynerov',
            'obshchaya_liniya_ognya',
            'vysota_vstraivayemaya',
            'shirina_vstraivayemaya',
            'glubina_vstraivayemaya',
            'maksimalnoe_vremya_goreniya',
            'seriya_kaminov',
            'raskhod_biotopliva',
            'material_tb',
            'tip_biokamina',
            'tolshchina_ochaga',
            'tolshchina_kryshki_toplivnogo_bloka',
            'seriya_toplivnogo_bloka',
            'obshchiy_razmer_tb',
            'obem_toplivnogo_bloka',
            'obem_zalivaemogo_topliva',
            'klass_rashoda_biotopliva',
            'vid_ognya',
            'tolshchina_stekla',
            'material_ochaga',
            'kolichestvo_rezhimov_ognya',
            'tip_upravleniya',
            'sposob_upravleniya',
            'maksimalnaya_potrebljayemaya_moshchnost',
            'tip_gaza',
            'nominalnoe_vkhodnoe_davlenie',
            'minimalnoe_vkhodnoe_davlenie',
            'maksimalnoe_vkhodnoe_davlenie',
            'avtomaticheskoe_upravlenie',
            'tip_ustroystva',
            'shirina_dveri',
            'vysota_dveri',
            'diametr_dymokhoda',
            'prisoyedinenie_dymokhoda',
            'forma_stekla_i_dverey',
            'moshchnost',
            'maksimalnaya_dlinna_drov',
            'kpd',
            'sposob_otkrytiya_dvertsy',
            'nalichie_dymosbornika',
            'futerovka',
            'nalichie_zolnika',
            'teplooobmenik',
            'moshchnost_teplooobmenika',
            'tip_dverki',
            'podacha_vozdushka_izvne',
            'raspolozhenie_v_pomeshchenii',
            'dizayn_i_ispolnenie',
            'material_oblitcovki',
            'tip_nagrevanija',
            'optsii_kaminov_i_pechey',
            'vysota_kaminokomplekta',
            'nalichie_balki',
            'three_d',
            'vtorichnyy_dozhig',
            'sistema_chistogo_stekla',
            'klass_energoemkosti',
            'rezerv3',
            'tekhnologiya_plameni',
            'tip_ochaga',
            'obem',
            'moshchnost_obogreva',
            'pult_du',
            'otlichitelnye_osobennosti',
            'optsii',
            'tsvet_ochaga',
            'tsvet_dereva',
            'tsvet_kamnya',
            'material_korpusa',
            'rezerv',
            'diametr_dymokhoda_dymokhody',
            'rezerv1',
        ];

        foreach ($nullable255 as $field) {
            $rules[$field] = 'nullable|string|max:255';
        }

        // числовое нестрогое поле
        $rules['price2'] = 'nullable|numeric|min:0';

        // URL-картинок (они есть в БД)
        $rules['image_url'] = 'nullable|string|max:1000';
        for ($i = 1; $i <= 20; $i++) {
            $rules["image_url_{$i}"] = 'nullable|string|max:1000';
        }

        // здесь валидируем только текстовые/числовые поля
        return $request->validate($rules);
    }

    /* ---------- Отдельная валидация файлов (НЕ попадает в $data) ---------- */

    protected function validateImages(Request $request): void
    {
        $fileRules = [
            'image_file'      => 'nullable|image|max:5120',
            'images_multi.*'  => 'nullable|image|max:5120',
        ];

        for ($i = 1; $i <= 20; $i++) {
            $fileRules["image_file_{$i}"] = 'nullable|image|max:5120';
        }

        $request->validate($fileRules);
    }

    /* ---------- Обработка файлов и заполнение image_url* ---------- */

    protected function handleImages(Request $request, array &$data): void
    {
        // список полей-слотов
        $slots = ['image_url'];
        for ($i = 1; $i <= 20; $i++) {
            $slots[] = "image_url_{$i}";
        }

        // 1) одиночные поля: image_file, image_file_1..20
        //    сразу занимают свои слоты
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        for ($i = 1; $i <= 20; $i++) {
            $fileField = "image_file_{$i}";
            $slot      = "image_url_{$i}";

            if ($request->hasFile($fileField)) {
                $path = $request->file($fileField)->store('products', 'public');
                $data[$slot] = '/storage/' . $path;
            }
        }

        // 2) массовая загрузка: images_multi[]
        //    заполняем оставшиеся пустые слоты по очереди
        $multiFiles = $request->file('images_multi') ?? [];

        if (!empty($multiFiles)) {
            // приводим $data к виду: на всякий случай, чтобы ключи существовали
            foreach ($slots as $slot) {
                if (!array_key_exists($slot, $data)) {
                    $data[$slot] = $data[$slot] ?? ($data[$slot] ?? null);
                }
            }

            foreach ($multiFiles as $file) {
                if (!$file) {
                    continue;
                }

                // ищем первый пустой слот
                $targetSlot = null;
                foreach ($slots as $slot) {
                    $current = $data[$slot] ?? null;
                    if ($current === null || trim((string)$current) === '') {
                        $targetSlot = $slot;
                        break;
                    }
                }

                // если свободных слотов нет — просто выходим
                if (!$targetSlot) {
                    break;
                }

                $path = $file->store('products', 'public');
                $data[$targetSlot] = '/storage/' . $path;
            }
        }

        // ВАЖНО: никаких $data['image_file'], images_multi и т.п. — только image_url*
    }
}

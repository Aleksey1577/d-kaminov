<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    public function index(Request $request)
    {

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

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('naimenovanie', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

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

        $min = $filters['price_min'] ?? null;
        $max = $filters['price_max'] ?? null;

        if ($min !== null && $max !== null) {
            if ($min > $max) {
                [$min, $max] = [$max, $min];
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

        $data = $this->validateData($request);

        $this->validateImages($request);

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

        $typesByCategory = Product::whereNotNull('kategoriya')
            ->where('kategoriya', '!=', '')
            ->whereNotNull('tip_tovara')
            ->where('tip_tovara', '!=', '')
            ->get(['kategoriya', 'tip_tovara'])
            ->groupBy('kategoriya')
            ->map(fn($group) => $group->pluck('tip_tovara')->unique()->values()->all())
            ->toArray();

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

    protected function validateData(Request $request): array
    {

        $rules = [
            'tip_stroki'            => 'required|in:product,product_variant,variant',
            'naimenovanie'          => 'required|string|max:255',
            'price'                 => 'required|numeric|min:0',
            'kategoriya'            => 'required|string|max:255',
            'v_nalichii_na_sklade'  => 'required|in:Да,Нет',
        ];

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

        $rules['price2'] = 'nullable|numeric|min:0';

        $rules['image_url'] = 'nullable|string|max:1000';
        for ($i = 1; $i <= 20; $i++) {
            $rules["image_url_{$i}"] = 'nullable|string|max:1000';
        }

        return $request->validate($rules);
    }

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

    protected function handleImages(Request $request, array &$data): void
    {

        $slots = ['image_url'];
        for ($i = 1; $i <= 20; $i++) {
            $slots[] = "image_url_{$i}";
        }

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

        $multiFiles = $request->file('images_multi') ?? [];

        if (!empty($multiFiles)) {

            foreach ($slots as $slot) {
                if (!array_key_exists($slot, $data)) {
                    $data[$slot] = $data[$slot] ?? ($data[$slot] ?? null);
                }
            }

            foreach ($multiFiles as $file) {
                if (!$file) {
                    continue;
                }

                $targetSlot = null;
                foreach ($slots as $slot) {
                    $current = $data[$slot] ?? null;
                    if ($current === null || trim((string)$current) === '') {
                        $targetSlot = $slot;
                        break;
                    }
                }

                if (!$targetSlot) {
                    break;
                }

                $path = $file->store('products', 'public');
                $data[$targetSlot] = '/storage/' . $path;
            }
        }

    }

    public function export(string $format)
    {
        $format = Str::lower($format);
        if (!in_array($format, ['csv', 'xlsx'], true)) {
            abort(404);
        }

        $columns = $this->getProductColumns();
        $timestamp = now()->format('Y-m-d_H-i');
        $filename = "products_{$timestamp}.{$format}";

        if ($format === 'csv') {
            return response()->streamDownload(function () use ($columns) {
                $output = fopen('php://output', 'w');

                fwrite($output, "\xEF\xBB\xBF");
                fputcsv($output, $columns, ';');

                Product::query()
                    ->select($columns)
                    ->orderBy('product_id')
                    ->chunkById(500, function ($chunk) use ($output, $columns) {
                        foreach ($chunk as $product) {
                            $row = [];
                            foreach ($columns as $column) {
                                $row[] = $product->{$column};
                            }
                            fputcsv($output, $row, ';');
                        }
                    }, 'product_id');

                fclose($output);
            }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
        }

        $path = $this->buildXlsxExport($columns);

        return response()
            ->download($path, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])
            ->deleteFileAfterSend(true);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:51200', 'mimes:csv,txt,xlsx,tsv'],
        ]);

        $file = $request->file('file');
        $extension = Str::lower($file->getClientOriginalExtension());
        $path = $file->getRealPath();

        if (!$path) {
            return redirect()->route('admin.products')->with('error', 'Не удалось прочитать файл.');
        }

        if ($extension === 'tsv') {
            $extension = 'csv';
        }

        if (in_array($extension, ['csv', 'txt'], true)) {
            $rows = $this->parseCsvFile($path);
        } elseif ($extension === 'xlsx') {
            $rows = $this->parseXlsxFile($path);
        } else {
            return redirect()->route('admin.products')->with('error', 'Формат файла не поддерживается.');
        }

        if (count($rows) < 2) {
            return redirect()->route('admin.products')->with('error', 'Файл пустой или без данных.');
        }

        $columns = $this->getProductColumns();
        $columnLookup = [];
        foreach ($columns as $column) {
            $columnLookup[Str::lower($column)] = $column;
            $columnLookup[Str::slug($column, '_')] = $column;
        }
        $aliases = $this->getHeaderAliases();

        $buildIndexToColumn = function (array $header) use ($columnLookup, $aliases): array {
            $indexToColumn = [];
            foreach ($header as $index => $name) {
                $key = Str::lower($name);
                if ($key === '') {
                    continue;
                }
                if (isset($aliases[$key])) {
                    $key = $aliases[$key];
                }
                if (isset($columnLookup[$key])) {
                    $indexToColumn[$index] = $columnLookup[$key];
                }
            }
            return $indexToColumn;
        };

        $header = array_map([$this, 'normalizeHeaderCell'], $rows[0]);
        $indexToColumn = $buildIndexToColumn($header);

        if (empty($indexToColumn) && in_array($extension, ['csv', 'txt'], true)) {
            $fallbackDelimiters = ["\t", ';', ',', '|'];
            $bestMap = [];
            $bestRows = $rows;

            foreach ($fallbackDelimiters as $delimiter) {
                $candidateRows = $this->parseCsvFile($path, $delimiter);
                if (count($candidateRows) < 1) {
                    continue;
                }
                $candidateHeader = array_map([$this, 'normalizeHeaderCell'], $candidateRows[0]);
                $candidateMap = $buildIndexToColumn($candidateHeader);
                if (count($candidateMap) > count($bestMap)) {
                    $bestMap = $candidateMap;
                    $bestRows = $candidateRows;
                }
            }

            if (!empty($bestMap)) {
                $indexToColumn = $bestMap;
                $rows = $bestRows;
            }
        }

        if (empty($indexToColumn)) {
            return redirect()->route('admin.products')
                ->with('error', 'Не удалось распознать заголовки колонок. Скачайте экспорт и используйте его как шаблон.');
        }

        $columnTypes = $this->getColumnTypes($columns);
        $required = ['tip_stroki', 'naimenovanie', 'price', 'kategoriya', 'v_nalichii_na_sklade'];

        $created = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $rowIndex => $row) {
            if ($rowIndex === 0) {
                continue;
            }

            $data = [];
            $hasValues = false;

            foreach ($indexToColumn as $index => $column) {
                $value = $row[$index] ?? null;
                $normalized = $this->normalizeValue($column, $value, $columnTypes);
                $data[$column] = $normalized;
                if ($normalized !== null && $normalized !== '') {
                    $hasValues = true;
                }
            }

            if (!$hasValues) {
                continue;
            }

            $productId = $data['product_id'] ?? null;
            if ($productId !== null && $productId !== '' && !is_numeric($productId)) {
                $skipped++;
                $errors[] = "Строка " . ($rowIndex + 1) . ": неверный product_id.";
                continue;
            }

            $productId = $productId !== null && $productId !== '' ? (int) $productId : null;
            $data['product_id'] = $productId;

            $product = $productId !== null ? Product::find($productId) : null;
            $isNew = $product === null;
            if ($isNew) {
                $missing = [];
                foreach ($required as $field) {
                    if (!array_key_exists($field, $data) || $data[$field] === null || $data[$field] === '') {
                        $missing[] = $field;
                    }
                }

                if (!empty($missing)) {
                    $skipped++;
                    $errors[] = "Строка " . ($rowIndex + 1) . ": нет обязательных полей (" . implode(', ', $missing) . ").";
                    continue;
                }
            }

            if ($product) {
                $updateData = $data;
                unset($updateData['product_id']);
                $product->update($updateData);
                $updated++;
                continue;
            }

            if ($productId === null) {
                unset($data['product_id']);
            }

            Product::create($data);
            $created++;
        }

        $report = [
            'created' => $created,
            'updated' => $updated,
            'skipped' => $skipped,
            'errors' => array_slice($errors, 0, 20),
        ];

        $message = "Импорт завершён: создано {$created}, обновлено {$updated}, пропущено {$skipped}.";

        return redirect()
            ->route('admin.products')
            ->with('success', $message)
            ->with('import_report', $report);
    }

    protected function getProductColumns(): array
    {
        $columns = Schema::getColumnListing('products');
        $columns = array_values(array_diff($columns, ['created_at', 'updated_at']));

        if (in_array('product_id', $columns, true)) {
            $columns = array_values(array_diff($columns, ['product_id']));
            array_unshift($columns, 'product_id');
        }

        return $columns;
    }

    protected function getColumnTypes(array $columns): array
    {
        $types = [];
        foreach ($columns as $column) {
            try {
                $types[$column] = Schema::getColumnType('products', $column);
            } catch (\Throwable $e) {
                $types[$column] = 'string';
            }
        }
        return $types;
    }

    protected function normalizeHeaderCell($value): string
    {
        $text = trim((string) $value);
        $text = ltrim($text, "\xEF\xBB\xBF");
        if ($text === '') {
            return '';
        }

        $slug = Str::slug($text, '_');
        if ($slug !== '') {
            return $slug;
        }

        return (string) Str::of($text)
            ->lower()
            ->replace(['-', '.', ' '], '_')
            ->replaceMatches('/_+/', '_')
            ->trim('_');
    }

    protected function getHeaderAliases(): array
    {
        return [
            'id' => 'product_id',
            'productid' => 'product_id',
            'product_id' => 'product_id',
        ];
    }

    protected function normalizeValue(string $column, $value, array $columnTypes)
    {
        if ($value === null) {
            return null;
        }

        $text = trim((string) $value);
        if ($text === '') {
            return null;
        }

        $type = $columnTypes[$column] ?? 'string';
        if (in_array($type, ['integer', 'bigint', 'smallint'], true)) {
            return (int) preg_replace('/\s+/', '', $text);
        }

        if (in_array($type, ['decimal', 'float', 'double'], true)) {
            $normalized = str_replace([' ', ','], ['', '.'], $text);
            return is_numeric($normalized) ? $normalized : $text;
        }

        return $text;
    }

    protected function parseCsvFile(string $path, ?string $delimiter = null): array
    {
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return [];
        }

        $firstLine = fgets($handle);
        if ($firstLine === false) {
            fclose($handle);
            return [];
        }

        $firstLine = $this->stripBom($firstLine);
        $delimiter = $delimiter ?: $this->detectCsvDelimiter($firstLine);
        $rows = [];
        $rows[] = str_getcsv(rtrim($firstLine, "\r\n"), $delimiter);

        while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
            $rows[] = $data;
        }

        fclose($handle);

        return $rows;
    }

    protected function parseXlsxFile(string $path): array
    {
        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) {
            return [];
        }

        $sheetName = 'xl/worksheets/sheet1.xml';
        if ($zip->locateName($sheetName) === false) {
            $sheetName = null;
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $stat = $zip->statIndex($i);
                if (isset($stat['name']) && Str::startsWith($stat['name'], 'xl/worksheets/sheet')) {
                    $sheetName = $stat['name'];
                    break;
                }
            }
        }

        if (!$sheetName) {
            $zip->close();
            return [];
        }

        $sheetXml = $zip->getFromName($sheetName);
        if ($sheetXml === false) {
            $zip->close();
            return [];
        }

        $sharedStrings = [];
        $sharedXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedXml !== false) {
            $shared = simplexml_load_string($sharedXml);
            if ($shared) {
                $ns = $shared->getNamespaces(true);
                $main = $ns[''] ?? null;
                $items = $main ? $shared->children($main)->si : $shared->si;
                foreach ($items as $si) {
                    $text = '';
                    $texts = $main ? $si->children($main)->t : $si->t;
                    foreach ($texts as $t) {
                        $text .= (string) $t;
                    }
                    $sharedStrings[] = $text;
                }
            }
        }

        $sheet = simplexml_load_string($sheetXml);
        $zip->close();

        if (!$sheet) {
            return [];
        }

        $ns = $sheet->getNamespaces(true);
        $main = $ns[''] ?? null;
        $sheetData = $main ? $sheet->children($main)->sheetData : $sheet->sheetData;
        if (!$sheetData) {
            return [];
        }

        $rows = [];
        $maxColIndex = 0;

        $rowNodes = $main ? $sheetData->children($main)->row : $sheetData->row;
        foreach ($rowNodes as $row) {
            $rowData = [];
            $cellNodes = $main ? $row->children($main)->c : $row->c;
            foreach ($cellNodes as $cell) {
                $cellRef = (string) $cell['r'];
                $colIndex = $this->columnIndexFromCellReference($cellRef);
                $maxColIndex = max($maxColIndex, $colIndex);

                $type = (string) $cell['t'];
                $value = '';

                if ($type === 's') {
                    $idx = (int) $cell->v;
                    $value = $sharedStrings[$idx] ?? '';
                } elseif ($type === 'inlineStr') {
                    $text = '';
                    $inline = $main ? $cell->children($main)->is : $cell->is;
                    if ($inline) {
                        $texts = $main ? $inline->children($main)->t : $inline->t;
                        foreach ($texts as $t) {
                            $text .= (string) $t;
                        }
                    }
                    $value = $text;
                } else {
                    $value = (string) $cell->v;
                }

                $rowData[$colIndex] = $value;
            }

            if (!empty($rowData)) {
                $rows[] = $rowData;
            }
        }

        $normalized = [];
        foreach ($rows as $row) {
            $filled = [];
            for ($i = 0; $i <= $maxColIndex; $i++) {
                $filled[$i] = $row[$i] ?? '';
            }
            $normalized[] = array_values($filled);
        }

        return $normalized;
    }

    protected function buildXlsxExport(array $columns): string
    {
        $sheetPath = tempnam(sys_get_temp_dir(), 'products_sheet_');
        $handle = fopen($sheetPath, 'w');

        $columnLetters = [];
        foreach (range(1, count($columns)) as $index) {
            $columnLetters[] = $this->toExcelColumn($index);
        }

        fwrite($handle, '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>');
        fwrite($handle, '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>');

        $rowIndex = 1;
        $this->writeXlsxRow($handle, $rowIndex, $columns, $columnLetters);
        $rowIndex++;

        Product::query()
            ->select($columns)
            ->orderBy('product_id')
            ->chunkById(500, function ($chunk) use (&$rowIndex, $handle, $columns, $columnLetters) {
                foreach ($chunk as $product) {
                    $row = [];
                    foreach ($columns as $column) {
                        $row[] = $product->{$column};
                    }
                    $this->writeXlsxRow($handle, $rowIndex, $row, $columnLetters);
                    $rowIndex++;
                }
            }, 'product_id');

        fwrite($handle, '</sheetData></worksheet>');
        fclose($handle);

        $zipPath = tempnam(sys_get_temp_dir(), 'products_xlsx_');
        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $zip->addFromString('[Content_Types].xml', $this->getXlsxContentTypes());
        $zip->addFromString('_rels/.rels', $this->getXlsxRootRels());
        $zip->addFromString('xl/workbook.xml', $this->getXlsxWorkbookXml());
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->getXlsxWorkbookRels());
        $zip->addFromString('xl/styles.xml', $this->getXlsxStylesXml());
        $zip->addFile($sheetPath, 'xl/worksheets/sheet1.xml');
        $zip->close();

        @unlink($sheetPath);

        return $zipPath;
    }

    protected function writeXlsxRow($handle, int $rowIndex, array $values, array $columnLetters): void
    {
        $cells = [];
        foreach ($values as $index => $value) {
            $column = $columnLetters[$index] ?? $this->toExcelColumn($index + 1);
            $cellRef = $column . $rowIndex;
            $text = htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_XML1);
            $cells[] = '<c r="' . $cellRef . '" t="inlineStr"><is><t xml:space="preserve">' . $text . '</t></is></c>';
        }

        fwrite($handle, '<row r="' . $rowIndex . '">' . implode('', $cells) . '</row>');
    }

    protected function toExcelColumn(int $index): string
    {
        $letters = '';
        while ($index > 0) {
            $index--;
            $letters = chr(65 + ($index % 26)) . $letters;
            $index = intdiv($index, 26);
        }
        return $letters;
    }

    protected function columnIndexFromCellReference(string $cellRef): int
    {
        if (!preg_match('/^[A-Z]+/', $cellRef, $matches)) {
            return 0;
        }

        $letters = $matches[0];
        $index = 0;
        foreach (str_split($letters) as $letter) {
            $index = $index * 26 + (ord($letter) - 64);
        }

        return max(0, $index - 1);
    }

    protected function stripBom(string $text): string
    {
        return preg_replace('/^\xEF\xBB\xBF/', '', $text);
    }

    protected function detectCsvDelimiter(string $line): string
    {
        $delimiters = [',' => substr_count($line, ','), ';' => substr_count($line, ';'), "\t" => substr_count($line, "\t")];
        arsort($delimiters);
        return (string) key($delimiters);
    }

    protected function getXlsxContentTypes(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '</Types>';
    }

    protected function getXlsxRootRels(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>';
    }

    protected function getXlsxWorkbookXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '
            . 'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets>'
            . '<sheet name="Products" sheetId="1" r:id="rId1"/>'
            . '</sheets>'
            . '</workbook>';
    }

    protected function getXlsxWorkbookRels(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';
    }

    protected function getXlsxStylesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<fonts count="1"><font><sz val="11"/><color theme="1"/><name val="Calibri"/><family val="2"/></font></fonts>'
            . '<fills count="1"><fill><patternFill patternType="none"/></fill></fills>'
            . '<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/></cellXfs>'
            . '</styleSheet>';
    }
}

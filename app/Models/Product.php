<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';

    /**
     * Так как мы валидируем все поля в контроллере и массово заполняем модель,
     * проще и безопаснее разрешить всё:
     */
    protected $guarded = [];

    protected $appends = ['slug'];

    /*
     * Слаг: название + product_id, используется в URL
     */
    public function getSlugAttribute(): string
    {
        return Str::slug($this->naimenovanie) . '-' . $this->product_id;
    }

    /*
     * Варианты товара (строки с tip_stroki = 'variant')
     * Логика может быть адаптирована под твою схему.
     */
    public function variants()
    {
        return $this->hasMany(Product::class, 'product_id', 'product_id')
            ->where('tip_stroki', 'variant');
    }

    /*
     * Короткие характеристики для карточки товара
     */
    public function getShortCharacteristics(): array
    {
        $characteristics = [];

        if ($this->sku) {
            $characteristics['Артикул'] = $this->sku;
        }

        if ($this->proizvoditel) {
            $characteristics['Производитель'] = $this->proizvoditel;
        }

        if ($this->kategoriya) {
            $characteristics['Категория'] = $this->kategoriya;
        }

        if ($this->material) {
            $characteristics['Материал'] = $this->material;
        }

        // Ограничиваем до 5 характеристик
        return array_slice($characteristics, 0, 5);
    }

    /*
     * ==========================
     *  Переводы / лейблы полей
     * ==========================
     */

    /**
     * Все "человеческие" лейблы для технических полей
     * берём из config/product.php.
     */
    public static function extraLabels(): array
    {
        return config('product.extra_labels', []);
    }

    /**
     * Получить подпись для конкретного поля.
     * Если в конфиге нет — делаем что-то вроде "Tolshchina Materiala" → "Tolshchina Materiala".
     */
    public static function labelFor(string $field): string
    {
        $labels = static::extraLabels();

        if (isset($labels[$field])) {
            return $labels[$field];
        }

        return Str::of($field)->replace('_', ' ')->headline();
    }

    /*
     * Полный набор характеристик для страницы товара.
     * Использует те же лейблы, что и формы create/edit.
     */
    public function getFullCharacteristics(): array
    {
        $map = static::extraLabels();

        // При желании можно сюда же добавить базовые поля, если нужно:
        $base = [
            'proizvoditel' => 'Производитель',
            'strana'       => 'Страна производства',
            'garantiya'    => 'Гарантия',
            'valyuta'      => 'Валюта',
            'toplivo'      => 'Топливо',
            'tip_tovara'   => 'Тип товара',
        ];

        // Базовые добавляем в начало, не перезаписывая, если уже есть в extra_labels
        foreach ($base as $field => $label) {
            if (!isset($map[$field])) {
                $map[$field] = $label;
            }
        }

        $result = [];

        foreach ($map as $field => $label) {
            $value = $this->{$field} ?? null;

            if ($value !== null && trim((string) $value) !== '') {
                $result[$label] = $value;
            }
        }

        return $result;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'product_id', 'naimenovanie', 'price', 'image_url', 'kategoriya', 'v_nalichii_na_sklade',
        'opisanije', 'sku', 'proizvoditel', 'price2', 'material', 'vysota', 'shirina', 'glubina',
        'ves', 'tsvet', 'garantiya', 'image_url_1', 'image_url_2', 'image_url_3', 'tip_stroki'
    ];

    protected $appends = ['slug'];

    public function getSlugAttribute()
    {
        return Str::slug($this->naimenovanie) . '-' . $this->product_id;
    }

    public function variants()
    {
        return $this->hasMany(Product::class, 'product_id', 'product_id')
                    ->where('tip_stroki', 'variant');
    }
    
    public function getShortCharacteristics()
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

        // Ограничьте до 5 характеристик
        return array_slice($characteristics, 0, 5);
    }

    public function getFullCharacteristics()
    {
        // Маппинг полей на русские названия
        $fieldMap = [
            'naimenovanie' => 'Наименование',
            'price' => 'Цена',
            'image_url' => 'Изображение',
            'kategoriya' => 'Категория',
            'v_nalichii_na_sklade' => 'В наличии',
            'opisanije' => 'Описание',
            'sku' => 'Артикул',
            'proizvoditel' => 'Производитель',
            'price2' => 'Цена 2',
            'material' => 'Материал',
            'vysota' => 'Высота',
            'shirina' => 'Ширина',
            'glubina' => 'Глубина',
            'ves' => 'Вес',
            'tsvet' => 'Цвет',
            'garantiya' => 'Гарантия',
            'image_url_1' => 'Изображение 1',
            'image_url_2' => 'Изображение 2',
            'image_url_3' => 'Изображение 3',
        ];

        // Получаем все атрибуты
        $attributes = $this->toArray();

        // Исключаем служебные поля
        $excludedFields = ['product_id', 'naimenovanie', 'opisanije', 'price', 'valyuta', 'supplier_sku', 'postavshik', 'image_url', 'image_url_1', 'image_url_2', 'image_url_3', 'image_url_4', 'image_url_5', 'image_url_6', 'image_url_7', 'image_url_8', 'image_url_9', 'image_url_10', 'image_url_11', 'image_url_12', 'image_url_13', 'image_url_14', 'image_url_15', 'image_url_16', 'image_url_17', 'image_url_18', 'image_url_19', 'image_url_20', 'price2', 'seo_title', 'seo_description',  'tip_stroki', 'created_at', 'updated_at', 'id', 'slug'];

        $characteristics = [];
        foreach ($attributes as $key => $value) {
            if (!in_array($key, $excludedFields) && isset($fieldMap[$key]) && !empty($value)) {
                $characteristics[$fieldMap[$key]] = $value;
            }
        }

        return $characteristics;
    }
}
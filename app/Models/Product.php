<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';
    protected $guarded = [];
    protected $appends = ['slug', 'first_image_url', 'thumb_url'];

    public function getSlugAttribute(): string
    {
        return Str::slug($this->naimenovanie) . '-' . $this->product_id;
    }

    public function variants()
    {
        return $this->hasMany(Product::class, 'product_id', 'product_id')
            ->where('tip_stroki', 'variant');
    }

    public function getShortCharacteristics(): array
    {
        $c = [];
        if ($this->sku)          $c['Артикул'] = $this->sku;
        if ($this->proizvoditel) $c['Производитель'] = $this->proizvoditel;
        if ($this->kategoriya)   $c['Категория'] = $this->kategoriya;
        if ($this->material)     $c['Материал'] = $this->material;
        return array_slice($c, 0, 5);
    }

    public static function extraLabels(): array
    {
        return config('product.extra_labels', []);
    }

    public static function labelFor(string $field): string
    {
        $labels = static::extraLabels();
        return $labels[$field] ?? Str::of($field)->replace('_', ' ')->headline();
    }

    public function getFullCharacteristics(): array
    {
        $map = static::extraLabels();
        $base = [
            'proizvoditel' => 'Производитель',
            'strana'       => 'Страна производства',
            'garantiya'    => 'Гарантия',
            'valyuta'      => 'Валюта',
            'toplivo'      => 'Топливо',
            'tip_tovara'   => 'Тип товара',
        ];
        foreach ($base as $f => $l) {
            $map[$f] = $map[$f] ?? $l;
        }

        $res = [];
        foreach ($map as $field => $label) {
            $val = $this->{$field} ?? null;
            if ($val !== null && trim((string)$val) !== '') {
                $res[$label] = $val;
            }
        }
        return $res;
    }

    public function getFirstImageUrlAttribute(): ?string
    {
        $fields = array_merge(['image_url'], array_map(fn ($i) => "image_url_{$i}", range(1, 20)));
        foreach ($fields as $field) {
            $val = $this->{$field} ?? null;
            if ($val !== null && trim((string)$val) !== '') {
                return $val;
            }
        }
        return null;
    }

    protected function resolveExistingUrl(?string $url): ?string
    {
        if (!$url) return null;

        if (preg_match('~^(https?:)?//|^data:~i', $url)) {
            return $url;
        }

        if (Str::startsWith($url, '/storage/')) {
            $relative = Str::after($url, '/storage/');
            if (Storage::disk('public')->exists($relative)) {
                return $url;
            }
            return null;
        }

        return $url;
    }

    public function getThumbUrlAttribute(): string
    {
        $candidate = $this->resolveExistingUrl($this->first_image_url);
        return $candidate ?: asset('assets/placeholder.png');
    }
}

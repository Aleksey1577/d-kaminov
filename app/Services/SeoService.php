<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class SeoService
{
    protected array $state = [
        'title'       => null,
        'description' => null,
        'keywords'    => null,
        'robots'      => 'index,follow',
        'canonical'   => null,
        'locale'      => 'ru_RU',
        'site_name'   => 'D-Kaminov',
        'twitter'     => '@dkaminov',
        'image'       => null, // абсолютный URL к превью (OG/Twitter)
        'hreflangs'   => [],   // [['lang' => 'ru', 'url' => '...']]
        'breadcrumbs' => [],   // [['name' => 'Каталог', 'url' => url('/catalog')]]
        'og'          => [],   // доп. OG-поля
        'jsonld'      => [],   // дополнительные JSON-LD блоки
    ];

    /** Дефолты для страниц */
    protected array $pages = [
        'home' => [
            'title'       => 'D-Kaminov — Камины и печи в Самаре | Купить камин под ключ',
            'description' => 'D-Kaminov: топки, печи, биокамины, электрокамины и аксессуары. Монтаж под ключ по Самаре и области.',
            'keywords'    => 'дом каминов, дом каминов официальный сайт, d-kaminov, дом каминов сайт, дом каминов самара',
        ],
        'delivery' => [
            'title'       => 'Доставка каминов и печей по Самаре и области | D-Kaminov',
            'description' => 'Быстрая и бережная доставка каминов, печей и комплектующих по Самаре и области. Самовывоз и помощь в подъёме.',
            'keywords'    => 'доставка каминов Самара, доставка печей Самара, доставка D-Kaminov',
        ],
        'montage' => [
            'title'       => 'Монтаж и установка печей, каминов и топок в Самаре | D-Kaminov',
            'description' => 'Профессиональный монтаж и установка печей, каминов и топок под ключ в Самаре и области. Выезд на объект, соблюдение норм, гарантия.',
            'keywords'    => 'монтаж печи Самара, монтаж каминов Самара, установка печи Самара, установка камина Самара, монтаж топок Самара, установка топок Самара',
        ],
        'portfolio' => [
            'title'       => 'Наши работы — установленные камины и печи | D-Kaminov',
            'description' => 'Фото и кейсы: профессиональный монтаж каминов и печей под ключ. 15+ лет опыта.',
            'keywords'    => 'наши работы, фото каминов, проекты каминов, монтаж каминов, работы дом каминов',
        ],
        'contacts' => [
            'title'       => 'Контакты | D-Kaminov — Камины и печи в Самаре',
            'description' => 'Телефон, email и адрес в Самаре. Работаем ежедневно с 9:00 до 19:00.',
            'keywords'    => 'контакты дом каминов, телефон D-Kaminov, адрес каминов Самара, телефон дом каминов, адрес дом каминов',
        ],
    ];

    public function getProduct(array $data): array
    {
        $name = $data['naimenovanie'] ?? '';

        $title = $data['seo_title']
            ?? ($name ? $name . ' | Купить в D-Kaminov' : ($this->defaults['title'] ?? 'D-Kaminov'));
        $desc  = $data['seo_description']
            ?? ($name
                ? 'Купить ' . $name . ' в D-Kaminov. Доставка по Самаре и России. Гарантия.'
                : ($this->defaults['description'] ?? '')
            );
        $keys  = $data['seo_keywords'] ?? ($this->defaults['keywords'] ?? '');

        // Нормируем длины под мета-теги
        $title = \Illuminate\Support\Str::limit(trim($title), 60);
        $desc  = \Illuminate\Support\Str::limit(strip_tags(trim($desc)), 160);

        return [
            'seo_title'       => $title,
            'seo_description' => $desc,
            'seo_keywords'    => $keys,
        ];
    }

    /** Глобальные дефолты (fallback) */
    protected array $defaults = [
        'title'       => 'D-Kaminov — Камины и печи в Самаре',
        'description' => 'Интернет-магазин каминов, печей и аксессуаров в Самаре. Профессиональный монтаж под ключ.',
        'keywords'    => 'камины Самара, печи, биокамины, электрокамины, топки, аксессуары, монтаж каминов',
        'image'       => '/img/og-default.jpg', // положи файл в public/img/og-default.jpg
    ];

    /* ------------------------ БАЗОВЫЕ СЕТТЕРЫ ------------------------ */

    public function forPage(string $key, array $override = []): static
    {
        $data = array_merge($this->defaults, $this->pages[$key] ?? []);
        $data = array_merge($data, $override);

        return $this->fill($data);
    }

    public function forProduct(array $p, array $override = []): static
    {
        $p = array_merge($p, $override);

        $name = (string) (Arr::get($p, 'naimenovanie') ?? '');

        $title = (string) (Arr::get($p, 'seo_title') ?: ($name ? $name . ' | Купить в D-Kaminov' : $this->defaults['title']));
        $desc  = (string) (Arr::get($p, 'seo_description') ?: ($name ? 'Купить ' . $name . ' в D-Kaminov. Доставка по Самаре и России. Гарантия.' : $this->defaults['description']));
        $keys  = (string) (Arr::get($p, 'seo_keywords') ?: $this->defaults['keywords']);

        $image = (string) (Arr::get($p, 'image_abs') ?: Arr::get($p, 'image') ?: $this->absolute($this->defaults['image']));
        $image = $this->absolute($image);
        $url   = (string) (Arr::get($p, 'url') ?: URL::current());

        $this->fill([
            'title'       => $title,
            'description' => $desc,
            'keywords'    => $keys,
            'image'       => $image,
        ]);

        $price = Arr::get($p, 'price');
        $price = is_numeric($price) ? (float) $price : null;

        $rawStock = (string) (Arr::get($p, 'v_nalichii_na_sklade') ?? Arr::get($p, 'availability') ?? '');
        $stock = Str::of($rawStock)->lower()->trim();
        $availability = 'https://schema.org/InStock';
        if ($stock->contains(['под заказ', 'предзаказ', 'preorder'])) {
            $availability = 'https://schema.org/PreOrder';
        } elseif ($stock->contains(['нет', 'отсут', 'out of stock', '0'])) {
            $availability = 'https://schema.org/OutOfStock';
        } elseif (in_array((string) $stock, ['да', 'в наличии', 'есть', '1', 'true'], true)) {
            $availability = 'https://schema.org/InStock';
        }

        $descriptionSource = (string) (
            Arr::get($p, 'opisanije')
            ?: Arr::get($p, 'opisanie')
            ?: Arr::get($p, 'short')
            ?: $desc
        );

        $offers = Arr::get($p, 'offers');
        if (!is_array($offers) || !isset($offers['@type'])) {
            $offers = [
                '@type'         => 'Offer',
                'url'           => $url,
                'priceCurrency' => 'RUB',
                'price'         => $price !== null ? number_format($price, 0, '.', '') : null,
                'availability'  => $availability,
            ];
        }

        $this->og([
            'og:type' => 'product',
        ]);
        if ($price !== null) {
            $this->og([
                'product:price:amount' => number_format($price, 0, '.', ''),
                'product:price:currency' => 'RUB',
            ]);
        }

        // JSON-LD Product
        $productLd = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Product',
            'name'        => $name ?: null,
            'sku'         => Arr::get($p, 'sku') ?: null,
            'brand'       => Arr::get($p, 'brand') ? ['@type' => 'Brand', 'name' => Arr::get($p, 'brand')] : null,
            'image'       => [$image],
            'description' => Str::limit(strip_tags($descriptionSource), 500),
            'offers'      => array_filter($offers, fn($v) => !is_null($v)),
            'url'         => $url,
        ];
        $this->pushJsonLd(array_filter($productLd, fn($v) => !is_null($v) && $v !== ''));

        return $this;
    }

    public function getPage(string $page): array
    {
        // Вернёт массив как раньше: ['title' => ..., 'description' => ..., 'keywords' => ...]
        $data = array_merge($this->defaults, $this->pages[$page] ?? []);
        return [
            'title'       => $data['title']       ?? $this->defaults['title'],
            'description' => $data['description'] ?? $this->defaults['description'],
            'keywords'    => $data['keywords']    ?? $this->defaults['keywords'],
        ];
    }

    public function title(string $v): static
    {
        $this->state['title'] = $v;
        return $this;
    }
    public function description(string $v): static
    {
        $this->state['description'] = $v;
        return $this;
    }
    public function keywords(string $v): static
    {
        $this->state['keywords'] = $v;
        return $this;
    }
    public function robots(string $v): static
    {
        $this->state['robots'] = $v;
        return $this;
    }

    public function canonical(?string $url = null): static
    {
        $this->state['canonical'] = $url ? $this->absolute($url) : URL::current();
        return $this;
    }

    public function image(string $url): static
    {
        $this->state['image'] = $this->absolute($url);
        return $this;
    }

    public function addHreflang(string $lang, string $url): static
    {
        $this->state['hreflangs'][] = ['lang' => $lang, 'url' => $this->absolute($url)];
        return $this;
    }

    public function breadcrumb(string $name, ?string $url = null): static
    {
        $this->state['breadcrumbs'][] = ['name' => $name, 'url' => $url ? $this->absolute($url) : null];
        return $this;
    }

    /**
     * @return array<int, array{name: string, url: string|null}>
     */
    public function breadcrumbs(): array
    {
        return $this->state['breadcrumbs'];
    }

    public function og(array $pairs): static
    {
        $this->state['og'] = array_merge($this->state['og'], $pairs);
        return $this;
    }

    public function pushJsonLd(array $block): static
    {
        $this->state['jsonld'][] = $block;
        return $this;
    }

    public function fill(array $data): static
    {
        foreach (['title', 'description', 'keywords', 'image'] as $k) {
            if (!empty($data[$k])) $this->state[$k] = $k === 'image' ? $this->absolute($data[$k]) : $data[$k];
        }
        return $this;
    }

    /* ------------------------ РЕНДЕР В <head> ------------------------ */

    public function render(): string
    {
        $t = $this->clean(Str::limit($this->state['title']       ?: $this->defaults['title'],       70));
        $d = $this->clean(Str::limit($this->state['description'] ?: $this->defaults['description'], 165));
        $k = $this->clean($this->state['keywords'] ?: $this->defaults['keywords']);

        $canonical = $this->state['canonical'] ?: URL::current();
        $image     = $this->state['image']     ?: $this->absolute($this->defaults['image']);

        // базовые JSON-LD: Organization, WebSite + SearchAction, BreadcrumbList
        $org = [
            '@context' => 'https://schema.org',
            '@type'    => 'Organization',
            'name'     => $this->state['site_name'],
            'url'      => url('/'),
            'logo'     => $image,
        ];
        $site = [
            '@context' => 'https://schema.org',
            '@type'    => 'WebSite',
            'name'     => $this->state['site_name'],
            'url'      => url('/'),
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => url('/search') . '?q={q}',
                'query-input' => 'required name=q',
            ],
        ];
        $breadcrumbs = $this->state['breadcrumbs'];
        if (!empty($breadcrumbs)) {
            $list = [
                '@context' => 'https://schema.org',
                '@type'    => 'BreadcrumbList',
                'itemListElement' => [],
            ];
            foreach (array_values($breadcrumbs) as $i => $bc) {
                $list['itemListElement'][] = [
                    '@type'    => 'ListItem',
                    'position' => $i + 1,
                    'name'     => $bc['name'],
                    'item'     => $bc['url'] ?: URL::current(),
                ];
            }
            $this->pushJsonLd($list);
        }

        $jsonBlocks = array_merge([$org, $site], $this->state['jsonld']);
        $json = '';
        foreach ($jsonBlocks as $blk) {
            $json .= '<script type="application/ld+json">' . json_encode($blk, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</script>\n";
        }

        // OG/Twitter
        $og = array_merge([
            'og:title'       => $t,
            'og:description' => $d,
            'og:type'        => 'website',
            'og:url'         => $canonical,
            'og:site_name'   => $this->state['site_name'],
            'og:locale'      => $this->state['locale'],
            'og:image'       => $image,
        ], $this->state['og']);

        $tags = [];
        $tags[] = "<title>{$t}</title>";
        $tags[] = '<meta name="description" content="' . $d . '">';
        $tags[] = '<meta name="keywords" content="' . $k . '">';
        $tags[] = '<meta name="robots" content="' . $this->state['robots'] . '">';
        $tags[] = '<link rel="canonical" href="' . $canonical . '">';

        foreach ($this->state['hreflangs'] as $alt) {
            $tags[] = '<link rel="alternate" href="' . $alt['url'] . '" hreflang="' . $this->clean($alt['lang']) . '">';
        }

        foreach ($og as $prop => $val) {
            $tags[] = '<meta property="' . $prop . '" content="' . $this->clean($val) . '">';
        }

        $tags[] = '<meta name="twitter:card" content="summary_large_image">';
        $tags[] = '<meta name="twitter:title" content="' . $t . '">';
        $tags[] = '<meta name="twitter:description" content="' . $d . '">';
        $tags[] = '<meta name="twitter:image" content="' . $image . '">';

        return implode("\n", $tags) . "\n" . $json;
    }

    /* ------------------------ ВСПОМОГАТЕЛЬНЫЕ ------------------------ */

    protected function clean(?string $v): string
    {
        $v = (string) ($v ?? '');
        $v = trim(strip_tags($v));
        return htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
    }

    protected function absolute(string $path): string
    {
        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }
        return url($path);
    }
}

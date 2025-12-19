@props([
    'items' => [],
])

@php
    $items = is_array($items) ? $items : [];
    $items = array_values(array_filter($items, function ($item) {
        if (!is_array($item)) return false;
        $name = trim((string)($item['name'] ?? ''));
        return $name !== '';
    }));
@endphp

@if (count($items) > 1)
    <nav aria-label="Хлебные крошки" class="text-sm">
        <ol class="flex flex-wrap items-center gap-2 text-slate-600">
            @foreach ($items as $index => $item)
                @php
                    $name = trim((string)($item['name'] ?? ''));
                    $url  = trim((string)($item['url'] ?? ''));
                    $isLast = $index === count($items) - 1;
                @endphp
                <li class="flex items-center gap-2">
                    @if ($index > 0)
                        <span class="text-slate-300">/</span>
                    @endif

                    @if (!$isLast && $url !== '')
                        <a href="{{ $url }}" class="hover:text-orange transition">
                            {{ $name }}
                        </a>
                    @else
                        <span class="text-slate-900 font-medium" aria-current="page">
                            {{ $name }}
                        </span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif


@props([
    'href',
    'icon',
    'alt',
    'text' => '',
    'badge' => 0,
    'class' => '',
])

@php
    $role = $alt === 'Избранное' ? 'favorites' : ($alt === 'Сравнение' ? 'compare' : ($alt === 'Корзина' ? 'cart' : ''));
    $badgeClass = $role ? "{$role}-badge" : '';
@endphp

<a href="{{ $href }}" class="relative flex flex-col items-center group {{ $class }}" data-role="{{ $role }}">
    <img src="{{ asset($icon) }}" alt="{{ $alt }}" class="w-6 h-6">
    @if($text)
        <span class="text-xs text-gray-700 group-hover:text-orange">{{ $text }}</span>
    @endif

    <span
        class="{{ $badgeClass }} absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 bg-red-600 text-white rounded-full text-xs px-1.5 py-0.5 {{ $badge > 0 ? '' : 'hidden' }}"
        data-badge="{{ $role }}"
    >
        {{ $badge }}
    </span>
</a>

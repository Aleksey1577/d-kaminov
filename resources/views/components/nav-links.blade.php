@php
    $navLink = 'px-3 py-2 rounded-xl text-slate-800 hover:text-orange hover:bg-amber-50 transition font-semibold';
@endphp

<a href="{{ route('montage') }}" class="{{ $navLink }}">Монтаж</a>
<a href="{{ route('portfolio') }}" class="{{ $navLink }}">Наши работы</a>
<a href="{{ route('delivery') }}" class="{{ $navLink }}">Доставка и оплата</a>
<a href="{{ route('contacts') }}" class="{{ $navLink }}">Контакты</a>

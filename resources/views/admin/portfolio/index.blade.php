@extends('layouts.admin')

@section('title', 'Портфолио')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold">Портфолио</h1>
            <p class="text-sm text-gray-500">Карточки страницы “Наши работы”.</p>
        </div>
        <a href="{{ route('admin.portfolio.create') }}" class="inline-flex items-center gap-2 bg-orange text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-orange-white transition">
            + Новая карточка
        </a>
    </div>

    @if($items->isEmpty())
        <p class="text-gray-600">Пока нет карточек.</p>
    @else
        <div class="overflow-x-auto border rounded-lg">
            <table class="min-w-full border-collapse text-sm">
                <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="border-b p-3 text-left font-semibold">Позиция</th>
                    <th class="border-b p-3 text-left font-semibold">Фото</th>
                    <th class="border-b p-3 text-left font-semibold">Заголовок</th>
                    <th class="border-b p-3 text-left font-semibold">Подзаголовок</th>
                    <th class="border-b p-3 text-left font-semibold">Активна</th>
                    <th class="border-b p-3 text-left font-semibold">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="border-t p-3 align-middle">{{ $item->position }}</td>
                        <td class="border-t p-3 align-middle">
                            @if($item->image_url)
                                <img src="{{ \Illuminate\Support\Str::startsWith($item->image_url, ['http://', 'https://']) ? $item->image_url : asset(ltrim($item->image_url, '/')) }}" alt="{{ $item->title }}" class="w-20 h-12 object-cover rounded border bg-white">
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="border-t p-3 align-middle">{{ $item->title }}</td>
                        <td class="border-t p-3 align-middle">
                            <div class="max-w-md text-gray-700 line-clamp-2">{{ $item->subtitle ?? '—' }}</div>
                        </td>
                        <td class="border-t p-3 align-middle">
                            <span class="px-2 py-1 rounded text-xs font-semibold {{ $item->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $item->is_active ? 'Да' : 'Нет' }}</span>
                        </td>
                        <td class="border-t p-3 align-middle">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.portfolio.edit', $item) }}" class="text-blue-600 hover:underline">Редактировать</a>
                                <form action="{{ route('admin.portfolio.destroy', $item) }}" method="POST" onsubmit="return confirm('Удалить карточку?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Удалить</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

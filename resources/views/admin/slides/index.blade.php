@extends('layouts.admin')

@section('title', 'Слайды главной')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold">Слайды главной</h1>
            <p class="text-sm text-gray-500">Первый слайд про доставку фиксирован, остальные настраиваются здесь.</p>
        </div>
        <a href="{{ route('admin.slides.create') }}" class="inline-flex items-center gap-2 bg-orange text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-orange-white transition">
            + Новый слайд
        </a>
    </div>

    @if($slides->isEmpty())
        <p class="text-gray-600">Пока нет слайдов.</p>
    @else
        <div class="overflow-x-auto border rounded-lg">
            <table class="min-w-full border-collapse text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="border-b p-3 text-left font-semibold">Позиция</th>
                        <th class="border-b p-3 text-left font-semibold">Заголовок</th>
                        <th class="border-b p-3 text-left font-semibold">Категория</th>
                            <th class="border-b p-3 text-left font-semibold">Активен</th>
                            <th class="border-b p-3 text-left font-semibold">Цвет текста</th>
                            <th class="border-b p-3 text-left font-semibold">Действия</th>
                        </tr>
                </thead>
                <tbody>
                    @foreach($slides as $slide)
                        <tr class="hover:bg-gray-50">
                            <td class="border-t p-3 align-middle">{{ $slide->position }}</td>
                            <td class="border-t p-3 align-middle">{{ $slide->title }}</td>
                            <td class="border-t p-3 align-middle">{{ $slide->category ?? '—' }}</td>
                            <td class="border-t p-3 align-middle">
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $slide->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $slide->is_active ? 'Да' : 'Нет' }}</span>
                            </td>
                            <td class="border-t p-3 align-middle">{{ $slide->text_color === 'light' ? 'Светлый' : 'Тёмный' }}</td>
                            <td class="border-t p-3 align-middle">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.slides.edit', $slide) }}" class="text-blue-600 hover:underline">Редактировать</a>
                                    <form action="{{ route('admin.slides.destroy', $slide) }}" method="POST" onsubmit="return confirm('Удалить слайд?');">
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

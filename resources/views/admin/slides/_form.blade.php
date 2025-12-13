@php
    $isEdit = isset($slide);
@endphp

<form action="{{ $isEdit ? route('admin.slides.update', $slide) : route('admin.slides.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Заголовок</label>
            <input type="text" name="title" value="{{ old('title', $slide->title ?? '') }}" class="w-full border rounded-md px-3 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Подзаголовок</label>
            <input type="text" name="subtitle" value="{{ old('subtitle', $slide->subtitle ?? '') }}" class="w-full border rounded-md px-3 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Текст кнопки</label>
            <input type="text" name="button_text" value="{{ old('button_text', $slide->button_text ?? 'В каталог') }}" class="w-full border rounded-md px-3 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Категория для ссылки</label>
            <select name="category" class="w-full border rounded-md px-3 py-2.5 text-sm">
                <option value="">— Не выбрано —</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" @selected(old('category', $slide->category ?? '') === $cat)>{{ $cat }}</option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 mt-1">Кнопка будет вести в каталог с параметром ?category=...</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Цвет текста</label>
            <select name="text_color" class="w-full border rounded-md px-3 py-2.5 text-sm">
                @foreach(['dark' => 'Тёмный (для светлого фона)', 'light' => 'Светлый (для тёмного фона)'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('text_color', $slide->text_color ?? 'dark') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-2 space-y-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Изображение</label>
            <input type="file" name="image_file" accept="image/jpeg,image/png,image/webp,.jpg,.jpeg,.png,.webp" class="w-full border rounded-md px-3 py-2.5 text-sm bg-white">
            <p class="text-xs text-gray-500">Можно указать файл или URL ниже.</p>
            <input type="text" name="image_url" value="{{ old('image_url', $slide->image_url ?? '') }}" class="w-full border rounded-md px-3 py-2.5 text-sm" placeholder="/assets/... или https://...">
            @if(!empty($slide?->image_url))
                <div class="flex items-center gap-3 text-sm">
                    <img src="{{ \Illuminate\Support\Str::startsWith($slide->image_url, ['http://', 'https://']) ? $slide->image_url : asset(ltrim($slide->image_url, '/')) }}" alt="preview" class="w-20 h-12 object-cover rounded border">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="remove_image" value="1" class="h-4 w-4">
                        <span class="text-gray-700">Удалить изображение</span>
                    </label>
                </div>
            @endif
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Позиция</label>
            <input type="number" name="position" value="{{ old('position', $slide->position ?? 1) }}" min="1" class="w-full border rounded-md px-3 py-2.5 text-sm">
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $slide->is_active ?? true)) class="h-4 w-4">
            <label for="is_active" class="text-sm text-gray-700">Активен</label>
        </div>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.slides.index') }}" class="px-4 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">Отмена</a>
        <button type="submit" class="px-4 py-2 text-sm bg-orange text-white rounded-md hover:bg-orange-white transition">
            {{ $isEdit ? 'Сохранить' : 'Создать' }}
        </button>
    </div>
</form>

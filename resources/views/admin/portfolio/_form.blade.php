@php
    $isEdit = isset($portfolioItem);
@endphp

<form action="{{ $isEdit ? route('admin.portfolio.update', $portfolioItem) : route('admin.portfolio.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Заголовок</label>
            <input type="text" name="title" value="{{ old('title', $portfolioItem->title ?? '') }}" class="w-full border rounded-md px-3 py-2.5 text-sm" required>
            @error('title') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Позиция</label>
            <input type="number" name="position" value="{{ old('position', $portfolioItem->position ?? 1) }}" min="1" class="w-full border rounded-md px-3 py-2.5 text-sm">
            @error('position') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Подзаголовок</label>
            <textarea name="subtitle" rows="3" class="w-full border rounded-md px-3 py-2.5 text-sm">{{ old('subtitle', $portfolioItem->subtitle ?? '') }}</textarea>
            @error('subtitle') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="md:col-span-2 space-y-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Изображение</label>
            <input type="file" name="image_file" accept="image/jpeg,image/png,image/webp,.jpg,.jpeg,.png,.webp" class="w-full border rounded-md px-3 py-2.5 text-sm bg-white">
            @error('image_file') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror

            <p class="text-xs text-gray-500">Можно указать файл или URL ниже.</p>
            <input type="text" name="image_url" value="{{ old('image_url', $portfolioItem->image_url ?? '') }}" class="w-full border rounded-md px-3 py-2.5 text-sm" placeholder="/assets/... или /storage/...">
            @error('image_url') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror

            @if(!empty($portfolioItem?->image_url))
                <div class="flex items-center gap-3 text-sm">
                    <img src="{{ \Illuminate\Support\Str::startsWith($portfolioItem->image_url, ['http://', 'https://']) ? $portfolioItem->image_url : asset(ltrim($portfolioItem->image_url, '/')) }}" alt="preview" class="w-20 h-12 object-cover rounded border bg-white">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="remove_image" value="1" class="h-4 w-4">
                        <span class="text-gray-700">Удалить изображение</span>
                    </label>
                </div>
            @endif
        </div>

        <div class="flex items-center gap-2 md:col-span-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $portfolioItem->is_active ?? true)) class="h-4 w-4">
            <label for="is_active" class="text-sm text-gray-700">Активна</label>
        </div>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.portfolio.index') }}" class="px-4 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">Отмена</a>
        <button type="submit" class="px-4 py-2 text-sm bg-orange text-white rounded-md hover:bg-orange-white transition">
            {{ $isEdit ? 'Сохранить' : 'Создать' }}
        </button>
    </div>
</form>

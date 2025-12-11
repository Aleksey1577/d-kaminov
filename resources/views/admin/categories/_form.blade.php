@php
    $isEdit = isset($category);
    $action = $isEdit ? route('admin.categories.update', $category) : route('admin.categories.store');
    $buttonText = $isEdit ? 'Сохранить' : 'Создать';
    $nameValue = old('name', $category->name ?? '');
@endphp

<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div>
        <label for="name" class="block text-gray-700 mb-1 text-sm font-medium">Название</label>
        <input type="text"
               name="name"
               id="name"
               value="{{ $nameValue }}"
               class="w-full border rounded-md px-3 py-2.5 text-sm"
               required>
        @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.categories') }}"
           class="px-4 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">Отмена</a>
        <button type="submit"
                class="px-4 py-2 text-sm bg-orange text-white rounded-md hover:bg-orange-white transition">
            {{ $buttonText }}
        </button>
    </div>
</form>

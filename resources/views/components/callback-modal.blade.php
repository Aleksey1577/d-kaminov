<div x-data="{ isOpen: false }" x-show="isOpen" x-cloak
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    @open-callback.window="isOpen = true" @keydown.escape.window="isOpen = false">
    <div class="bg-white p-6 rounded-lg max-w-md w-full mx-4" @click.outside="isOpen = false">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Заказать звонок</h2>
            <button @click="isOpen = false" class="text-gray-500 hover:text-gray-700">
                ✖
            </button>
        </div>

        <form action="{{ route('callback') }}" method="POST"
            @submit.prevent="
                fetch('{{ route('callback') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({
                        name: document.getElementById('callback-name').value,
                        phone: document.getElementById('callback-phone').value,
                        comment: document.getElementById('callback-comment').value
                    })
                })
                .then(response => {
                    if (response.ok) {
                        alert('Заявка отправлена!');
                        isOpen = false;
                    } else {
                        alert('Ошибка при отправке формы');
                    }
                })">
            @csrf
            <div class="mb-4">
                <label for="callback-name" class="block text-gray-700 mb-1">Имя</label>
                <input type="text" id="callback-name" name="name" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label for="callback-phone" class="block text-gray-700 mb-1">Телефон</label>
                <input type="tel" id="callback-phone" name="phone" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label for="callback-comment" class="block text-gray-700 mb-1">Комментарий</label>
                <textarea id="callback-comment" name="comment" class="w-full border rounded px-3 py-2" rows="3"></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" @click="isOpen = false"
                    class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">
                    Отмена
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Отправить
                </button>
            </div>
        </form>
    </div>
</div>

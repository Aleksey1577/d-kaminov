<div x-data="{
        isOpen: false,
        loading: false,
        success: false,
        error: '',
        form: { name: '', phone: '', comment: '' },
        submit() {
            this.loading = true;
            this.error = '';
            this.success = false;

            fetch('{{ route('callback') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify(this.form)
            })
            .then(response => {
                if (!response.ok) throw new Error('Ошибка при отправке. Попробуйте ещё раз.');
                this.success = true;
                this.form = { name: '', phone: '', comment: '' };
                setTimeout(() => { this.isOpen = false; this.success = false; }, 1600);
            })
            .catch(err => this.error = err.message || 'Ошибка')
            .finally(() => this.loading = false);
        }
    }"
    x-show="isOpen"
    x-cloak
    class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 px-4"
    @open-callback.window="isOpen = true" @keydown.escape.window="isOpen = false">

    <div class="surface p-6 sm:p-7 w-full max-w-md shadow-2xl relative" @click.outside="isOpen = false">
        <button @click="isOpen = false" class="absolute top-3 right-3 text-slate-400 hover:text-slate-600">
            ✖
        </button>

        <div class="mb-4">
            <p class="eyebrow mb-2">Позвоните нам</p>
            <h2 class="text-2xl font-bold text-slate-900">Заказать звонок</h2>
            <p class="text-sm text-slate-600 mt-1">Оставьте контакты — перезвоним в течение 15 минут.</p>
        </div>

        <template x-if="success">
            <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-emerald-800 text-sm">
                Заявка отправлена! Мы свяжемся с вами.
            </div>
        </template>
        <template x-if="error">
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-red-800 text-sm" x-text="error"></div>
        </template>

        <form @submit.prevent="submit">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="callback-name" class="block text-sm font-semibold text-slate-800 mb-1">Имя</label>
                    <input type="text" id="callback-name" name="name" x-model="form.name"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 focus:border-orange focus:ring focus:ring-orange/20"
                        required>
                </div>
                <div>
                    <label for="callback-phone" class="block text-sm font-semibold text-slate-800 mb-1">Телефон</label>
                    <input type="tel" id="callback-phone" name="phone" x-model="form.phone"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 focus:border-orange focus:ring focus:ring-orange/20"
                        required>
                </div>
                <div>
                    <label for="callback-comment" class="block text-sm font-semibold text-slate-800 mb-1">Комментарий</label>
                    <textarea id="callback-comment" name="comment" x-model="form.comment"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 focus:border-orange focus:ring focus:ring-orange/20"
                        rows="3" placeholder="Удобное время, модель камина..."></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-5">
                <button type="button" @click="isOpen = false"
                    class="btn-ghost px-4 py-2 text-sm">
                    Отмена
                </button>
                <button type="submit"
                    class="btn-primary px-4 py-2 text-sm"
                    :disabled="loading">
                    <span x-show="!loading">Отправить</span>
                    <span x-show="loading" class="flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <circle cx="12" cy="12" r="10" stroke-width="4" class="opacity-25"></circle>
                            <path d="M4 12a8 8 0 018-8" stroke-width="4" class="opacity-75"></path>
                        </svg>
                        Отправляем...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

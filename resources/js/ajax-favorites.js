// resources/js/ajax-favorites.js

// ---- Общий апдейтер бейджей в хедере ----
function updateHeaderBadge(type, count) {
    // type: 'favorites' | 'compare' | 'cart'
    let badge = document.querySelector(`span[data-badge="${type}"]`);
    if (!badge) {
        // Если по каким-то причинам нет — создадим и вставим в соответствующую ссылку
        const link = document.querySelector(`[data-role="${type}"]`);
        if (!link) return;
        badge = document.createElement('span');
        badge.dataset.badge = type;
        badge.className = `${type}-badge absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 bg-red-600 text-white rounded-full text-xs px-1.5 py-0.5 hidden`;
        link.appendChild(badge);
    }
    badge.textContent = count;
    if (Number(count) > 0) {
        badge.classList.remove('hidden');
        badge.style.display = ''; // на случай inline-стилей
    } else {
        badge.classList.add('hidden');
    }
}

function csrfToken() {
    const el = document.querySelector('meta[name="csrf-token"]');
    return el ? el.getAttribute('content') : '';
}

document.addEventListener('DOMContentLoaded', function () {
    // ---------- ИЗБРАННОЕ ----------
    document.querySelectorAll('.favorites-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const fd = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: fd,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken(),
                },
            })
            .then(r => {
                if (!r.ok) throw new Error(`HTTP ${r.status}`);
                return r.json();
            })
            .then(data => {
                // Текущее состояние
                let isFavorite = form.dataset.isFavorite === 'true';
                const button = form.querySelector('.favorites-button');
                const icon = form.querySelector('.favorites-icon');

                if (data.status === 'added' || data.status === 'removed') {
                    // Инвертируем локально
                    isFavorite = !isFavorite;
                    form.dataset.isFavorite = isFavorite ? 'true' : 'false';

                    if (isFavorite) {
                        // Переключаем action на remove
                        form.action = form.dataset.removeUrl;

                        // Добавляем _method=DELETE, если нет
                        let methodInput = form.querySelector('input[name="_method"]');
                        if (!methodInput) {
                            methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';
                            form.appendChild(methodInput);
                        }

                        // Стили/атрибуты
                        if (button) {
                            button.classList.remove('bg-gray-100', 'text-gray-800', 'hover:bg-gray-200');
                            button.classList.add('bg-red-100', 'text-red-500');
                            button.title = 'Удалить из избранного';
                        }
                        if (icon) icon.setAttribute('fill', 'currentColor');
                    } else {
                        // Переключаем action на add
                        form.action = form.dataset.addUrl;

                        // Удаляем _method=DELETE
                        const methodInput = form.querySelector('input[name="_method"]');
                        if (methodInput) methodInput.remove();

                        // Стили/атрибуты
                        if (button) {
                            button.classList.remove('bg-red-100', 'text-red-500');
                            button.classList.add('bg-gray-100', 'text-gray-800', 'hover:bg-gray-200');
                            button.title = 'Добавить в избранное';
                        }
                        if (icon) icon.setAttribute('fill', 'none');
                    }

                    // Обновляем бейдж в хедере
                    if (typeof data.count !== 'undefined') {
                        updateHeaderBadge('favorites', data.count);
                    }
                }
            })
            .catch(err => {
                console.error('Favorites AJAX error:', err);
            });
        });
    });

    // ---------- СРАВНЕНИЕ ----------
    document.querySelectorAll('.compare-form').forEach(form => {
        const checkbox = form.querySelector('input[type="checkbox"][name="compare"]');
        if (!checkbox) return;

        checkbox.addEventListener('change', function () {
            const isChecked = this.checked;

            // Переключение action + _method
            if (isChecked) {
                // add
                const del = form.querySelector('input[name="_method"]');
                if (del) del.remove();
                form.action = form.dataset.addUrl;
            } else {
                // remove (DELETE)
                form.action = form.dataset.removeUrl;
                if (!form.querySelector('input[name="_method"]')) {
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);
                }
            }

            const fd = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: fd,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken(),
                },
            })
            .then(r => {
                if (!r.ok) throw new Error(`HTTP ${r.status}`);
                return r.json();
            })
            .then(data => {
                if (data.status === 'added') {
                    form.dataset.isCompared = 'true';
                    checkbox.checked = true;
                } else if (data.status === 'removed') {
                    form.dataset.isCompared = 'false';
                    checkbox.checked = false;
                }

                if (typeof data.count !== 'undefined') {
                    updateHeaderBadge('compare', data.count);
                }
            })
            .catch(err => {
                console.error('Compare AJAX error:', err);
                // Откатываем чекбокс, чтобы состояние визуально совпадало
                checkbox.checked = !isChecked;
                alert('Ошибка: ' + err.message);
            });
        });
    });
});

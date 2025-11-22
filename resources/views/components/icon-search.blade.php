@props(['position' => 'right'])

<div x-data="{ searchOpen: false }" class="relative">
    <button @click="searchOpen = !searchOpen" type="button" class="focus:outline-none flex flex-col items-center group">
        <img src="{{ asset('assets/header/find-search.svg') }}" alt="Поиск" class="w-6 h-6 filter brightness-50 group-hover:brightness-100 transition-all duration-300">
        <span class="text-xs text-gray-600 group-hover:text-orange transition-colors duration-300">Поиск</span>
    </button>

    <!-- Поле поиска -->
    <form 
        x-show="searchOpen"
        @click.away="searchOpen = false"
        x-transition
        method="GET"
        action="{{ route('search') }}"
        class="absolute {{ $position === 'left' ? 'left-0' : 'right-0' }} mt-2 w-64 bg-white  rounded shadow z-50 p-2"
    >
        <input
            type="text"
            name="search"
            placeholder="Поиск по наименованию или артикулу"
            class="w-full px-3 py-2 border rounded text-sm focus:outline-none focus:border-orange"
            autofocus
        >
    </form>
</div>
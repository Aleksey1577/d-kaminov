@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-8 flex">


        <!-- Основное содержимое -->
        <main class="flex-1">
            <h1 class="text-2xl font-bold mb-6">Аналитика посещений</h1>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Пользователь</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Страна</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Устройство</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($visits as $visit)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $visit->user?->name ?? 'Гость' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $visit->ip_address }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($visit->country_code)
                                    <img src="https://flagsapi.com/{{ $visit->country_code }}/flat/64.png" alt="{{ $visit->country }}" class="inline w-5 h-3 mr-2">
                                @endif
                                {{ $visit->country ?? 'Не определено' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ ucfirst($visit->device_type) ?? 'Не определено' }}
                            </td>
                            <td class="px-6 py-4">{{ $visit->url }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $visit->created_at->format('d.m.Y H:i') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Нет данных</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
@endsection
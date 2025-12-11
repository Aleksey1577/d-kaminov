@extends('layouts.admin')

@section('title', 'Аналитика')

@section('content')
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
            <div>
                <h1 class="text-2xl font-bold">Аналитика посещений</h1>
                <p class="text-sm text-gray-500">Последние визиты пользователей</p>
            </div>
        </div>

        <div class="border rounded-lg">
            <table class="w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Пользователь</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">IP</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Страна</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Устройство</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">URL</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Дата</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($visits as $visit)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $visit->user?->name ?? 'Гость' }}</td>
                            <td class="px-4 py-3">{{ $visit->ip_address }}</td>
                            <td class="px-4 py-3">
                                @if ($visit->country_code)
                                    <img src="https://flagsapi.com/{{ $visit->country_code }}/flat/64.png" alt="{{ $visit->country }}" class="inline w-5 h-3 mr-2">
                                @endif
                                {{ $visit->country ?? 'Не определено' }}
                            </td>
                            <td class="px-4 py-3">{{ ucfirst($visit->device_type) ?? 'Не определено' }}</td>
                            <td class="px-4 py-3 break-all">{{ $visit->url }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $visit->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">Нет данных</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $visits->links() }}
        </div>
    </div>
@endsection

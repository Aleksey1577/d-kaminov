@extends('layouts.admin')

@section('title', 'Пользователи')

@section('content')
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
            <div>
                <h1 class="text-2xl font-bold">Пользователи</h1>
                <p class="text-sm text-gray-500">Список учетных записей и прав</p>
            </div>
        </div>

        <div class="overflow-x-auto border rounded-lg">
            <table class="min-w-full border-collapse text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="border-b p-3 text-left font-semibold">ID</th>
                        <th class="border-b p-3 text-left font-semibold">Имя</th>
                        <th class="border-b p-3 text-left font-semibold">Email</th>
                        <th class="border-b p-3 text-left font-semibold">Админ</th>
                        <th class="border-b p-3 text-left font-semibold w-32">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="border-t p-3 align-middle">#{{ $user->id }}</td>
                            <td class="border-t p-3 align-middle">{{ $user->name }}</td>
                            <td class="border-t p-3 align-middle">{{ $user->email }}</td>
                            <td class="border-t p-3 align-middle">
                                <span class="inline-flex px-2 py-1 rounded text-xs font-semibold {{ $user->is_admin ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $user->is_admin ? 'Да' : 'Нет' }}
                                </span>
                            </td>
                            <td class="border-t p-3 align-middle">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:underline">Редактировать</a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Удалить пользователя?');">
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

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
@endsection

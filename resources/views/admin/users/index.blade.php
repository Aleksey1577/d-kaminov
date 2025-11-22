@extends('layouts.admin')

@section('title', 'Пользователи')

@section('content')
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Пользователи</h1>

        <table class="min-w-full border-collapse mb-6">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-3 text-left">ID</th>
                    <th class="border p-3 text-left">Имя</th>
                    <th class="border p-3 text-left">Email</th>
                    <th class="border p-3 text-left">Админ</th>
                    <th class="border p-3 text-left">Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="border p-3">{{ $user->id }}</td>
                        <td class="border p-3">{{ $user->name }}</td>
                        <td class="border p-3">{{ $user->email }}</td>
                        <td class="border p-3">
                            {{ $user->is_admin ? 'Да' : 'Нет' }}
                        </td>
                        <td class="border p-3 space-x-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:underline">Редактировать</a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Удалить пользователя?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    </div>
@endsection
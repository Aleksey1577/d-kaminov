@extends('layouts.admin')

@section('title', 'Редактировать слайд')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold">Редактировать слайд</h1>
            <p class="text-sm text-gray-500">ID: {{ $slide->id }}</p>
        </div>
        <a href="{{ route('admin.slides.index') }}" class="text-sm px-3 py-2 rounded-md border border-gray-200 hover:bg-gray-50">← Назад</a>
    </div>

    @include('admin.slides._form')
</div>
@endsection

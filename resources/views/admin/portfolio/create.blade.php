@extends('layouts.admin')

@section('title', 'Добавить карточку')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6 max-w-4xl">
    <h1 class="text-2xl font-bold mb-4">Новая карточка</h1>
    @include('admin.portfolio._form')
</div>
@endsection


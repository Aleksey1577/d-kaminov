@extends('layouts.app')

@section('title', 'Страница не найдена — 404')

@section('content')
<div class="container py-5">
    <div class="text-center">
        <h1 style="font-size: 70px; font-weight: 700;">404</h1>
        <h2 class="mb-4">Страница не найдена</h2>
        <p class="mb-4">
            Похоже, вы перешли по неверной ссылке или страница была удалена.
        </p>

        <a href="{{ url('/') }}" class="btn btn-primary">
            Вернуться на главную
        </a>
    </div>
</div>
@endsection

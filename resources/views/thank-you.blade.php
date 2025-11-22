@extends('layouts.app')

@section('title', 'Заказ оформлен')

@section('content')
    <div class="text-center py-12">
        <h1 class="text-3xl font-bold mb-4">Спасибо за заказ!</h1>
        <p class="text-gray-600 mb-6">Ваш заказ был успешно оформлен. Мы свяжемся с вами в ближайшее время.</p>
        <a href="{{ route('home') }}" class="inline-block bg-orange hover:bg-blue-700 text-white py-2 px-6 rounded">
            Вернуться на главную
        </a>
    </div>
@endsection
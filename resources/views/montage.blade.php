@extends('layouts.app')

@section('seo_title', $seoData['title'])
@section('seo_description', $seoData['description'])
@section('seo_keywords', $seoData['keywords'])

@section('content')
<div class="p-2 max-w-6xl mx-auto">
    <header class="mb-8 text-center">
        <h1 class="text-4xl font-bold text-gray-800">Монтаж Дом каминов в Самаре — печи, камины и топки</h1>
    </header>

    <section class="mb-8">
        <p class="text-lg text-gray-700">
            Дом каминов выполняет монтаж печей, монтаж каминов и монтаж/установку топок в Самаре и области — под ключ и с соблюдением норм. Правильный монтаж гарантирует безопасность, долговечность и эффективность.
        </p>
    </section>

    <section class="mb-8">
        <h2 class="text-3xl text-center font-semibold text-gray-800 mb-4">Какие работы выполняем</h2>
        <ul class="list-disc pl-6 text-gray-700 space-y-2">
            <li>Установка печи (дровяные, печи-камины) и подключение к дымоходу.</li>
            <li>Установка камина и монтаж облицовки/портала.</li>
            <li>Монтаж топки (встраиваемые решения), герметизация и проверка тяги.</li>
            <li>Монтаж дымохода, проходные узлы, теплоизоляция и пусконаладка.</li>
        </ul>
    </section>

    <section class="mb-8">
        <h2 class="text-3xl text-center font-semibold text-gray-800 mb-4">Почему важен профессиональный монтаж?</h2>
        <ul class="list-disc pl-6 text-gray-700 space-y-2">
            <li><span class="font-semibold">Безопасность</span> — некорректная установка может привести к пожароопасным ситуациям.</li>
            <li><span class="font-semibold">Эффективность</span> — правильно установленный камин работает максимально эффективно.</li>
            <li><span class="font-semibold">Соответствие нормам</span> — выполняем монтаж с соблюдением всех строительных и пожарных норм.</li>
            <li><span class="font-semibold">Долговечность</span> — профессиональная установка продлевает срок службы устройства.</li>
        </ul>
    </section>

    <div class="mt-10 p-6 bg-white rounded-md" x-data="{ activeTab: 'Печи/Камины' }">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Этапы монтажа</h2>
        <div class="mb-6 border-b border-gray-300">
            <ul class="flex justify-center space-x-6">
                <li>
                    <button @click="activeTab = 'Печи/Камины'" class="py-2 px-4 border-b-2 transition-colors" :class="activeTab === 'Печи/Камины' ? 'border-orange text-orange rounded-t-lg' : 'border-transparent text-gray-700 hover:text-orange'">Печи/Камины</button>
                </li>
                <li>
                    <button @click="activeTab = 'Биокамины'" class="py-2 px-4 border-b-2 transition-colors" :class="activeTab === 'Биокамины' ? 'border-orange text-orange' : 'border-transparent text-gray-700 hover:text-orange'">Биокамины</button>
                </li>
                <li>
                    <button @click="activeTab = 'Электрокамины'" class="py-2 px-4 border-b-2 transition-colors" :class="activeTab === 'Электрокамины' ? 'border-orange text-orange' : 'border-transparent text-gray-700 hover:text-orange'">Электрокамины</button>
                </li>
            </ul>
        </div>

        <div x-show="activeTab === 'Печи/Камины'">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Этапы монтажа печей и каминов</h3>
            <ol class="list-decimal ml-6 space-y-3 text-gray-700">
                <li><span class="font-semibold">Консультация и подбор:</span> Выбираем модель с учетом площади, мощности и пожеланий.</li>
                <li><span class="font-semibold">Выезд на объект:</span> Оцениваем место, вентиляцию и нормы.</li>
                <li><span class="font-semibold">Подготовка места:</span> Разметка, огнеупорное покрытие, защита стен, подготовка дымохода.</li>
                <li><span class="font-semibold">Монтаж дымохода:</span> Сборка системы, изоляция узлов, герметизация, тестирование тяги.</li>
                <li><span class="font-semibold">Установка печи/камина:</span> Размещение, подключение, монтаж экранов.</li>
                <li><span class="font-semibold">Герметизация и тестирование:</span> Проверка соединений, пробный запуск, оценка безопасности.</li>
                <li><span class="font-semibold">Отделка:</span> Облицовка термостойкими материалами, монтаж порталов.</li>
                <li><span class="font-semibold">Инструктаж:</span> Правила эксплуатации, розжиг, уход.</li>
                <li><span class="font-semibold">Гарантия:</span> Обслуживание, осмотры по запросу.</li>
            </ol>
        </div>

        <div x-show="activeTab === 'Биокамины'">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Этапы монтажа биокаминов</h3>
            <ol class="list-decimal ml-6 space-y-3 text-gray-700">
                <li><span class="font-semibold">Консультация и подбор:</span> Выбираем модель по интерьеру, мощности пламени и типу установки.</li>
                <li><span class="font-semibold">Выезд и подготовка:</span> Оцениваем место, проверяем материалы, готовим нишу/подставку.</li>
                <li><span class="font-semibold">Установка:</span> Монтаж настенного/встраиваемого/напольного биокамина, фиксация конструкции.</li>
                <li><span class="font-semibold">Подключение и тестирование:</span> Установка топливного блока, заливка топлива, тестовый запуск.</li>
                <li><span class="font-semibold">Инструктаж:</span> Правила эксплуатации, дозаправки, уход.</li>
                <li><span class="font-semibold">Гарантия:</span> Обслуживание, очистка горелки.</li>
            </ol>
        </div>

        <div x-show="activeTab === 'Электрокамины'">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Этапы монтажа электрокаминов</h3>
            <ol class="list-decimal ml-6 space-y-3 text-gray-700">
                <li><span class="font-semibold">Консультация и подбор:</span> Выбираем модель по интерьеру, мощности и функциям (пульт, пар).</li>
                <li><span class="font-semibold">Выезд и подготовка:</span> Проверяем розетку, готовим нишу/каркас.</li>
                <li><span class="font-semibold">Монтаж:</span> Размещение напольного/настенного/встраиваемого, фиксация.</li>
                <li><span class="font-semibold">Подключение и настройка:</span> Электросеть, тест обогрева/пламени, вентиляция.</li>
                <li><span class="font-semibold">Инструктаж:</span> Управление, безопасность, уход.</li>
                <li><span class="font-semibold">Гарантия:</span> Обслуживание, очистка экрана.</li>
            </ol>
        </div>
    </div>
</div>
@endsection

@push('structured-data')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Service",
  "serviceType": "Монтаж Дом каминов — печи, камины и топки в Самаре",
  "provider": {
    "@type": "Organization",
    "name": "Дом каминов"
  },
  "areaServed": [
    { "@type": "City", "name": "Самара" },
    { "@type": "AdministrativeArea", "name": "Самарская область" }
  ],
  "description": @json($seoData['description'])
}
</script>
@endpush

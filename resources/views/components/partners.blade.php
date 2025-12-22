@once
<style>
    @keyframes partners-marquee {
        0% {
            transform: translateX(0);
        }

        100% {
            /* смещаемся на половину, т.к. контент продублирован */
            transform: translateX(-50%);
        }
    }

    .partners-marquee {
        display: flex;
        width: max-content;
        animation: partners-marquee 40s linear infinite;
    }

    /* Пауза при наведении */
    .partners-marquee:hover {
        animation-play-state: paused;
    }
</style>
@endonce

@php
$partners = [
['logo' => asset('assets/partners/1-zavod_litkom.png'), 'alt' => 'Завод Литком'],
['logo' => asset('assets/partners/abx-logo.png'), 'alt' => 'ABX'],
['logo' => asset('assets/partners/aston-logo.png'), 'alt' => 'Aston'],
['logo' => asset('assets/partners/ballu-logo.png'), 'alt' => 'Ballu'],
['logo' => asset('assets/partners/Defro.png'), 'alt' => 'Defro'],
['logo' => asset('assets/partners/echa-tech.png'), 'alt' => 'Echa Tech'],
['logo' => asset('assets/partners/esma-logo.svg'), 'alt' => 'Esma'],
['logo' => asset('assets/partners/etna-logo.svg'), 'alt' => 'Etna'],
['logo' => asset('assets/partners/everest.svg'), 'alt' => 'Everest'],
['logo' => asset('assets/partners/FireBird-logo.png'), 'alt' => 'FireBird'],
['logo' => asset('assets/partners/INVICTA-logo.png'), 'alt' => 'Invicta'],
['logo' => asset('assets/partners/kratki-loga.png'), 'alt' => 'Kratki'],
['logo' => asset('assets/partners/LOGO-KRATKI-PRO.webp'), 'alt' => 'Kratki Pro'],
['logo' => asset('assets/partners/logo-schiedel.png'), 'alt' => 'Schiedel'],
['logo' => asset('assets/partners/Logo-termofor.png'), 'alt' => 'Termofor'],
['logo' => asset('assets/partners/logo-top-mobile.svg'), 'alt' => 'Esma2'],
['logo' => asset('assets/partners/NMK-logo.svg'), 'alt' => 'NMK'],
['logo' => asset('assets/partners/nordpeis_logo.webp'), 'alt' => 'Nordpeis'],
['logo' => asset('assets/partners/realflame-logo.png'), 'alt' => 'RealFlame'],
['logo' => asset('assets/partners/royalflame-logo.png'), 'alt' => 'RoyalFlame'],
['logo' => asset('assets/partners/steelheat-logo.png'), 'alt' => 'Steelheat'],
['logo' => asset('assets/partners/vezuvii.svg'), 'alt' => 'Vezuvii'],
['logo' => asset('assets/partners/Logo_warmhaus.png'), 'alt' => 'Warmhaus'],
['logo' => asset('assets/partners/interflame_logo.png'), 'alt' => 'interflame'],
['logo' => asset('assets/partners/ecokamin-logo.png'), 'alt' => 'ecokamin'],
['logo' => asset('assets/partners/schmid-logo.png'), 'alt' => 'schmid'],
['logo' => asset('assets/partners/spartherm.png'), 'alt' => 'spartherm'],

];
@endphp

<div class="section p-6 sm:p-8 md:p-10 mb-12 overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-8">
        <div>
            <div class="eyebrow">Нам доверяют</div>
            <h2 class="section-title">Бренды, с которыми мы работаем</h2>
            <p class="section-lead text-base">Оригинальная продукция и официальная поддержка.</p>
        </div>
    </div>

    <div class="relative h-40">
        <div class="absolute inset-0 flex items-center overflow-hidden">
            <div class="partners-marquee">
                @foreach (array_merge($partners, $partners) as $partner)

                <div class="flex-shrink-0 w-44 sm:w-52 md:w-60 lg:w-72 px-6">
                    <div class="h-28 flex items-center justify-center">
                        <img src="{{ $partner['logo'] }}"
                            alt="{{ $partner['alt'] }}"
                            class="max-h-full max-w-full object-contain"
                            loading="lazy"
                            decoding="async">
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

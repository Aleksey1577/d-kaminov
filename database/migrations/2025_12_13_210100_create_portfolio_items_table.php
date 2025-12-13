<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->string('image_url', 1024)->nullable();
            $table->unsignedInteger('position')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $now = now();

        DB::table('portfolio_items')->insert([
            [
                'title' => 'Дымоход',
                'subtitle' => 'Коттедж. Дымоход из нержавеющей стали AISI 430 с усиленной тягой и теплоизоляцией.',
                'image_url' => '/assets/work/work1.jpg',
                'position' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Угловая топка',
                'subtitle' => 'Загородный дом. Дровяная угловая топка с КПД 78%, обогрев до 80 м².',
                'image_url' => '/assets/work/work2.jpg',
                'position' => 2,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Угловая топка',
                'subtitle' => 'Загородный дом. Угловая дровяная топка с панорамным стеклом — компактное размещение, эффект живого огня, обогрев до 40 м².',
                'image_url' => '/assets/work/work3.jpg',
                'position' => 3,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Топка',
                'subtitle' => 'Загородный дом. Настенная топка с LED-имитацией пламени, пульт ДУ, обогрев до 25 м².',
                'image_url' => '/assets/work/work4.jpg',
                'position' => 4,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Угловая топка',
                'subtitle' => 'Коттедж. Дровяная угловая топка с кирпичной облицовкой, чугунной дверцей и системой "чистое стекло".',
                'image_url' => '/assets/work/work5.jpg',
                'position' => 5,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Топка',
                'subtitle' => 'Загородный дом. Металлическая топка с панорамным стеклом — современный дизайн, экономия пространства.',
                'image_url' => '/assets/work/work6.jpg',
                'position' => 6,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Топка',
                'subtitle' => 'Загородный дом. Чугунная топка с варочной плитой — готовим и греемся одновременно.',
                'image_url' => '/assets/work/work7.jpg',
                'position' => 7,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Печь камин',
                'subtitle' => 'Загородный дом. Двухсторонняя печь-камин — огонь виден с двух сторон, создаёт атмосферу.',
                'image_url' => '/assets/work/work8.jpg',
                'position' => 8,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Угловая топка',
                'subtitle' => 'Загородный дом. Компактная угловая электрокамин-топка с 3D-эффектом пламени, безопасна для детей.',
                'image_url' => '/assets/work/work9.jpg',
                'position' => 9,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Угловая топка',
                'subtitle' => 'Элитный коттедж. Итальянская угловая топка, мраморный портал, автоматика и дистанционное управление.',
                'image_url' => '/assets/work/work11.jpg',
                'position' => 10,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Угловая топка',
                'subtitle' => 'Беседка в загородном доме. Угловая топка с функциями гриля и коптильни — многофункциональный уличный очаг.',
                'image_url' => '/assets/work/work12.jpg',
                'position' => 11,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Топка',
                'subtitle' => 'Сруб в загородной зоне. Топка с дымоходом через крышу, усиленная теплоизоляция, полная пожаробезопасность.',
                'image_url' => '/assets/work/work13.jpg',
                'position' => 12,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Биокамин',
                'subtitle' => 'Лофт-апартаменты. Линейный биокамин длиной 1.2 м — эффект "живого огня" по всей длине.',
                'image_url' => '/assets/work/work14.jpg',
                'position' => 13,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Угловая топка',
                'subtitle' => 'Загородный дом без газа. Угловая дровяная топка с водяным контуром — обогрев дома + горячая вода.',
                'image_url' => '/assets/work/work15.jpg',
                'position' => 14,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Печь камин с дымоходом',
                'subtitle' => 'Загородный дом. Встроенная печь-камин с дымоходом в нише — идеально вписана в интерьер, экономия места.',
                'image_url' => '/assets/work/work16.jpg',
                'position' => 15,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Печь камин с дымоходом',
                'subtitle' => 'Вилла в загородной зоне. Печь-камин с трёхсторонним остеклением — обзор пламени 270°, премиум-класс.',
                'image_url' => '/assets/work/work17.jpg',
                'position' => 16,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_items');
    }
};


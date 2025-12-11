<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SlideFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'subtitle' => $this->faker->sentence(10),
            'button_text' => 'В каталог',
            'category' => $this->faker->randomElement(['Биокамины', 'Порталы', 'Электроочаги']),
            'image_url' => $this->faker->imageUrl(1200, 800, 'fireplace', true),
            'position' => 1,
            'is_active' => true,
        ];
    }
}

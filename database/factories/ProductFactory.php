<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);

        return [
            'naimenovanie' => ucfirst($name),
            'price' => $this->faker->randomFloat(2, 1000, 50000),
            'kategoriya' => $this->faker->randomElement(['biokamin', 'electro', 'gazovaya']),
            'v_nalichii_na_sklade' => $this->faker->randomElement(['Да', 'Нет']),
            'image_url' => null,
            'opisanije' => $this->faker->sentence(8),
            'sku' => $this->faker->ean13(),
            'proizvoditel' => $this->faker->company(),
            'price2' => null,
        ];
    }
}

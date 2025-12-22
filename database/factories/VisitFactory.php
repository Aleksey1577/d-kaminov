<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VisitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'country' => $this->faker->country(),
            'country_code' => $this->faker->countryCode(),
            'device_type' => $this->faker->randomElement(['desktop', 'mobile', 'tablet']),
            'url' => $this->faker->url(),
        ];
    }
}

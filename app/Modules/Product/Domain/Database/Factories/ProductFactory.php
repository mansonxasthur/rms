<?php

namespace App\Modules\Product\Domain\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Product\Domain\Entities\Product>
 */
class ProductFactory extends Factory
{
    protected $model = \App\Modules\Product\Domain\Entities\Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->text(100),
            'price' => $this->faker->randomFloat(2, 10, 100),
        ];
    }
}

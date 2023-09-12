<?php

namespace App\Modules\Ingredient\Domain\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Ingredient\Domain\Entities\Ingredient>
 */
class IngredientFactory extends Factory
{
    protected $model = \App\Modules\Ingredient\Domain\Entities\Ingredient::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->text(100),
        ];
    }
}

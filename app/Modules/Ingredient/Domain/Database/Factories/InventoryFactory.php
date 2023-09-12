<?php

namespace App\Modules\Ingredient\Domain\Database\Factories;

use App\Modules\Ingredient\Domain\Enum\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Ingredient\Domain\Entities\Inventory;
use App\Modules\Ingredient\Domain\Entities\Ingredient;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Inventory>
 */
class InventoryFactory extends Factory
{
    protected $model = Inventory::class;

    public function definition(): array
    {
        return [
            'ingredient_id' => Ingredient::factory(),
            'stock_level' => $level = rand(5000, 26000),
            'current_level' => rand(0, $level),
            'unit' => Unit::Grams,
            'threshold' => 50,
        ];
    }
}

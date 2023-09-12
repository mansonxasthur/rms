<?php

namespace App\Modules\Ingredient\Domain\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Ingredient\Domain\Entities\Ingredient;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Inventory\Domain\Entities\StockAlert>
 */
class StockAlertFactory extends Factory
{
    protected $model = \App\Modules\Ingredient\Domain\Entities\StockAlert::class;

    public function definition(): array
    {
        return [
            'ingredient_id' => Ingredient::factory(),
            'email_sent_at' => now(),
            'resolved_at' => null,
        ];
    }
}

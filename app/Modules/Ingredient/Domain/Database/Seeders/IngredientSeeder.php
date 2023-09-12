<?php

namespace App\Modules\Ingredient\Domain\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Ingredient\Domain\Entities\Inventory;
use App\Modules\Ingredient\Domain\Entities\Ingredient;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = [
            ['name' => 'Beef', 'stock_level' => 20000],
            ['name' => 'Cheese', 'stock_level' => 5000],
            ['name' => 'Onion', 'stock_level' => 1000],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::factory()->state(['name' => $ingredient['name']])
                ->has(Inventory::factory()->state(['stock_level' => $ingredient['stock_level']]))
                ->create();
        }
    }
}

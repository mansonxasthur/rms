<?php

namespace App\Modules\Product\Domain\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Product\Domain\Entities\Product;
use App\Modules\Ingredient\Domain\Entities\Inventory;
use App\Modules\Ingredient\Domain\Entities\Ingredient;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        if (Product::count()) {
            return;
        }

        $ingredients = [
            ['name' => 'Beef', 'stock_level' => 20000, 'burger_recipe' => '150'],
            ['name' => 'Cheese', 'stock_level' => 5000, 'burger_recipe' => '30'],
            ['name' => 'Onion', 'stock_level' => 1000, 'burger_recipe' => '20'],
        ];

        $recipe = [];
        foreach ($ingredients as $ingredient) {
            $i = Ingredient::factory()->state(['name' => $ingredient['name']])
                ->has(Inventory::factory()->state(['stock_level' => $ingredient['stock_level'], 'current_level' => $ingredient['stock_level']]))
                ->create();
            $recipe[$i->id] = ['amount' => $ingredient['burger_recipe']];
        }

        $product = Product::factory()->state(['name' => 'Burger', 'price' => '100.50'])->create();
        $product->ingredients()->attach($recipe);
    }
}

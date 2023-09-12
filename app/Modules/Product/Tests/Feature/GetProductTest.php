<?php

namespace App\Modules\Product\Tests\Feature;

use Tests\TestCase;
use App\Modules\Product\Domain\Entities\Product;
use App\Modules\Ingredient\Domain\Entities\Inventory;
use App\Modules\Ingredient\Domain\Entities\Ingredient;

class GetProductTest extends TestCase
{
    /**
     * @test
     *
     * @group product
     */
    public function it_can_get_product(): void
    {
        $product = Product::factory()
            ->hasAttached(
                Ingredient::factory(3)
                    ->has(Inventory::factory()),
                ['amount' => 50]
            )
            ->create();

        $this->assertDatabaseCount('products', 1);
        $res = $this->json('get', route('api.products.get', ['product' => $product]));

        $res->assertOk()
            ->assertJsonFragment([
                'id' => $product->id,
                'name' => $product->name,
            ])
            ->assertJsonCount(3, 'data.ingredients');
    }
}

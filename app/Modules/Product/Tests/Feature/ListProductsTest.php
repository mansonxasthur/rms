<?php

namespace App\Modules\Product\Tests\Feature;

use Tests\TestCase;
use App\Modules\Product\Domain\Entities\Product;
use App\Modules\Ingredient\Domain\Entities\Inventory;
use App\Modules\Ingredient\Domain\Entities\Ingredient;

class ListProductsTest extends TestCase
{
    /**
     * @test
     *
     * @group product
     */
    public function it_can_list_products(): void
    {
        Product::factory(5)
            ->hasAttached(
                Ingredient::factory(3)
                    ->has(Inventory::factory()),
                ['amount' => 50]
            )
            ->create();

        $this->assertDatabaseCount('products', 5);

        $res = $this->json('get', route('api.products.list'));

        $res->assertOk()
            ->assertJsonCount(5, 'data');
    }
}
